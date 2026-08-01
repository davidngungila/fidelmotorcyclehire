<?php

namespace App\Services;

class ErrorMessagesService
{
    protected array $httpErrors = [
        400 => [
            'title' => 'Bad Request',
            'description' => 'The request could not be understood due to invalid syntax or missing parameters.',
            'icon' => 'fa-circle-exclamation',
            'color' => 'orange'
        ],
        401 => [
            'title' => 'Unauthorized',
            'description' => 'Authentication is required to access this resource.',
            'icon' => 'fa-lock',
            'color' => 'red'
        ],
        402 => [
            'title' => 'Payment Required',
            'description' => 'Reserved for future use; often used for subscription or payment systems.',
            'icon' => 'fa-credit-card',
            'color' => 'yellow'
        ],
        403 => [
            'title' => 'Forbidden',
            'description' => 'You don\'t have permission to access this resource.',
            'icon' => 'fa-ban',
            'color' => 'red'
        ],
        404 => [
            'title' => 'Not Found',
            'description' => 'The requested page or resource could not be found.',
            'icon' => 'fa-ghost',
            'color' => 'gray'
        ],
        405 => [
            'title' => 'Method Not Allowed',
            'description' => 'The HTTP method used is not supported for this resource.',
            'icon' => 'fa-code',
            'color' => 'orange'
        ],
        406 => [
            'title' => 'Not Acceptable',
            'description' => 'The requested response format is unavailable.',
            'icon' => 'fa-file-code',
            'color' => 'orange'
        ],
        407 => [
            'title' => 'Proxy Authentication Required',
            'description' => 'Authentication with a proxy server is required.',
            'icon' => 'fa-shield-halved',
            'color' => 'red'
        ],
        408 => [
            'title' => 'Request Timeout',
            'description' => 'The server timed out waiting for the request.',
            'icon' => 'fa-clock',
            'color' => 'yellow'
        ],
        409 => [
            'title' => 'Conflict',
            'description' => 'The request conflicts with the current state of the resource.',
            'icon' => 'fa-triangle-exclamation',
            'color' => 'orange'
        ],
        410 => [
            'title' => 'Gone',
            'description' => 'The requested resource has been permanently removed.',
            'icon' => 'fa-trash-can',
            'color' => 'gray'
        ],
        411 => [
            'title' => 'Length Required',
            'description' => 'Content-Length header is required.',
            'icon' => 'fa-ruler',
            'color' => 'orange'
        ],
        412 => [
            'title' => 'Precondition Failed',
            'description' => 'Preconditions specified in the request failed.',
            'icon' => 'fa-xmark-circle',
            'color' => 'orange'
        ],
        413 => [
            'title' => 'Payload Too Large',
            'description' => 'The uploaded file or request body exceeds the allowed size.',
            'icon' => 'fa-weight-hanging',
            'color' => 'red'
        ],
        414 => [
            'title' => 'URI Too Long',
            'description' => 'The requested URL is too long.',
            'icon' => 'fa-link',
            'color' => 'orange'
        ],
        415 => [
            'title' => 'Unsupported Media Type',
            'description' => 'The uploaded file type is not supported.',
            'icon' => 'fa-file',
            'color' => 'orange'
        ],
        416 => [
            'title' => 'Range Not Satisfiable',
            'description' => 'The requested byte range is invalid.',
            'icon' => 'fa-arrows-left-right',
            'color' => 'orange'
        ],
        417 => [
            'title' => 'Expectation Failed',
            'description' => 'The server cannot meet the Expect header requirements.',
            'icon' => 'fa-face-frown',
            'color' => 'orange'
        ],
        418 => [
            'title' => 'I\'m a Teapot',
            'description' => 'April Fools\' joke status code, occasionally used for testing.',
            'icon' => 'fa-mug-hot',
            'color' => 'blue'
        ],
        419 => [
            'title' => 'Page Expired',
            'description' => 'Session expired or CSRF token mismatch (common in Laravel).',
            'icon' => 'fa-hourglass-end',
            'color' => 'yellow'
        ],
        422 => [
            'title' => 'Unprocessable Entity',
            'description' => 'Validation failed or invalid input data.',
            'icon' => 'fa-circle-xmark',
            'color' => 'orange'
        ],
        423 => [
            'title' => 'Locked',
            'description' => 'The requested resource is locked.',
            'icon' => 'fa-lock',
            'color' => 'red'
        ],
        424 => [
            'title' => 'Failed Dependency',
            'description' => 'A previous request dependency failed.',
            'icon' => 'fa-link-slash',
            'color' => 'orange'
        ],
        425 => [
            'title' => 'Too Early',
            'description' => 'The server is unwilling to process the request yet.',
            'icon' => 'fa-hourglass-start',
            'color' => 'yellow'
        ],
        426 => [
            'title' => 'Upgrade Required',
            'description' => 'Client must switch to a different protocol.',
            'icon' => 'fa-arrow-up',
            'color' => 'blue'
        ],
        429 => [
            'title' => 'Too Many Requests',
            'description' => 'Rate limit exceeded. Please try again later.',
            'icon' => 'fa-gauge-high',
            'color' => 'red'
        ],
        431 => [
            'title' => 'Request Header Fields Too Large',
            'description' => 'Request headers are too large.',
            'icon' => 'fa-heading',
            'color' => 'orange'
        ],
        451 => [
            'title' => 'Unavailable For Legal Reasons',
            'description' => 'Access blocked due to legal restrictions.',
            'icon' => 'fa-gavel',
            'color' => 'red'
        ],
        500 => [
            'title' => 'Internal Server Error',
            'description' => 'An unexpected server error occurred.',
            'icon' => 'fa-server',
            'color' => 'red'
        ],
        501 => [
            'title' => 'Not Implemented',
            'description' => 'The requested functionality is not implemented.',
            'icon' => 'fa-wrench',
            'color' => 'orange'
        ],
        502 => [
            'title' => 'Bad Gateway',
            'description' => 'Invalid response from an upstream server.',
            'icon' => 'fa-network-wired',
            'color' => 'red'
        ],
        503 => [
            'title' => 'Service Unavailable',
            'description' => 'The server is temporarily unavailable or under maintenance.',
            'icon' => 'fa-power-off',
            'color' => 'yellow'
        ],
        504 => [
            'title' => 'Gateway Timeout',
            'description' => 'The upstream server took too long to respond.',
            'icon' => 'fa-clock',
            'color' => 'red'
        ],
        505 => [
            'title' => 'HTTP Version Not Supported',
            'description' => 'The HTTP version is not supported.',
            'icon' => 'fa-code',
            'color' => 'orange'
        ],
        507 => [
            'title' => 'Insufficient Storage',
            'description' => 'The server has insufficient storage to complete the request.',
            'icon' => 'fa-hard-drive',
            'color' => 'red'
        ],
        508 => [
            'title' => 'Loop Detected',
            'description' => 'Infinite loop detected while processing the request.',
            'icon' => 'fa-rotate',
            'color' => 'red'
        ],
    ];

    protected array $authenticationErrors = [
        'invalid_credentials' => [
            'title' => 'Invalid Credentials',
            'description' => 'The username or password you entered is incorrect.',
            'icon' => 'fa-user-lock',
            'color' => 'red',
            'code' => 401
        ],
        'incorrect_password' => [
            'title' => 'Incorrect Password',
            'description' => 'The password you entered is incorrect.',
            'icon' => 'fa-key',
            'color' => 'red',
            'code' => 401
        ],
        'invalid_email' => [
            'title' => 'Invalid Email Address',
            'description' => 'The email address you entered is not valid.',
            'icon' => 'fa-envelope',
            'color' => 'orange',
            'code' => 422
        ],
        'account_locked' => [
            'title' => 'Account Locked',
            'description' => 'Your account has been locked due to multiple failed login attempts.',
            'icon' => 'fa-lock',
            'color' => 'red',
            'code' => 423
        ],
        'account_suspended' => [
            'title' => 'Account Suspended',
            'description' => 'Your account has been suspended. Please contact support.',
            'icon' => 'fa-ban',
            'color' => 'red',
            'code' => 403
        ],
        'account_disabled' => [
            'title' => 'Account Disabled',
            'description' => 'Your account has been disabled. Please contact support.',
            'icon' => 'fa-user-slash',
            'color' => 'red',
            'code' => 403
        ],
        'email_not_verified' => [
            'title' => 'Email Not Verified',
            'description' => 'Please verify your email address to access this feature.',
            'icon' => 'fa-envelope-circle-check',
            'color' => 'yellow',
            'code' => 403
        ],
        '2fa_required' => [
            'title' => 'Two-Factor Authentication Required',
            'description' => 'Please complete two-factor authentication to continue.',
            'icon' => 'fa-shield-halved',
            'color' => 'yellow',
            'code' => 403
        ],
        'invalid_verification_code' => [
            'title' => 'Invalid Verification Code',
            'description' => 'The verification code you entered is invalid or expired.',
            'icon' => 'fa-circle-xmark',
            'color' => 'red',
            'code' => 422
        ],
        'password_expired' => [
            'title' => 'Password Expired',
            'description' => 'Your password has expired. Please create a new password.',
            'icon' => 'fa-key',
            'color' => 'yellow',
            'code' => 403
        ],
        'session_expired' => [
            'title' => 'Session Expired',
            'description' => 'Your session has expired. Please log in again.',
            'icon' => 'fa-clock',
            'color' => 'yellow',
            'code' => 419
        ],
        'csrf_mismatch' => [
            'title' => 'CSRF Token Mismatch',
            'description' => 'Security token mismatch. Please refresh the page and try again.',
            'icon' => 'fa-shield-virus',
            'color' => 'red',
            'code' => 419
        ],
    ];

    protected array $authorizationErrors = [
        'access_denied' => [
            'title' => 'Access Denied',
            'description' => 'You do not have permission to access this resource.',
            'icon' => 'fa-ban',
            'color' => 'red',
            'code' => 403
        ],
        'insufficient_permissions' => [
            'title' => 'Insufficient Permissions',
            'description' => 'You do not have the required permissions for this action.',
            'icon' => 'fa-user-shield',
            'color' => 'red',
            'code' => 403
        ],
        'role_required' => [
            'title' => 'Role Required',
            'description' => 'This action requires a specific role that you do not have.',
            'icon' => 'fa-user-tag',
            'color' => 'red',
            'code' => 403
        ],
        'resource_ownership_required' => [
            'title' => 'Resource Ownership Required',
            'description' => 'You can only access resources that belong to you.',
            'icon' => 'fa-user-lock',
            'color' => 'red',
            'code' => 403
        ],
        'admin_access_only' => [
            'title' => 'Admin Access Only',
            'description' => 'This resource is restricted to administrators only.',
            'icon' => 'fa-user-shield',
            'color' => 'red',
            'code' => 403
        ],
        'super_admin_access_only' => [
            'title' => 'Super Admin Access Only',
            'description' => 'This resource is restricted to super administrators only.',
            'icon' => 'fa-crown',
            'color' => 'red',
            'code' => 403
        ],
    ];

    protected array $validationErrors = [
        'required_field_missing' => [
            'title' => 'Required Field Missing',
            'description' => 'One or more required fields are missing.',
            'icon' => 'fa-asterisk',
            'color' => 'orange',
            'code' => 422
        ],
        'invalid_email_format' => [
            'title' => 'Invalid Email Format',
            'description' => 'The email format is invalid.',
            'icon' => 'fa-envelope',
            'color' => 'orange',
            'code' => 422
        ],
        'invalid_phone_number' => [
            'title' => 'Invalid Phone Number',
            'description' => 'The phone number format is invalid.',
            'icon' => 'fa-phone',
            'color' => 'orange',
            'code' => 422
        ],
        'password_too_short' => [
            'title' => 'Password Too Short',
            'description' => 'Password must be at least 8 characters long.',
            'icon' => 'fa-key',
            'color' => 'orange',
            'code' => 422
        ],
        'password_confirmation_mismatch' => [
            'title' => 'Password Confirmation Doesn\'t Match',
            'description' => 'The password confirmation does not match.',
            'icon' => 'fa-key',
            'color' => 'orange',
            'code' => 422
        ],
        'duplicate_record' => [
            'title' => 'Duplicate Record',
            'description' => 'A record with this information already exists.',
            'icon' => 'fa-clone',
            'color' => 'orange',
            'code' => 409
        ],
        'invalid_date' => [
            'title' => 'Invalid Date',
            'description' => 'The date format or value is invalid.',
            'icon' => 'fa-calendar',
            'color' => 'orange',
            'code' => 422
        ],
        'invalid_file_type' => [
            'title' => 'Invalid File Type',
            'description' => 'The file type is not supported.',
            'icon' => 'fa-file',
            'color' => 'orange',
            'code' => 415
        ],
        'file_too_large' => [
            'title' => 'File Too Large',
            'description' => 'The file exceeds the maximum allowed size.',
            'icon' => 'fa-weight-hanging',
            'color' => 'red',
            'code' => 413
        ],
    ];

    protected array $fileUploadErrors = [
        'upload_failed' => [
            'title' => 'Upload Failed',
            'description' => 'The file upload failed. Please try again.',
            'icon' => 'fa-cloud-arrow-up',
            'color' => 'red',
            'code' => 500
        ],
        'file_not_found' => [
            'title' => 'File Not Found',
            'description' => 'The requested file could not be found.',
            'icon' => 'fa-file-circle-question',
            'color' => 'gray',
            'code' => 404
        ],
        'unsupported_file_format' => [
            'title' => 'Unsupported File Format',
            'description' => 'This file format is not supported.',
            'icon' => 'fa-file',
            'color' => 'orange',
            'code' => 415
        ],
        'virus_detected' => [
            'title' => 'Virus Detected',
            'description' => 'The uploaded file contains a virus and was rejected.',
            'icon' => 'fa-virus',
            'color' => 'red',
            'code' => 422
        ],
        'storage_full' => [
            'title' => 'Storage Full',
            'description' => 'The server storage is full. Cannot upload files.',
            'icon' => 'fa-hard-drive',
            'color' => 'red',
            'code' => 507
        ],
        'upload_interrupted' => [
            'title' => 'Upload Interrupted',
            'description' => 'The upload was interrupted. Please try again.',
            'icon' => 'fa-circle-pause',
            'color' => 'yellow',
            'code' => 408
        ],
    ];

    protected array $databaseErrors = [
        'database_connection_failed' => [
            'title' => 'Database Connection Failed',
            'description' => 'Could not connect to the database. Please try again later.',
            'icon' => 'fa-database',
            'color' => 'red',
            'code' => 503
        ],
        'record_not_found' => [
            'title' => 'Record Not Found',
            'description' => 'The requested record could not be found in the database.',
            'icon' => 'fa-database',
            'color' => 'gray',
            'code' => 404
        ],
        'duplicate_entry' => [
            'title' => 'Duplicate Entry',
            'description' => 'A duplicate entry already exists in the database.',
            'icon' => 'fa-clone',
            'color' => 'orange',
            'code' => 409
        ],
        'foreign_key_constraint_failed' => [
            'title' => 'Foreign Key Constraint Failed',
            'description' => 'Cannot delete or update due to related records.',
            'icon' => 'fa-link',
            'color' => 'red',
            'code' => 422
        ],
        'transaction_failed' => [
            'title' => 'Transaction Failed',
            'description' => 'The database transaction failed. Please try again.',
            'icon' => 'fa-database',
            'color' => 'red',
            'code' => 500
        ],
        'query_timeout' => [
            'title' => 'Query Timeout',
            'description' => 'The database query took too long to execute.',
            'icon' => 'fa-clock',
            'color' => 'red',
            'code' => 504
        ],
    ];

    protected array $paymentErrors = [
        'payment_failed' => [
            'title' => 'Payment Failed',
            'description' => 'The payment could not be processed. Please try again.',
            'icon' => 'fa-credit-card',
            'color' => 'red',
            'code' => 402
        ],
        'insufficient_balance' => [
            'title' => 'Insufficient Balance',
            'description' => 'You do not have sufficient balance for this transaction.',
            'icon' => 'fa-wallet',
            'color' => 'red',
            'code' => 402
        ],
        'card_declined' => [
            'title' => 'Card Declined',
            'description' => 'Your card was declined. Please use a different payment method.',
            'icon' => 'fa-credit-card',
            'color' => 'red',
            'code' => 402
        ],
        'invalid_payment_method' => [
            'title' => 'Invalid Payment Method',
            'description' => 'The payment method is invalid or expired.',
            'icon' => 'fa-credit-card',
            'color' => 'orange',
            'code' => 422
        ],
        'transaction_cancelled' => [
            'title' => 'Transaction Cancelled',
            'description' => 'The transaction was cancelled.',
            'icon' => 'fa-circle-xmark',
            'color' => 'yellow',
            'code' => 409
        ],
        'payment_timeout' => [
            'title' => 'Payment Timeout',
            'description' => 'The payment gateway timed out. Please try again.',
            'icon' => 'fa-clock',
            'color' => 'red',
            'code' => 504
        ],
    ];

    protected array $networkErrors = [
        'connection_lost' => [
            'title' => 'Connection Lost',
            'description' => 'Your internet connection was lost. Please check your connection.',
            'icon' => 'fa-wifi',
            'color' => 'red',
            'code' => 503
        ],
        'network_timeout' => [
            'title' => 'Network Timeout',
            'description' => 'The request timed out due to network issues.',
            'icon' => 'fa-clock',
            'color' => 'yellow',
            'code' => 504
        ],
        'dns_resolution_failed' => [
            'title' => 'DNS Resolution Failed',
            'description' => 'Could not resolve the server address.',
            'icon' => 'fa-globe',
            'color' => 'red',
            'code' => 502
        ],
        'ssl_certificate_error' => [
            'title' => 'SSL Certificate Error',
            'description' => 'There is an issue with the SSL certificate.',
            'icon' => 'fa-lock',
            'color' => 'red',
            'code' => 502
        ],
        'internet_connection_required' => [
            'title' => 'Internet Connection Required',
            'description' => 'An internet connection is required to access this feature.',
            'icon' => 'fa-wifi',
            'color' => 'yellow',
            'code' => 503
        ],
    ];

    protected array $systemErrors = [
        'maintenance_mode' => [
            'title' => 'Maintenance Mode',
            'description' => 'The system is currently under maintenance. Please try again later.',
            'icon' => 'fa-screwdriver-wrench',
            'color' => 'yellow',
            'code' => 503
        ],
        'feature_not_available' => [
            'title' => 'Feature Not Available',
            'description' => 'This feature is not available at the moment.',
            'icon' => 'fa-toggle-off',
            'color' => 'gray',
            'code' => 501
        ],
        'unexpected_error' => [
            'title' => 'Unexpected Error',
            'description' => 'An unexpected error occurred. Please try again.',
            'icon' => 'fa-triangle-exclamation',
            'color' => 'red',
            'code' => 500
        ],
        'service_temporarily_unavailable' => [
            'title' => 'Service Temporarily Unavailable',
            'description' => 'The service is temporarily unavailable. Please try again later.',
            'icon' => 'fa-power-off',
            'color' => 'yellow',
            'code' => 503
        ],
        'configuration_error' => [
            'title' => 'Configuration Error',
            'description' => 'There is a configuration error. Please contact support.',
            'icon' => 'fa-gear',
            'color' => 'red',
            'code' => 500
        ],
        'resource_busy' => [
            'title' => 'Resource Busy',
            'description' => 'The requested resource is currently busy. Please try again later.',
            'icon' => 'fa-spinner',
            'color' => 'yellow',
            'code' => 503
        ],
    ];

    public function getHttpError(int $code): ?array
    {
        return $this->httpErrors[$code] ?? null;
    }

    public function getAuthenticationError(string $key): ?array
    {
        return $this->authenticationErrors[$key] ?? null;
    }

    public function getAuthorizationError(string $key): ?array
    {
        return $this->authorizationErrors[$key] ?? null;
    }

    public function getValidationError(string $key): ?array
    {
        return $this->validationErrors[$key] ?? null;
    }

    public function getFileUploadError(string $key): ?array
    {
        return $this->fileUploadErrors[$key] ?? null;
    }

    public function getDatabaseError(string $key): ?array
    {
        return $this->databaseErrors[$key] ?? null;
    }

    public function getPaymentError(string $key): ?array
    {
        return $this->paymentErrors[$key] ?? null;
    }

    public function getNetworkError(string $key): ?array
    {
        return $this->networkErrors[$key] ?? null;
    }

    public function getSystemError(string $key): ?array
    {
        return $this->systemErrors[$key] ?? null;
    }

    public function getError(string $type, string $key): ?array
    {
        return match($type) {
            'authentication' => $this->getAuthenticationError($key),
            'authorization' => $this->getAuthorizationError($key),
            'validation' => $this->getValidationError($key),
            'file_upload' => $this->getFileUploadError($key),
            'database' => $this->getDatabaseError($key),
            'payment' => $this->getPaymentError($key),
            'network' => $this->getNetworkError($key),
            'system' => $this->getSystemError($key),
            default => null,
        };
    }

    public function getAllErrors(): array
    {
        return [
            'http' => $this->httpErrors,
            'authentication' => $this->authenticationErrors,
            'authorization' => $this->authorizationErrors,
            'validation' => $this->validationErrors,
            'file_upload' => $this->fileUploadErrors,
            'database' => $this->databaseErrors,
            'payment' => $this->paymentErrors,
            'network' => $this->networkErrors,
            'system' => $this->systemErrors,
        ];
    }
}
