<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use RuntimeException;

class GoogleSheetService
{
    protected ?object $client = null;

    protected ?object $service = null;

    protected ?string $spreadsheetId = null;

    protected array $config = [];

    protected int $maxRetries = 3;

    protected int $retryDelayMs = 500;

    public function __construct(
        protected LoggerInterface $logger,
    ) {
    }

    public function authenticateUsingServiceAccount(array $config): void
    {
        $requiredKeys = ['type', 'project_id', 'private_key_id', 'private_key', 'client_email', 'client_id'];
        foreach ($requiredKeys as $key) {
            if (empty($config[$key])) {
                throw new InvalidArgumentException("Missing required Google service account config key: {$key}");
            }
        }

        $this->config = $config;
        $this->spreadsheetId = $config['spreadsheet_id'] ?? null;

        if (! class_exists(\Google\Client::class)) {
            $this->logger->warning('Google Client SDK not installed. GoogleSheetService will operate in degraded mode. Run: composer require google/apiclient:"^2.0"');
            $this->client = null;
            $this->service = null;

            return;
        }

        try {
            $client = new \Google\Client();
            $client->setAuthConfig($config);
            $client->setScopes([\Google\Service\Sheets::SPREADSHEETS]);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            $this->client = $client;
            $this->service = new \Google\Service\Sheets($client);

            $this->logger->info('GoogleSheetsService: Authenticated successfully with service account.', [
                'project_id' => $config['project_id'],
                'client_email' => $config['client_email'],
            ]);
        } catch (Exception $e) {
            $this->logger->error('GoogleSheetsService: Failed to authenticate with Google API.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new RuntimeException('Failed to authenticate with Google Sheets API: '.$e->getMessage(), 0, $e);
        }
    }

    public function fetchSheet(string $spreadsheetId, string $range): array
    {
        if (empty($spreadsheetId)) {
            throw new InvalidArgumentException('Spreadsheet ID cannot be empty.');
        }

        if (empty($range)) {
            throw new InvalidArgumentException('Range cannot be empty.');
        }

        if (! class_exists(\Google\Client::class)) {
            throw new RuntimeException(
                'Google Client SDK is not installed. Please run: composer require google/apiclient:"^2.0" to enable live Google Sheets integration.'
            );
        }

        if ($this->service === null) {
            throw new RuntimeException('GoogleSheetsService is not authenticated. Call authenticateUsingServiceAccount() first.');
        }

        return $this->executeWithRetry(function () use ($spreadsheetId, $range): array {
            $this->logger->debug('GoogleSheetsService: Fetching sheet range.', [
                'spreadsheet_id' => $spreadsheetId,
                'range' => $range,
            ]);

            $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();

            if (empty($values)) {
                $this->logger->warning('GoogleSheetsService: Empty data returned for range.', [
                    'spreadsheet_id' => $spreadsheetId,
                    'range' => $range,
                ]);

                return [];
            }

            $this->logger->info('GoogleSheetsService: Successfully fetched sheet data.', [
                'spreadsheet_id' => $spreadsheetId,
                'range' => $range,
                'row_count' => count($values),
            ]);

            return $values;
        });
    }

    public function parseSheetRows(array $rows): array
    {
        if (empty($rows)) {
            return [];
        }

        $headers = array_map(static function ($header): string {
            return is_string($header) ? trim($header) : (string) $header;
        }, array_shift($rows));

        $normalizedHeaders = $this->normalizeHeaders($headers);

        $result = [];
        foreach ($rows as $rowIndex => $row) {
            $rowData = [];
            foreach ($normalizedHeaders as $headerIndex => $normalized) {
                $value = $row[$headerIndex] ?? null;
                if (is_string($value)) {
                    $value = trim($value);
                }
                $rowData[$normalized] = $value;
                $rowData[$headers[$headerIndex]] = $value;
            }
            $rowData['_row_index'] = $rowIndex + 2;
            $result[] = $rowData;
        }

        return $result;
    }

    public function appendRow(string $spreadsheetId, string $range, array $data): bool
    {
        if (empty($spreadsheetId)) {
            throw new InvalidArgumentException('Spreadsheet ID cannot be empty.');
        }

        if (empty($range)) {
            throw new InvalidArgumentException('Range cannot be empty.');
        }

        if (! class_exists(\Google\Client::class)) {
            throw new RuntimeException(
                'Google Client SDK is not installed. Please run: composer require google/apiclient:"^2.0" to enable live Google Sheets integration.'
            );
        }

        if ($this->service === null) {
            throw new RuntimeException('GoogleSheetsService is not authenticated. Call authenticateUsingServiceAccount() first.');
        }

        try {
            $body = new \Google\Service\Sheets\ValueRange([
                'values' => [array_values($data)]
            ]);

            $params = [
                'valueInputOption' => 'RAW'
            ];

            $this->logger->debug('GoogleSheetsService: Appending row to sheet.', [
                'spreadsheet_id' => $spreadsheetId,
                'range' => $range,
                'data' => $data,
            ]);

            $response = $this->service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);

            $this->logger->info('GoogleSheetsService: Successfully appended row to sheet.', [
                'spreadsheet_id' => $spreadsheetId,
                'range' => $range,
                'updates' => $response->getUpdates() ?? [],
            ]);

            return true;
        } catch (Exception $e) {
            $this->logger->error('GoogleSheetsService: Failed to append row to sheet.', [
                'spreadsheet_id' => $spreadsheetId,
                'range' => $range,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new RuntimeException('Failed to append row to Google Sheets: '.$e->getMessage(), 0, $e);
        }
    }

    public function getSpreadsheetId(): ?string
    {
        return $this->spreadsheetId;
    }

    public function isReady(): bool
    {
        return $this->service !== null && class_exists(\Google\Client::class);
    }

    public function setMaxRetries(int $maxRetries): self
    {
        if ($maxRetries < 1) {
            throw new InvalidArgumentException('Max retries must be at least 1.');
        }

        $this->maxRetries = $maxRetries;

        return $this;
    }

    public function setRetryDelayMs(int $retryDelayMs): self
    {
        if ($retryDelayMs < 0) {
            throw new InvalidArgumentException('Retry delay cannot be negative.');
        }

        $this->retryDelayMs = $retryDelayMs;

        return $this;
    }

    protected function executeWithRetry(callable $callback): mixed
    {
        $attempts = 0;
        $lastException = null;

        while ($attempts < $this->maxRetries) {
            $attempts++;
            try {
                return $callback();
            } catch (\Google\Service\Exception $e) {
                $lastException = $e;
                $statusCode = $e->getCode();
                $isRetriable = in_array($statusCode, [429, 500, 502, 503, 504], true);

                $this->logger->warning('GoogleSheetsService: API request failed.', [
                    'attempt' => $attempts,
                    'max_attempts' => $this->maxRetries,
                    'status_code' => $statusCode,
                    'error' => $e->getMessage(),
                    'retriable' => $isRetriable,
                ]);

                if (! $isRetriable || $attempts >= $this->maxRetries) {
                    break;
                }
            } catch (Exception $e) {
                $lastException = $e;
                $this->logger->warning('GoogleSheetsService: Unexpected exception during API call.', [
                    'attempt' => $attempts,
                    'max_attempts' => $this->maxRetries,
                    'error' => $e->getMessage(),
                ]);

                if ($attempts >= $this->maxRetries) {
                    break;
                }
            }

            $delayUsec = $this->retryDelayMs * 1000 * $attempts;
            usleep($delayUsec);
        }

        $errorMessage = $lastException ? $lastException->getMessage() : 'Unknown error';
        $this->logger->error('GoogleSheetsService: Exhausted all retries for API call.', [
            'attempts' => $attempts,
            'error' => $errorMessage,
        ]);

        throw new RuntimeException(
            "Google Sheets API call failed after {$attempts} attempts: {$errorMessage}",
            0,
            $lastException
        );
    }

    protected function normalizeHeaders(array $headers): array
    {
        return array_map(static function (string $header): string {
            $snake = preg_replace('/\s+/u', '_', $header);
            $snake = preg_replace('/[^A-Za-z0-9_]/u', '', (string) $snake);

            return strtolower((string) $snake);
        }, $headers);
    }
}
