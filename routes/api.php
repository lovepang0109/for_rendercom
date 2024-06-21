<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ClearingController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\QualityController;
use App\Http\Controllers\RoutingController;

use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

   
Route::prefix('v1')->group( function(){

    Route::get('/rrr', [HomeController::class, 'kkk']);

    Route::post('login',                [ApiController::class, 'login']);
    Route::post('dashboard',            [ApiController::class, 'dashboard']);
    Route::post('report-countries',     [ApiController::class, 'reportCountries']);
    Route::post('report-providers',     [ApiController::class, 'reportProviders']);
    Route::post('report-customers',     [ApiController::class, 'reportCustomers']);
    Route::post('report-mccmnc',        [ApiController::class, 'reportMccMnc']);
    Route::post('report-senders',       [ApiController::class, 'reportSenders']);
    Route::post('last-messages',        [ApiController::class, 'getLastMessages']);
    Route::post('getMccMnc',            [ApiController::class, 'getMccMncWithCode']);
    Route::post('mobile-incident',      [ApiController::class, 'mobileIncident']);
    Route::post('connection-client',    [ConnectionController::class, 'connectionClient']);
    Route::post('connection-provider',  [ConnectionController::class, 'connectionProvider']);

    Route::post('route-general-countries',        [RoutingController::class, 'routeGeneralCountries']);
    Route::post('route-general-customers',        [RoutingController::class, 'routeGeneralCustomers']);
    Route::post('route-general-mccmnc',           [RoutingController::class, 'routeGeneralMccMnc']);
    Route::post('route-general-mccmnc-customers', [RoutingController::class, 'routeGeneralMccMncCustomers']);

    Route::post('route-sender-countries',            [RoutingController::class, 'routeSenderCountries']);
    Route::post('route-sender-customers',            [RoutingController::class, 'routeSenderCustomers']);
    Route::post('route-sender-restricted-operators', [RoutingController::class, 'routeSenderRestrictedOperators']);

    Route::post('route-blacklist-countries',         [RoutingController::class, 'routeBlacklistCountries']);
    Route::post('route-blacklist-customers',         [RoutingController::class, 'routeBlacklistCustomers']);

    Route::post('route-backup-countries',            [RoutingController::class, 'routeBackupCountries']);
    Route::post('route-backup-customers',            [RoutingController::class, 'routeBackupCustomers']);
    Route::post('route-backup-mccmnc',               [RoutingController::class, 'routeBackupMccMnc']);
    Route::post('route-backup-routes',               [RoutingController::class, 'routeBackupRoutes']);

    Route::post('pricing-countries',    [PricingController::class, 'pricingCountries']);
    Route::post('pricing-providers',    [PricingController::class, 'pricingProviders']);
    Route::post('pricing-customers',    [PricingController::class, 'pricingCustomers']);
    Route::post('pricing-defaults',     [PricingController::class, 'pricingDefaults']);

    Route::post('billing-defaults',             [BillingController::class, 'billingDefaults']);
    Route::post('billing-defaults-item',        [BillingController::class, 'billingDefaultsItem']);

    Route::post('billing-customer-date',    [BillingController::class, 'billingCustomerDate']);
    Route::post('billing-customer-mccmnc',  [BillingController::class, 'billingCustomerMccMnc']);
    Route::post('billing-provider-date',    [BillingController::class, 'billingProviderDate']);
    Route::post('billing-provider-mccmnc',  [BillingController::class, 'billingProviderMccMnc']);
    Route::post('billing-details',          [BillingController::class, 'billingDetails']);

    Route::post('clearing-mccmnc',          [ClearingController::class, 'clearingMccMnc']);
    Route::post('clearing-date',            [ClearingController::class, 'clearingDate']);
    Route::post('clearing-provider-mccmnc', [ClearingController::class, 'clearingProviderMccMnc']);
    Route::post('clearing-provider-date',   [ClearingController::class, 'clearingProviderDate']);

    Route::post('quality-map',       [QualityController::class, 'qualityMap']);
    Route::post('quality-country',   [QualityController::class, 'qualityCountry']);
    Route::post('quality-mccmnc',    [QualityController::class, 'qualityMccMnc']);

    Route::post('config-account',                       [ConfigurationController::class, 'configAccount']);
    Route::post('config-account-user-update',           [ConfigurationController::class, 'configAccountUserUpdate']);
    Route::post('config-account-user-password-reset',   [ConfigurationController::class, 'configAccountUserPasswordReset']);
    Route::post('config-account-user-block',            [ConfigurationController::class, 'configAccountUserBlock']);
    Route::post('config-account-mine-update',           [ConfigurationController::class, 'configAccountMineUpdate']);
    Route::post('config-account-add-new-user',          [ConfigurationController::class, 'configAccountAddNewUser']);

    Route::post('config-customers',          [ConfigurationController::class, 'configCustomers']);
    Route::post('config-customer-update',    [ConfigurationController::class, 'configCustomerUpdate']);
    Route::post('config-customer-add-new',   [ConfigurationController::class, 'configCustomerAddNew']);
    
});