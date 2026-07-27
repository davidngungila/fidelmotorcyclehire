// ============================================================================
// FEEDTAN COMMUNITY MICROFINANCE - GOOGLE SHEETS TO LARAVEL SYNC
// ============================================================================
// This script synchronizes all data from Google Sheets to Laravel backend
// Features:
// - Auto-sync every 6 hours
// - Manual sync from menu
// - Full data export (customers, balances, transactions, saving plans)
// - Error handling and logging
// - Email notifications
// ============================================================================

// ============================================================================
// SYNC_CONFIGURATION
// ============================================================================
const GS_SYNC_CONFIG = {
    // Google Sheet ID
    sheetId: "1BTEPceHVgfQe4SRdDmRO_3bV6k8jA_2lWCqglDfiPwU",
    
    // Laravel API Configuration
    apiUrl: "https://portal.feedtancmg.org/api/v1",
    
    // Sheet Names
    customerDetailsSheet: "customerdetails",
    savingBalancesSheet: "savingbalances",
    transactionsSheet: "Transactions",
    savingPlansSheet: "Saving Plans",
    
    // Data Ranges
    startRow: 2,
    
    // Email Notifications
    adminEmail: "ecolishe@gmail.com",
    
    // Auto-sync settings
    syncIntervalHours: 6
};

// ============================================================================
// MAIN MENU FUNCTIONS
// ============================================================================

/**
 * Create custom menu in Google Sheets
 */
function onOpen() {
    const ui = SpreadsheetApp.getUi();
    const menu = ui.createMenu('📤 Sync to Laravel');
    
    menu.addItem('🔄 Sync All Data', 'syncAllData')
        .addItem('👤 Sync Customers Only', 'syncCustomersOnly')
        .addItem('💰 Sync Balances Only', 'syncBalancesOnly')
        .addItem('📝 Sync Transactions Only', 'syncTransactionsOnly')
        .addItem('📊 Sync Saving Plans Only', 'syncSavingPlansOnly')
        .addSeparator()
        .addItem('⏰ Setup Auto-Sync (6 Hours)', 'setupAutoSync')
        .addItem('🛑 Stop Auto-Sync', 'stopAutoSync')
        .addSeparator()
        .addItem('📋 View Last Sync Status', 'viewSyncStatus')
        .addItem('📊 View Dashboard', 'showDashboard')
        .addToUi();
}

// ============================================================================
// MAIN SYNC FUNCTIONS
// ============================================================================

/**
 * Sync ALL data to Laravel
 */
function syncAllData() {
    try {
        const ui = SpreadsheetApp.getUi();
        ui.alert('🔄 Sync Started', 'Synchronizing all data to Laravel... Please wait.', ui.ButtonSet.OK);
        
        const startTime = new Date();
        const results = {
            customers: 0,
            balances: 0,
            transactions: 0,
            savingPlans: 0,
            errors: []
        };
        
        // Get all data
        const customers = getCustomerProfiles();
        const balances = getSavingBalances();
        const transactions = getTransactions();
        const savingPlans = getSavingPlans();
        
        // Send to Laravel
        if (customers.length > 0) {
            results.customers = sendCustomersToLaravel(customers);
        }
        
        if (balances.length > 0) {
            results.balances = sendBalancesToLaravel(balances);
        }
        
        if (transactions.length > 0) {
            results.transactions = sendTransactionsToLaravel(transactions);
        }
        
        if (savingPlans.length > 0) {
            results.savingPlans = sendSavingPlansToLaravel(savingPlans);
        }
        
        const endTime = new Date();
        const duration = (endTime - startTime) / 1000;
        
        // Log results
        logSyncResults('full_sync', results, duration);
        
        // Show success message
        const message = `✅ Sync Completed Successfully!\n\n` +
                       `📊 Customers: ${results.customers}\n` +
                       `💰 Balances: ${results.balances}\n` +
                       `📝 Transactions: ${results.transactions}\n` +
                       `📊 Saving Plans: ${results.savingPlans}\n` +
                       `⏱️ Duration: ${duration} seconds\n\n` +
                       `${results.errors.length > 0 ? '⚠️ Errors: ' + results.errors.length : '✅ No errors'}`;
        
        ui.alert('✅ Sync Complete', message, ui.ButtonSet.OK);
        
        // Send email notification
        sendSyncNotification('Full Sync', results, duration);
        
        return results;
        
    } catch (error) {
        const ui = SpreadsheetApp.getUi();
        ui.alert('❌ Sync Failed', 'Error: ' + error.message, ui.ButtonSet.OK);
        Logger.log('Sync error: ' + error.message + '\nStack: ' + error.stack);
        sendErrorNotification(error);
        return { success: false, error: error.message };
    }
}

/**
 * Sync only customers to Laravel
 */
function syncCustomersOnly() {
    try {
        const customers = getCustomerProfiles();
        const result = sendCustomersToLaravel(customers);
        
        const ui = SpreadsheetApp.getUi();
        ui.alert('✅ Customers Synced', `${result} customers synced successfully.`, ui.ButtonSet.OK);
        
        return result;
    } catch (error) {
        Logger.log('Error syncing customers: ' + error.message);
        return 0;
    }
}

/**
 * Sync only balances to Laravel
 */
function syncBalancesOnly() {
    try {
        const balances = getSavingBalances();
        const result = sendBalancesToLaravel(balances);
        
        const ui = SpreadsheetApp.getUi();
        ui.alert('✅ Balances Synced', `${result} balances synced successfully.`, ui.ButtonSet.OK);
        
        return result;
    } catch (error) {
        Logger.log('Error syncing balances: ' + error.message);
        return 0;
    }
}

/**
 * Sync only transactions to Laravel
 */
function syncTransactionsOnly() {
    try {
        const transactions = getTransactions();
        const result = sendTransactionsToLaravel(transactions);
        
        const ui = SpreadsheetApp.getUi();
        ui.alert('✅ Transactions Synced', `${result} transactions synced successfully.`, ui.ButtonSet.OK);
        
        return result;
    } catch (error) {
        Logger.log('Error syncing transactions: ' + error.message);
        return 0;
    }
}

/**
 * Sync only saving plans to Laravel
 */
function syncSavingPlansOnly() {
    try {
        const savingPlans = getSavingPlans();
        const result = sendSavingPlansToLaravel(savingPlans);
        
        const ui = SpreadsheetApp.getUi();
        ui.alert('✅ Saving Plans Synced', `${result} saving plans synced successfully.`, ui.ButtonSet.OK);
        
        return result;
    } catch (error) {
        Logger.log('Error syncing saving plans: ' + error.message);
        return 0;
    }
}

// ============================================================================
// DATA EXTRACTION FUNCTIONS
// ============================================================================

/**
 * Get customer profiles from customerdetails sheet
 */
function getCustomerProfiles() {
    try {
        const ss = SpreadsheetApp.openById(GS_SYNC_CONFIG.sheetId);
        const sheet = ss.getSheetByName(GS_SYNC_CONFIG.customerDetailsSheet);
        
        if (!sheet) {
            Logger.log('Customer details sheet not found');
            return [];
        }
        
        const lastRow = sheet.getLastRow();
        const lastCol = sheet.getLastColumn();
        
        if (lastRow < GS_SYNC_CONFIG.startRow) {
            return [];
        }
        
        const headers = sheet.getRange(1, 1, 1, lastCol).getValues()[0];
        const data = sheet.getRange(GS_SYNC_CONFIG.startRow, 1, lastRow - GS_SYNC_CONFIG.startRow + 1, lastCol).getValues();
        
        const customers = [];
        
        data.forEach(row => {
            const customerId = String(row[1] || '').trim();
            if (customerId) {
                customers.push({
                    customer_id: customerId,
                    customer_name: String(row[0] || '').trim(),
                    email_address: String(row[2] || '').trim(),
                    phone_number: String(row[3] || '').trim(),
                    member_type: String(row[5] || '').trim(),
                    start_date: row[3] ? formatDateForAPI(row[3]) : null,
                    end_date: row[4] ? formatDateForAPI(row[4]) : null,
                    account_status: 'Active',
                    metadata: {
                        imported_from: 'google_sheets',
                        import_date: new Date().toISOString()
                    }
                });
            }
        });
        
        return customers;
        
    } catch (error) {
        Logger.log('Error getting customer profiles: ' + error.message);
        return [];
    }
}

/**
 * Get saving plans from Saving Plans sheet
 */
function getSavingPlans() {
    try {
        const ss = SpreadsheetApp.openById(GS_SYNC_CONFIG.sheetId);
        const sheet = ss.getSheetByName(GS_SYNC_CONFIG.savingPlansSheet);
        
        if (!sheet) {
            Logger.log('Saving Plans sheet not found');
            return [];
        }
        
        const lastRow = sheet.getLastRow();
        if (lastRow < 2) return [];
        
        const data = sheet.getRange(2, 1, lastRow - 1, 5).getValues();
        const plans = [];
        
        data.forEach(row => {
            const planId = String(row[1] || '').trim();
            if (planId) {
                plans.push({
                    plan_id: planId,
                    name: String(row[0] || '').trim(),
                    membership: String(row[2] || '').trim(),
                    monthly_goal: parseFloat(row[3]) || 0,
                    goal: parseFloat(row[4]) || 0,
                    metadata: {
                        imported_from: 'google_sheets',
                        import_date: new Date().toISOString()
                    }
                });
            }
        });
        
        return plans;
        
    } catch (error) {
        Logger.log('Error getting saving plans: ' + error.message);
        return [];
    }
}

/**
 * Get saving balances from savingbalances sheet
 */
function getSavingBalances() {
    try {
        const ss = SpreadsheetApp.openById(GS_SYNC_CONFIG.sheetId);
        const sheet = ss.getSheetByName(GS_SYNC_CONFIG.savingBalancesSheet);
        
        if (!sheet) {
            Logger.log('Saving balances sheet not found');
            return [];
        }
        
        const lastRow = sheet.getLastRow();
        if (lastRow < 2) return [];
        
        const data = sheet.getRange(2, 1, lastRow - 1, 30).getValues();
        const balances = [];
        
        data.forEach(row => {
            const customerId = String(row[0] || '').trim();
            if (customerId) {
                balances.push({
                    customer_id: customerId,
                    // Monthly Savings (B-G)
                    monthly_saving_target: parseFloat(row[1]) || 0,
                    monthly_total_savings_deposits: parseFloat(row[2]) || 0,
                    monthly_goal_achievement: parseFloat(row[3]) || 0,
                    overall_saving_goal: parseFloat(row[4]) || 0,
                    total_saved: parseFloat(row[5]) || 0,
                    overall_goal_achievement: parseFloat(row[6]) || 0,
                    
                    // Flexi Account (H-K)
                    flexi_opening_balance: parseFloat(row[7]) || 0,
                    flexi_deposit: parseFloat(row[8]) || 0,
                    flexi_withdrawal: parseFloat(row[9]) || 0,
                    flexi_balance: parseFloat(row[10]) || 0,
                    
                    // RDA Account (L-O)
                    rda_opening_balance: parseFloat(row[11]) || 0,
                    rda_deposit: parseFloat(row[12]) || 0,
                    rda_withdrawal: parseFloat(row[13]) || 0,
                    rda_balance: parseFloat(row[14]) || 0,
                    
                    // Emergency Account (P-S)
                    emergency_opening_balance: parseFloat(row[15]) || 0,
                    emergency_deposit: parseFloat(row[16]) || 0,
                    emergency_withdrawal: parseFloat(row[17]) || 0,
                    emergency_balance: parseFloat(row[18]) || 0,
                    
                    // Business Account (T-U)
                    business_opening_balance: parseFloat(row[19]) || 0,
                    business_deposit: parseFloat(row[20]) || 0,
                    business_withdrawal: parseFloat(row[21]) || 0,
                    business_balance: parseFloat(row[22]) || 0,
                    
                    // Totals (V-AD)
                    total_balance: parseFloat(row[23]) || 0,
                    interest_payable: parseFloat(row[24]) || 0,
                    savings_held_for_loan_security: parseFloat(row[25]) || 0,
                    free_savings_emergency: parseFloat(row[26]) || 0,
                    free_savings_rda_flexi_business: parseFloat(row[27]) || 0,
                    total_free_saving: parseFloat(row[28]) || 0,
                    premature_withdraw_charge: parseFloat(row[29]) || 0,
                    
                    metadata: {
                        imported_from: 'google_sheets',
                        import_date: new Date().toISOString()
                    }
                });
            }
        });
        
        return balances;
        
    } catch (error) {
        Logger.log('Error getting saving balances: ' + error.message);
        return [];
    }
}

/**
 * Get transactions from Transactions sheet
 */
function getTransactions() {
    try {
        const ss = SpreadsheetApp.openById(GS_SYNC_CONFIG.sheetId);
        const sheet = ss.getSheetByName(GS_SYNC_CONFIG.transactionsSheet);
        
        if (!sheet) {
            Logger.log('Transactions sheet not found');
            return [];
        }
        
        const lastRow = sheet.getLastRow();
        if (lastRow < 2) return [];
        
        const data = sheet.getRange(2, 1, lastRow - 1, 5).getValues();
        const transactions = [];
        
        data.forEach(row => {
            const customerId = String(row[1] || '').trim();
            if (customerId) {
                const transactionType = String(row[2] || '').toLowerCase();
                const amount = parseFloat(row[4]) || 0;
                
                // Determine account type
                let accountType = 'flexi';
                if (transactionType.includes('rda')) accountType = 'rda';
                else if (transactionType.includes('emergency') || transactionType.includes('emerg')) accountType = 'emergency';
                else if (transactionType.includes('business') || transactionType.includes('biz')) accountType = 'business';
                
                transactions.push({
                    customer_id: customerId,
                    transaction_date: row[0] ? formatDateForAPI(row[0]) : new Date().toISOString().split('T')[0],
                    transaction_type: String(row[2] || '').trim(),
                    reference_no: String(row[3] || '').trim(),
                    amount: amount,
                    account_type: accountType,
                    metadata: {
                        imported_from: 'google_sheets',
                        import_date: new Date().toISOString()
                    }
                });
            }
        });
        
        return transactions;
        
    } catch (error) {
        Logger.log('Error getting transactions: ' + error.message);
        return [];
    }
}

// ============================================================================
// LARAVEL API SENDER FUNCTIONS
// ============================================================================

/**
 * Send customers to Laravel
 */
function sendCustomersToLaravel(customers) {
    if (!customers || customers.length === 0) return 0;
    
    try {
        const payload = { customers: customers };
        const response = sendToLaravel('/sync/customers', payload);
        return response.total || customers.length;
    } catch (error) {
        Logger.log('Error sending customers: ' + error.message);
        return 0;
    }
}

/**
 * Send saving balances to Laravel
 */
function sendBalancesToLaravel(balances) {
    if (!balances || balances.length === 0) return 0;
    
    try {
        const payload = { balances: balances };
        const response = sendToLaravel('/sync/balances', payload);
        return response.total || balances.length;
    } catch (error) {
        Logger.log('Error sending balances: ' + error.message);
        return 0;
    }
}

/**
 * Send transactions to Laravel
 */
function sendTransactionsToLaravel(transactions) {
    if (!transactions || transactions.length === 0) return 0;
    
    try {
        // Split into chunks to avoid payload size limits
        const chunkSize = 500;
        let total = 0;
        
        for (let i = 0; i < transactions.length; i += chunkSize) {
            const chunk = transactions.slice(i, i + chunkSize);
            const payload = { transactions: chunk };
            const response = sendToLaravel('/sync/transactions', payload);
            total += response.total || chunk.length;
        }
        
        return total;
    } catch (error) {
        Logger.log('Error sending transactions: ' + error.message);
        return 0;
    }
}

/**
 * Send saving plans to Laravel
 */
function sendSavingPlansToLaravel(plans) {
    if (!plans || plans.length === 0) return 0;
    
    try {
        const payload = { saving_plans: plans };
        const response = sendToLaravel('/sync/saving-plans', payload);
        return response.total || plans.length;
    } catch (error) {
        Logger.log('Error sending saving plans: ' + error.message);
        return 0;
    }
}

/**
 * Send data to Laravel API
 */
function sendToLaravel(endpoint, payload) {
    try {
        const options = {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            payload: JSON.stringify(payload),
            muteHttpExceptions: true
        };
        
        const url = GS_SYNC_CONFIG.apiUrl + endpoint;
        const response = UrlFetchApp.fetch(url, options);
        const responseData = JSON.parse(response.getContentText());
        
        if (!responseData.success) {
            throw new Error(responseData.message || 'API request failed');
        }
        
        return responseData;
        
    } catch (error) {
        Logger.log('API request error: ' + error.message);
        throw error;
    }
}

// ============================================================================
// AUTO-SYNC FUNCTIONS
// ============================================================================

/**
 * Setup auto-sync every 6 hours
 */
function setupAutoSync() {
    try {
        // Delete existing triggers
        const triggers = ScriptApp.getProjectTriggers();
        triggers.forEach(trigger => {
            if (trigger.getHandlerFunction() === 'autoSync') {
                ScriptApp.deleteTrigger(trigger);
            }
        });
        
        // Create new trigger
        ScriptApp.newTrigger('autoSync')
            .timeBased()
            .everyHours(GS_SYNC_CONFIG.syncIntervalHours)
            .create();
        
        const ui = SpreadsheetApp.getUi();
        ui.alert('✅ Auto-Sync Setup Complete', 
            `Auto-sync will run every ${GS_SYNC_CONFIG.syncIntervalHours} hours.\n\n` +
            'Next sync will run automatically.', 
            ui.ButtonSet.OK);
        
        Logger.log('Auto-sync setup complete');
        
    } catch (error) {
        Logger.log('Error setting up auto-sync: ' + error.message);
        const ui = SpreadsheetApp.getUi();
        ui.alert('❌ Auto-Sync Setup Failed', 'Error: ' + error.message, ui.ButtonSet.OK);
    }
}

/**
 * Stop auto-sync
 */
function stopAutoSync() {
    try {
        const triggers = ScriptApp.getProjectTriggers();
        let count = 0;
        
        triggers.forEach(trigger => {
            if (trigger.getHandlerFunction() === 'autoSync') {
                ScriptApp.deleteTrigger(trigger);
                count++;
            }
        });
        
        const ui = SpreadsheetApp.getUi();
        ui.alert('🛑 Auto-Sync Stopped', 
            `${count} auto-sync trigger(s) removed. No further automatic syncs will occur.`, 
            ui.ButtonSet.OK);
        
    } catch (error) {
        Logger.log('Error stopping auto-sync: ' + error.message);
        const ui = SpreadsheetApp.getUi();
        ui.alert('❌ Error', 'Error stopping auto-sync: ' + error.message, ui.ButtonSet.OK);
    }
}

/**
 * Auto-sync function - runs every 6 hours
 */
function autoSync() {
    try {
        Logger.log('Auto-sync started at ' + new Date().toISOString());
        
        const startTime = new Date();
        const results = {
            customers: 0,
            balances: 0,
            transactions: 0,
            savingPlans: 0,
            errors: []
        };
        
        // Get all data
        const customers = getCustomerProfiles();
        const balances = getSavingBalances();
        const transactions = getTransactions();
        const savingPlans = getSavingPlans();
        
        // Send to Laravel
        if (customers.length > 0) {
            results.customers = sendCustomersToLaravel(customers);
        }
        
        if (balances.length > 0) {
            results.balances = sendBalancesToLaravel(balances);
        }
        
        if (transactions.length > 0) {
            results.transactions = sendTransactionsToLaravel(transactions);
        }
        
        if (savingPlans.length > 0) {
            results.savingPlans = sendSavingPlansToLaravel(savingPlans);
        }
        
        const endTime = new Date();
        const duration = (endTime - startTime) / 1000;
        
        // Log results
        logSyncResults('auto_sync', results, duration);
        
        // Send email notification
        sendSyncNotification('Auto-Sync', results, duration);
        
        Logger.log('Auto-sync completed successfully');
        
    } catch (error) {
        Logger.log('Auto-sync error: ' + error.message + '\nStack: ' + error.stack);
        sendErrorNotification(error);
    }
}

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

/**
 * Format date for API
 */
function formatDateForAPI(dateValue) {
    try {
        const date = new Date(dateValue);
        if (isNaN(date.getTime())) {
            return new Date().toISOString().split('T')[0];
        }
        return date.toISOString().split('T')[0];
    } catch (error) {
        return new Date().toISOString().split('T')[0];
    }
}

/**
 * Log sync results
 */
function logSyncResults(type, results, duration) {
    try {
        const ss = SpreadsheetApp.openById(GS_SYNC_CONFIG.sheetId);
        let logSheet = ss.getSheetByName('SyncLogs');
        
        if (!logSheet) {
            logSheet = ss.insertSheet('SyncLogs');
            logSheet.appendRow([
                'Timestamp',
                'Sync Type',
                'Customers',
                'Balances',
                'Transactions',
                'Saving Plans',
                'Duration (s)',
                'Status'
            ]);
            
            // Format header
            logSheet.getRange(1, 1, 1, 8).setBackground('#006400').setFontColor('#FFFFFF').setFontWeight('bold');
        }
        
        const status = results.errors && results.errors.length > 0 ? 'Partial' : 'Success';
        
        logSheet.appendRow([
            new Date().toISOString(),
            type,
            results.customers || 0,
            results.balances || 0,
            results.transactions || 0,
            results.savingPlans || 0,
            duration,
            status
        ]);
        
        // Keep only last 1000 rows
        const maxRows = 1000;
        if (logSheet.getLastRow() > maxRows) {
            logSheet.deleteRows(maxRows + 1, logSheet.getLastRow() - maxRows);
        }
        
    } catch (error) {
        Logger.log('Error logging sync results: ' + error.message);
    }
}

/**
 * Send sync notification email
 */
function sendSyncNotification(type, results, duration) {
    try {
        const subject = `${type} Report - FeedTan CMG`;
        const body = `
            <h2>${type} Report</h2>
            <p><strong>Time:</strong> ${new Date().toISOString()}</p>
            <p><strong>Duration:</strong> ${duration} seconds</p>
            <h3>Results:</h3>
            <ul>
                <li>👤 Customers: ${results.customers || 0}</li>
                <li>💰 Balances: ${results.balances || 0}</li>
                <li>📝 Transactions: ${results.transactions || 0}</li>
                <li>📊 Saving Plans: ${results.savingPlans || 0}</li>
            </ul>
            <p><strong>Status:</strong> ✅ Completed successfully</p>
            <p style="color: #006400; font-weight: bold;">FeedTan Community Microfinance Group</p>
        `;
        
        GmailApp.sendEmail(
            GS_SYNC_CONFIG.adminEmail,
            subject,
            '',
            { htmlBody: body }
        );
        
    } catch (error) {
        Logger.log('Error sending notification: ' + error.message);
    }
}

/**
 * Send error notification email
 */
function sendErrorNotification(error) {
    try {
        const subject = '⚠️ Google Sheets Sync Error - FeedTan CMG';
        const body = `
            <h2>⚠️ Sync Error</h2>
            <p><strong>Time:</strong> ${new Date().toISOString()}</p>
            <p><strong>Error:</strong> ${error.message}</p>
            <p><strong>Stack Trace:</strong></p>
            <pre>${error.stack || 'No stack trace available'}</pre>
            <p style="color: #cc0000;">Please check the Google Sheets script and try again.</p>
            <p style="color: #006400; font-weight: bold;">FeedTan Community Microfinance Group</p>
        `;
        
        GmailApp.sendEmail(
            GS_SYNC_CONFIG.adminEmail,
            subject,
            '',
            { htmlBody: body }
        );
        
    } catch (e) {
        Logger.log('Error sending error notification: ' + e.message);
    }
}

/**
 * View sync status
 */
function viewSyncStatus() {
    try {
        const ss = SpreadsheetApp.openById(GS_SYNC_CONFIG.sheetId);
        const logSheet = ss.getSheetByName('SyncLogs');
        
        if (!logSheet || logSheet.getLastRow() < 2) {
            SpreadsheetApp.getUi().alert('No sync logs found.');
            return;
        }
        
        const lastRow = logSheet.getLastRow();
        const lastSync = logSheet.getRange(lastRow, 1, 1, 8).getValues()[0];
        
        const message = `📊 Last Sync Status\n\n` +
                       `🕐 Time: ${lastSync[0]}\n` +
                       `📋 Type: ${lastSync[1]}\n` +
                       `👤 Customers: ${lastSync[2]}\n` +
                       `💰 Balances: ${lastSync[3]}\n` +
                       `📝 Transactions: ${lastSync[4]}\n` +
                       `📊 Saving Plans: ${lastSync[5]}\n` +
                       `⏱️ Duration: ${lastSync[6]} seconds\n` +
                       `📌 Status: ${lastSync[7]}`;
        
        SpreadsheetApp.getUi().alert('📊 Sync Status', message, ui.ButtonSet.OK);
        
    } catch (error) {
        Logger.log('Error viewing sync status: ' + error.message);
        SpreadsheetApp.getUi().alert('Error', 'Could not retrieve sync status.', ui.ButtonSet.OK);
    }
}

/**
 * Show dashboard with sync statistics
 */
function showDashboard() {
    try {
        const ss = SpreadsheetApp.openById(GS_SYNC_CONFIG.sheetId);
        const logSheet = ss.getSheetByName('SyncLogs');
        
        let stats = '';
        let totalSyncs = 0;
        let successfulSyncs = 0;
        
        if (logSheet && logSheet.getLastRow() > 1) {
            const lastRow = logSheet.getLastRow();
            const data = logSheet.getRange(2, 1, lastRow - 1, 8).getValues();
            
            totalSyncs = data.length;
            successfulSyncs = data.filter(row => row[7] === 'Success').length;
            const lastSync = data[data.length - 1];
            
            stats = `📊 Sync Dashboard\n\n` +
                   `📈 Total Syncs: ${totalSyncs}\n` +
                   `✅ Successful: ${successfulSyncs}\n` +
                   `❌ Failed: ${totalSyncs - successfulSyncs}\n` +
                   `📊 Success Rate: ${totalSyncs > 0 ? Math.round((successfulSyncs / totalSyncs) * 100) : 0}%\n\n` +
                   `🔄 Last Sync:\n` +
                   `   Time: ${lastSync[0]}\n` +
                   `   Type: ${lastSync[1]}\n` +
                   `   Status: ${lastSync[7]}\n` +
                   `   Duration: ${lastSync[6]} seconds\n\n` +
                   `⏰ Next Auto-Sync: ${new Date(Date.now() + GS_SYNC_CONFIG.syncIntervalHours * 3600000).toLocaleString()}`;
        } else {
            stats = '📊 No sync data available yet.\n\n' +
                   'Run your first sync from the 📤 Sync to Laravel menu.';
        }
        
        SpreadsheetApp.getUi().alert('📊 Dashboard', stats, ui.ButtonSet.OK);
        
    } catch (error) {
        Logger.log('Error showing dashboard: ' + error.message);
        SpreadsheetApp.getUi().alert('Error', 'Could not load dashboard.', ui.ButtonSet.OK);
    }
}

// ============================================================================
// WEBHOOK ENDPOINT (For Laravel to trigger sync)
// ============================================================================

/**
 * Webhook endpoint for Laravel to trigger sync
 */
function doPost(e) {
    try {
        const params = JSON.parse(e.postData.contents);
        const action = params.action || 'sync-all';
        const apiKey = params.api_key;
        
        // Verify API key
        if (apiKey !== GS_SYNC_CONFIG.apiKey) {
            return ContentService.createTextOutput(JSON.stringify({
                success: false,
                error: 'Invalid API key'
            })).setMimeType(ContentService.MimeType.JSON);
        }
        
        let result;
        
        switch(action) {
            case 'sync-all':
                result = syncAllData();
                break;
            case 'sync-customers':
                result = syncCustomersOnly();
                break;
            case 'sync-balances':
                result = syncBalancesOnly();
                break;
            case 'sync-transactions':
                result = syncTransactionsOnly();
                break;
            case 'sync-saving-plans':
                result = syncSavingPlansOnly();
                break;
            default:
                result = { success: false, error: 'Unknown action' };
        }
        
        return ContentService.createTextOutput(JSON.stringify({
            success: true,
            data: result,
            timestamp: new Date().toISOString()
        })).setMimeType(ContentService.MimeType.JSON);
        
    } catch (error) {
        return ContentService.createTextOutput(JSON.stringify({
            success: false,
            error: error.message,
            timestamp: new Date().toISOString()
        })).setMimeType(ContentService.MimeType.JSON);
    }
}

// ============================================================================
// TRIGGER SETUP (Run this once to set up all triggers)
// ============================================================================

/**
 * Setup all triggers (run this once)
 */
function setupAllTriggers() {
    try {
        // Delete existing triggers
        const triggers = ScriptApp.getProjectTriggers();
        triggers.forEach(trigger => {
            ScriptApp.deleteTrigger(trigger);
        });
        
        // Setup auto-sync
        ScriptApp.newTrigger('autoSync')
            .timeBased()
            .everyHours(GS_SYNC_CONFIG.syncIntervalHours)
            .create();
        
        // Setup on-open trigger
        ScriptApp.newTrigger('onOpen')
            .onOpen()
            .create();
        
        Logger.log('All triggers setup complete');
        SpreadsheetApp.getUi().alert('✅ All Triggers Setup Complete', 
            'Auto-sync will run every 6 hours.\n\n' +
            'The menu will appear when you open the sheet.', 
            ui.ButtonSet.OK);
        
    } catch (error) {
        Logger.log('Error setting up triggers: ' + error.message);
        SpreadsheetApp.getUi().alert('❌ Error', 'Error setting up triggers: ' + error.message, ui.ButtonSet.OK);
    }
}

// ============================================================================
// INITIALIZATION - Runs when script is loaded
// ============================================================================

// Log startup
Logger.log('FeedTan CMG Sync Script loaded at ' + new Date().toISOString());
Logger.log('Sheet ID: ' + GS_SYNC_CONFIG.sheetId);
Logger.log('API URL: ' + GS_SYNC_CONFIG.apiUrl);
Logger.log('Auto-sync interval: ' + GS_SYNC_CONFIG.syncIntervalHours + ' hours');

// ============================================================================
// END OF SCRIPT
// ============================================================================