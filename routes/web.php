<?php

use App\Models\Brand;
use App\Helpers\Helper;
use App\Models\BankAccount;
use App\Models\Promotion;
use App\Models\PromotionCost;
// use Illuminate\Routing\Route;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


Route::get('test', function() {
    
    $promotion_credit_frees = Promotion::whereBrandId(30)->whereTypePromotion(6)->get();

    $promotion_costs = PromotionCost::with('promotion')->whereCustomerId($customer->id)->whereIn('promotion_id', $promotion_credit_frees->pluck('id'))->get()->take(5);

});

Route::group(['domain' => 'line.' . env('APP_NAME') . '.' . env('APP_DOMAIN')], function () {

    Route::get('/connect/{brand}', 'LineController@index')->name('line');
    Route::post('/connect', 'LineController@store')->name('line.store');
});

    Route::group(['domain' => 'bot.' . env('APP_NAME') . '.' . env('APP_DOMAIN')], function () {

        Route::group(['namespace' => 'Bot'], function () {

            Route::get('/', 'HomeController@index')->name('bot');
            Route::post('/unlock', 'HomeController@unlock')->name('bot.unlock');
            Route::get('/bot-event', 'HomeController@botEvent')->name('bot.event');
            Route::get('/check-status', 'HomeController@checkStatus')->name('bot.check-status');
            Route::get('/bot-restart/{brand_id}', 'BotController@restart')->name('bot.restart');

            Route::post('/bot/bank/{brand_id}', 'BotController@bank')->name('bot.bank');

            Route::post('/bot/deposit/{brand_id}', 'BotController@deposit')->name('bot.deposit');

            Route::post('/bot/withdraw/{brand_id}', 'BotController@withdraw')->name('bot.withdraw');

            Route::get('/bank/{subdomain}','BotController@bank')->name('bot.bank');
            Route::post('/bank/store','BotController@bankStore')->name('bot.bank.store');

            Route::get('/otp/send', 'BotWithdrawController@index')->name('otp');
            Route::get('/otp/get', 'BotWithdrawController@getOtp')->name('otp.get');
            Route::post('/otp/withdraw', 'BotWithdrawController@withdraw')->name('otp.withdraw');

            Route::get('/bot/withdraw/otp/{brand_id}', 'BotController@withdrawOtp')->name('bot.withdraw.otp');
            Route::post('/bot/withdraw-otp/', 'BotController@withdrawOtpStore')->name('bot.withdraw.otp.store');
        });
    });

Route::group(['domain' => 'backend.' . env('APP_NAME') . '.' . env('APP_DOMAIN')], function () {

    Route::post('/bot', 'HomeController@bot')->name('admin.bot');

    Route::group(['namespace' => 'Admin'], function () {

        Route::get('/login', 'AuthController@showLoginForm')->name('admin.login');
        Route::post('/login', 'AuthController@login')->name('admin.login.store');
        Route::post('/logout', 'AuthController@logout')->name('admin.logout');

        Route::group(['middleware' => 'admin'], function () {

            Route::get('', 'HomeController@index')->name('admin');
            Route::post('/change-password', 'HomeController@changePassword')->name('admin.change-password');
            Route::post('/bank-account-transaction/status', 'HomeController@bankAccountTransactionStatus')->name('admin.bank-account-transaction.status');

            //Game Module
            Route::get('/game', 'GameController@index')->name('admin.game');
            Route::post('/game', 'GameController@store')->name('admin.game.store');
            Route::post('/game/delete', 'GameController@delete')->name('admin.game.delete');
            Route::post('/game/update', 'GameController@update')->name('admin.game.update');

            //Treumoney Module
            Route::get('/truemoney', 'TruemoneyController@index')->name('admin.truemoney');
            Route::post('/truemoney', 'TruemoneyController@store')->name('admin.truemoney.store');
            Route::post('/truemoney/delete', 'TruemoneyController@delete')->name('admin.truemoney.delete');
            Route::post('/truemoney/update', 'TruemoneyController@update')->name('admin.truemoney.update');

            //Brand Module
            Route::get('/brand', 'BrandController@index')->name('admin.brand');
            Route::post('/brand', 'BrandController@store')->name('admin.brand.store');
            Route::post('/brand/delete', 'BrandController@delete')->name('admin.brand.delete');
            Route::post('/brand/update', 'BrandController@update')->name('admin.brand.update');
            Route::post('/brand/update/rich-menu-register', 'BrandController@updateRichMenuRegister')->name('admin.brand.update.rich-menu-register');
            Route::post('/brand/update/rich-menu-member', 'BrandController@updateRichMenuMember')->name('admin.brand.update.rich-menu-member');

            //BankAccount Moduls
            Route::get('/bank-account', 'BankAccountController@index')->name('admin.bank-account');
            Route::post('/bank-account', 'BankAccountController@store')->name('admin.bank-account.store');
            Route::post('/bank-account/delete', 'BankAccountController@delete')->name('admin.bank-account.delete');
            Route::post('/bank-account/update', 'BankAccountController@update')->name('admin.bank-account.update');
            Route::post('/bank-account/update-status', 'BankAccountController@updateStatus')->name('admin.bank-account.update-status');

            //User Modeul
            Route::get('/user', 'UserController@index')->name('admin.user');
            Route::post('/user', 'UserController@store')->name('admin.user.store');
            Route::post('/user/reset-password', 'UserController@resetPassword')->name('admin.user.reset-password');
            Route::post('/user/delete', 'UserController@delete')->name('admin.user.delete');
            Route::post('/user/update', 'UserController@update')->name('admin.user.update');
            Route::post('/user/update-staus', 'UserController@updateStatus')->name('admin.user.update-status');

            //Rich Menu
            Route::get('/rich-menu', 'RichMenuController@index')->name('admin.rich-menu');
            Route::post('/rich-menu/create', 'RichMenuController@create')->name('admin.rich-menu.create');
            Route::post('/rich-menu/upload', 'RichMenuController@upload')->name('admin.rich-menu.upload');
            Route::get('/rich-menu/show/{brand_id}', 'RichMenuController@show')->name('admin.rich-menu.show');
            Route::get('/rich-menu/delete/{brand_id}/{rich_menu_id}', 'RichMenuController@delete')->name('admin.rich-menu.delete');
            Route::get('/rich-menu/default/{brand_id}/{rich_menu_id}', 'RichMenuController@default')->name('admin.rich-menu.default');
            Route::get('/rich-menu/image/{brand_id}/{rich_menu_id}', 'RichMenuController@image')->name('admin.rich-menu.image');
        });
    });
});

Route::group(['domain' => 'agent.' . env('APP_NAME') . '.' . env('APP_DOMAIN')], function () {

    Route::group(['namespace' => 'Agent'], function () {

        // Route::get('/bot', 'BotController@index')->name('agent.bot');
        // Route::post('/set-brand', 'BotController@setBrand')->name('agent.bot.set-brand');
        // Route::get('/brand-lists/{brand_id}', 'BotController@brandLists')->name('agent.bot.brand-lists');

        Route::get('/login', 'AuthController@showLoginForm')->name('agent.login');
        Route::post('/login', 'AuthController@login')->name('agent.login.store');
        Route::post('/logout', 'AuthController@logout')->name('agent.logout');
        Route::get('/bot-scb','HomeController@botScb')->name('agent.bot.scb');
        Route::get('/bot-scb/getflag','HomeController@getFlag')->name('agent.bot.getflag');
        Route::post('/bot-scb/register','HomeController@register')->name('agent.bot.register');
        Route::post('/bot-scb/cf-otp','HomeController@cfOtp')->name('agent.bot.cf-otp');

        Route::group(['middleware' => 'agent'], function () {

            Route::get('', 'HomeController@index')->name('agent');
            Route::post('/change-password', 'HomeController@changePassword')->name('agent.change-password');
            Route::post('/bank-account/update-status', 'HomeController@bankAccountUpdateStatus')->name('agent.bank-account.update-status');
            Route::post('/check-credit','HomeController@checkCredit')->name('agent.check-credit');
            Route::get('/notification','HomeController@notification')->name('agent.notification');

            //Wheel Modules
            Route::get('/wheel','WheelController@index')->name('agent.wheel');
            Route::post('/wheel','WheelController@store')->name('agent.wheel.store');
            Route::post('/wheel/update/status','WheelController@updateStatus')->name('agent.wheel.update-status');
            Route::post('/wheel/update/slot','WheelController@updateSlot')->name('agent.wheel.update-slot');
            Route::post('/wheel/update','WheelController@update')->name('agent.wheel.update');

            //Deposit Module 
            Route::get('/deposit', 'DepositController@index')->name('agent.deposit');
            Route::post('/deposit', 'DepositController@store')->name('agent.deposit.store');
            Route::post('/deposit/manual', 'DepositController@manual')->name('agent.deposit.manual');
            Route::get('/deposit/history', 'DepositController@history')->name('agent.deposit.history');
            Route::get('/deposit/export', 'DepositController@export')->name('agent.deposit.export');
            Route::get('/deposit/lists', 'DepositController@lists')->name('agent.deposit.lists');
            Route::post('/deposit/cancel', 'DepositController@cancel')->name('agent.deposit.cancel');
            Route::post('/deposit/update-type-deposit', 'DepositController@updateTypeDeposit')->name('agent.deposit.update-type-deposit');
            Route::get('/deposit/notify', 'DepositController@notify')->name('agent.deposit.notify');
            Route::get('/deposit/find-customer', 'DepositController@findCustomer')->name('agent.deposit.find-customer');

            //Withdraw Module 
            Route::get('/withdraw', 'WithdrawController@index')->name('agent.withdraw');
            Route::post('/withdraw', 'WithdrawController@store')->name('agent.withdraw.store');
            Route::post('/withdraw/approve', 'WithdrawController@approve')->name('agent.withdraw.approve');
            Route::post('/withdraw/cancel', 'WithdrawController@cancel')->name('agent.withdraw.cancel');
            Route::post('/withdraw/refresh', 'WithdrawController@refresh')->name('agent.withdraw.refresh');
            Route::post('/withdraw/manual', 'WithdrawController@manual')->name('agent.withdraw.manual');
            Route::get('/withdraw/history', 'WithdrawController@history')->name('agent.withdraw.history');
            Route::get('/withdraw/lists', 'WithdrawController@lists')->name('agent.withdraw.lists');
            Route::get('/withdraw/export', 'WithdrawController@export')->name('agent.withdraw.export');
            Route::get('/withdraw/notify', 'WithdrawController@notify')->name('agent.withdraw.notify');

            //Customer Module 
            Route::get('/customer', 'CustomerController@index')->name('agent.customer');
            Route::post('/customer/last-promotion', 'CustomerController@lastPromotion')->name('agent.customer.last-promotion');
            Route::post('/customer/update', 'CustomerController@update')->name('agent.customer.update');
            Route::post('/customer/promotion', 'CustomerController@promotion')->name('agent.customer.promotion');
            Route::post('/customer/change-password', 'CustomerController@changePassword')->name('agent.customer.change-password');
            Route::get('/customer/show/{customer_id}','CustomerController@show')->name('agent.customer.show');
            Route::post('/customer/minus-credit','CustomerController@minusCredit')->name('agent.customer.minus-credit');

            //Manual
            Route::get('/manual', 'ManualController@index')->name('agent.manual');
            Route::get('/manual/transaction', 'ManualController@transaction')->name('agent.manual.transaction');
            Route::get('/manual/history', 'ManualController@history')->name('agent.manual.history');
            Route::post('/manual', 'ManualController@store')->name('agent.manual.store');
            Route::post('/manual/credit-free', 'ManualController@creditFree')->name('agent.manual.credit-free');
            Route::get('/manual/update/{customer_id}', 'ManualController@update')->name('agent.manual.update');
            Route::get('/manual/transaction-lists/{brand_id}', 'ManualController@transactionLists')->name('agent.manual.transaction-lists');
            Route::get('/manual/monitor-lists/{brand_id}', 'ManualController@monitorLists')->name('agent.manual.monitor-lists');

            //Brand Module
            Route::get('/brand', 'BrandController@index')->name('agent.brand');
            Route::post('/brand', 'BrandController@store')->name('agent.brand.store');
            Route::post('/brand/delete', 'BrandController@delete')->name('agent.brand.delete');
            Route::post('/brand/update', 'BrandController@update')->name('agent.brand.update');
            Route::post('/brand/update-status-rank', 'BrandController@updateStatusRank')->name('agent.brand.update-status-rank');
            Route::post('/brand/update-rank', 'BrandController@updateRank')->name('agent.brand.update-rank');

            //Promotion Module
            Route::get('/promotion', 'PromotionController@index')->name('agent.promotion');
            Route::post('/promotion', 'PromotionController@store')->name('agent.promotion.store');
            Route::post('/promotion/delete', 'PromotionController@delete')->name('agent.promotion.delete');
            Route::post('/promotion/update', 'PromotionController@update')->name('agent.promotion.update');
            Route::post('/promotion/update-status', 'PromotionController@updateStatus')->name('agent.promotion.update-status');
            Route::post('/promotion/clear', 'PromotionController@clear')->name('agent.promotion.clear');

            //Bank Account Module
            Route::get('/bank-account', 'BankAccountController@index')->name('agent.bank-account');
            Route::post('/bank-account', 'BankAccountController@store')->name('agent.bank-account.store');
            Route::post('/bank-account/delete', 'BankAccountController@delete')->name('agent.bank-account.delete');
            Route::post('/bank-account/update', 'BankAccountController@update')->name('agent.bank-account.update');
            Route::post('/bank-account/update/amount', 'BankAccountController@updateAmount')->name('agent.bank-account.update-amount');
            Route::post('/bank-account/update-status', 'BankAccountController@updateStatus')->name('agent.bank-account.update-status');
            Route::post('/bank-account/update-status-bot', 'BankAccountController@updateStatusBot')->name('agent.bank-account.update-status-bot');
            Route::get('/bank-account/update/amount/{bank_account_id}', 'BankAccountController@updateAmountBot')->name('agent.bank-account.update-amount-bot');

            //User Module
            Route::get('/user', 'UserController@index')->name('agent.user');
            Route::post('/user', 'UserController@store')->name('agent.user.store');
            Route::post('/user/reset-password', 'UserController@resetPassword')->name('agent.user.reset-password');
            Route::post('/user/change-password', 'UserController@changePassword')->name('agent.user.change-password');
            Route::post('/user/delete', 'UserController@delete')->name('agent.user.delete');
            Route::post('/user/update', 'UserController@update')->name('agent.user.update');
            Route::post('/user/update-staus', 'UserController@updateStatus')->name('agent.user.update-status');
            Route::get('/user/event/{user_id}', 'UserController@event')->name('agent.user.event');
            Route::get('/user/event/{user_id}/excel', 'UserController@eventExcel')->name('agent.user.event-excel');

            //Finance Module
            //transfer 
            Route::get('/finance/transfer', 'FinanceController@transfer')->name('agent.transfer');
            Route::post('/finance/transfer', 'FinanceController@transferStore')->name('agent.transfer.store');
            Route::post('/finance/transfer/delete', 'FinanceController@transferDelete')->name('agent.transfer.delete');
            //withdraw
            Route::get('/finance/withdraw', 'FinanceController@withdraw')->name('agent.withdraw-finance');
            Route::post('/finance/withdraw', 'FinanceController@withdrawStore')->name('agent.withdraw-finance.store');
            Route::post('/finance/withdraw/delete', 'FinanceController@withdrawDelete')->name('agent.withdraw-finance.delete');
            //return
            Route::get('/finance/return', 'FinanceController@return')->name('agent.return');
            Route::post('/finance/return', 'FinanceController@returnStore')->name('agent.return.store');
            Route::post('/finance/return/delete', 'FinanceController@returnDelete')->name('agent.return.delete');
            //receive
            Route::get('/finance/receive', 'FinanceController@receive')->name('agent.receive');
            Route::post('/finance/receive', 'FinanceController@receiveStore')->name('agent.receive.store');
            Route::post('/finance/receive/delete', 'FinanceController@receiveDelete')->name('agent.receive.delete');

            //Report Module 
            Route::get('/report/summary', 'ReportController@summary')->name('agent.report.summary');
            Route::post('/report/summary/credit', 'ReportController@summaryCredit')->name('agent.report.summary-credit');
            Route::get('/report/customer', 'ReportController@customer')->name('agent.report.customer');
            Route::get('/report/customer-excel', 'ReportController@customerExcel')->name('agent.report.customer-excel');
            Route::post('/report/customer', 'ReportController@customerUpdate')->name('agent.report.customer-update');
            Route::post('/report/customer/password', 'ReportController@customerPassword')->name('agent.report.customer-password');
            Route::get('/report/deposit', 'ReportController@deposit')->name('agent.report.deposit');
            Route::get('/report/deposit/excel', 'ReportController@depositExcel')->name('agent.report.deposit-excel');
            Route::get('/report/withdraw', 'ReportController@withdraw')->name('agent.report.withdraw');
            Route::get('/report/withdraw/excel', 'ReportController@withdrawExcel')->name('agent.report.withdraw-excel');
            Route::get('/report/promotion', 'ReportController@promotion')->name('agent.report.promotion');
            Route::get('/report/promotion/excel', 'ReportController@promotionExcel')->name('agent.report.promotion-excel');
            Route::get('/report/event', 'ReportController@event')->name('agent.report.event');
            Route::get('/report/event/excel', 'ReportController@eventExcel')->name('agent.report.event-excel');
            Route::get('/report/transaction', 'ReportController@transaction')->name('agent.report.transaction');
            Route::get('/report/transaction/excel', 'ReportController@transactionExcel')->name('agent.report.transaction-excel');
            Route::get('/report/statement', 'ReportController@statement')->name('agent.report.statement');
            Route::get('/report/statement/excel', 'ReportController@statementExcel')->name('agent.report.statement-excel');
            Route::get('/report/bank-account-transaction', 'ReportController@bankAccountTransaction')->name('agent.report.bank-account-transaction');
            Route::post('/report/bank-account-transaction/updated', 'ReportController@bankAccountTransactionUpdate')->name('agent.report.bank-account-transaction.update');

            //Invite Module 
            Route::get('/invite', 'InviteController@index')->name('agent.invite');
            Route::get('/invite/show/{customer_id}', 'InviteController@show')->name('agent.invite.show');

            // CreditFree Module
            Route::get('/credit-free', 'CreditFreeController@index')->name('agent.credit-free');
            Route::post('/credit-free/generate','CreditFreeController@generate')->name('agent.credit-free.generate');
            
            Route::get('/marketing/top','MarketingController@top')->name('agent.marketing.top');
            Route::get('/marketing/customer','MarketingController@customer')->name('agent.marketing.customer');
            Route::post('/marketing/top-chart','MarketingCOntroller@topChart')->name('agent.marketing.top-chart');
            Route::get('/marketing/from-type','MarketingController@fromType')->name('agent.marketing.from-type');

        });
    });
});

Route::group(['domain' => 'super.' . env('APP_NAME') . '.' . env('APP_DOMAIN')], function () {

    Route::group(['namespace' => 'Super'], function () {

        Route::get('/login', 'AuthController@showLoginForm')->name('super.login');
        Route::post('/login', 'AuthController@login')->name('super.login.store');
        Route::post('/logout', 'AuthController@logout')->name('super.logout');

        Route::group(['middleware' => 'super'], function () {
            
            Route::get('', 'HomeController@index')->name('super');
            Route::post('/change-password', 'HomeController@changePassword')->name('super.change-password');

            Route::get('/report/customer', 'ReportController@customer')->name('super.report.customer');
            Route::get('/report/customer-excel', 'ReportController@customerExcel')->name('super.report.customer-excel');
            Route::post('/report/customer', 'ReportController@customerUpdate')->name('super.report.customer-update');
            Route::post('/report/customer/password', 'ReportController@customerPassword')->name('super.report.customer-password');
            Route::get('/report/deposit', 'ReportController@deposit')->name('super.report.deposit');
            Route::get('/report/deposit/excel', 'ReportController@depositExcel')->name('super.report.deposit-excel');
            Route::get('/report/withdraw', 'ReportController@withdraw')->name('super.report.withdraw');
            Route::get('/report/withdraw/excel', 'ReportController@withdrawExcel')->name('super.report.withdraw-excel');
        });

    });

});

Route::group(['domain' => 'support.' . env('APP_NAME') . '.' . env('APP_DOMAIN')], function () {

    Route::group(['namespace' => 'Support'], function () {

        Route::get('/login', 'AuthController@showLoginForm')->name('support.login');
        Route::post('/login', 'AuthController@login')->name('support.login.store');
        Route::post('/logout', 'AuthController@logout')->name('support.logout');

        Route::group(['middleware' => 'support'], function () {
            
            Route::get('', 'HomeController@index')->name('support');
            Route::post('/change-password', 'HomeController@changePassword')->name('support.change-password');
            Route::get('/transaction','HomeController@transaction')->name('support.transaction');
            Route::get('/bankAccount','HomeController@bankAccount')->name('support.bankAccount');
            Route::geT('/logs','HomeController@logs')->name('support.logs');

            // Monitor Module
            Route::get('/monitor','MonitorController@index')->name('support.monitor');
            Route::get('/transaction','HomeController@monitor')->name('support.monitor.transaction');
            Route::get('/bankAccount','HomeController@bankAccount')->name('support.monitor.bankAccount');

            // Annoucement Module
            Route::get('/annoucement','AnnoucementController@index')->name('support.annoucement');
            Route::post('/annoucement','AnnoucementController@store')->name('support.annoucement.store');
            Route::post('/annoucement/update','AnnoucementController@update')->name('support.annoucement.update');
            Route::get('/annoucement/delete/{annoucement_id}','AnnoucementController@delete')->name('support.annoucement.delete');

            //Game Module
            Route::get('/game', 'GameController@index')->name('support.game');
            Route::post('/game', 'GameController@store')->name('support.game.store');
            Route::post('/game/delete', 'GameController@delete')->name('support.game.delete');
            Route::post('/game/update', 'GameController@update')->name('support.game.update');

            //Treumoney Module
            Route::get('/truemoney', 'TruemoneyController@index')->name('support.truemoney');
            Route::post('/truemoney', 'TruemoneyController@store')->name('support.truemoney.store');
            Route::post('/truemoney/delete', 'TruemoneyController@delete')->name('support.truemoney.delete');
            Route::post('/truemoney/update', 'TruemoneyController@update')->name('support.truemoney.update');
            Route::get('/truemoney/check/{bank_account_id}','TruemoneyController@check')->name('support.truemoney.check');

            //User Modeul
            Route::get('/user', 'UserController@index')->name('support.user');
            Route::post('/user', 'UserController@store')->name('support.user.store');
            Route::post('/user/reset-password', 'UserController@resetPassword')->name('support.user.reset-password');
            Route::post('/user/delete', 'UserController@delete')->name('support.user.delete');
            Route::post('/user/update', 'UserController@update')->name('support.user.update');
            Route::post('/user/update-staus', 'UserController@updateStatus')->name('support.user.update-status');

            //Brand Module
            Route::get('/brand', 'BrandController@index')->name('support.brand');
            Route::post('/brand', 'BrandController@store')->name('support.brand.store');
            Route::post('/brand/delete', 'BrandController@delete')->name('support.brand.delete');
            Route::post('/brand/update', 'BrandController@update')->name('support.brand.update');
            Route::post('/brand/update/rich-menu-register', 'BrandController@updateRichMenuRegister')->name('support.brand.update.rich-menu-register');
            Route::post('/brand/update/rich-menu-member', 'BrandController@updateRichMenuMember')->name('support.brand.update.rich-menu-member');

            //BankAccount Moduls
            Route::get('/bank-account', 'BankAccountController@index')->name('support.bank-account');
            Route::post('/bank-account', 'BankAccountController@store')->name('support.bank-account.store');
            Route::post('/bank-account/delete', 'BankAccountController@delete')->name('support.bank-account.delete');
            Route::post('/bank-account/update', 'BankAccountController@update')->name('support.bank-account.update');
            Route::post('/bank-account/update-status', 'BankAccountController@updateStatus')->name('support.bank-account.update-status');
            Route::get('/bank-account/check/{bank_account_id}','BankAccountController@check')->name('support.bank-account.check');
            
        });

    });

});