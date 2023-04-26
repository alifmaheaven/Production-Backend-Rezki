<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserActiveController;
use App\Http\Controllers\UserBankController;
use App\Http\Controllers\UserWishController;
use App\Http\Controllers\WishesController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CampaignBannerController;
use App\Http\Controllers\CampaignPeriodController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CampaignReportController;
use App\Http\Controllers\CampaignReportDetailController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
|-------------------------------------------------------------------------------------------|
| Note                                                                                      |
|-------------------------------------------------------------------------------------------|
| 1. when you want allow all user roles acces api you can use "auth" only                   |
| 2. when you want allow specific user roles acces api you can use "auth:1,2"               |
| 3. you want spesific api like post only can acces by investor, you can try like this      |
| "$router->middleware('auth:3')->get('', [UserBankController::class, 'index']);"           |
| 4. 1 = for investor, 2 = UMKM, 3 = Reviewer                                               |
|-------------------------------------------------------------------------------------------|
*/


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth');
    Route::post('refresh', 'refresh')->middleware('auth');
});

/*
|---------------------------------------------------------------------------|
| API Each Table Routes                                                     |
|---------------------------------------------------------------------------|
| http://127.0.0.1:8000/api/upload                          |POST|          |
| http://127.0.0.1:8000/api/user-banks                  |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/user-actives                |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/users                       |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/wishes                      |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/user-wishes                 |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/news                        |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/campaign-banners            |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/campaign-periods            |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/campaigns                   |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/payments                    |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/campaign-reports            |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/campaign-report-details     |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/withdraws                   |GET|POST|PUT|DELETE|
| http://127.0.0.1:8000/api/transactions                |GET|POST|PUT|DELETE|
|---------------------------------------------------------------------------|
| Note :                                                                    |
|---------------------------------------------------------------------------|
| 1. want add realation to another table add "include[]" parameter          |
|    example : http://127.0.0.1:8000/api/users?include[]=user_bank&include[]=user_active
| 2. want add filter to another table add name of your filter parameter     |
|    example : http://127.0.0.1:8000/api/users?name=John
|---------------------------------------------------------------------------|
*/

// user_banks
Route::group(['prefix' => 'upload'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->post('', [FileController::class, 'store']);
    });
});

// user_banks
Route::group(['prefix' => 'user'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [UserController::class, 'index']);
        $router->get('/{id}', [UserController::class, 'show']);
        $router->post('', [UserController::class, 'store']);
        $router->put('{id}', [UserController::class, 'update']);
        $router->delete('{id}', [UserController::class, 'destroy']);
    });
});

// user_banks
Route::group(['prefix' => 'user-banks'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [UserBankController::class, 'index']);
        $router->get('/{id}', [UserBankController::class, 'show']);
        $router->post('', [UserBankController::class, 'store']);
        $router->put('{id}', [UserBankController::class, 'update']);
        $router->delete('{id}', [UserBankController::class, 'destroy']);
    });
});

// user_actives
Route::group(['prefix' => 'user-actives'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [UserActiveController::class, 'index']);
        $router->get('/{id}', [UserActiveController::class, 'show']);
        $router->post('', [UserActiveController::class, 'store']);
        $router->put('{id}', [UserActiveController::class, 'update']);
        $router->delete('{id}', [UserActiveController::class, 'destroy']);
    });
});

// wishes
Route::group(['prefix' => 'wishes'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [WishesController::class, 'index']);
        $router->get('/{id}', [WishesController::class, 'show']);
        $router->post('', [WishesController::class, 'store']);
        $router->put('{id}', [WishesController::class, 'update']);
        $router->delete('{id}', [WishesController::class, 'destroy']);
    });
});

// user_wishes
Route::group(['prefix' => 'user-wishes'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [UserWishController::class, 'index']);
        $router->get('/{id}', [UserWishController::class, 'show']);
        $router->post('', [UserWishController::class, 'store']);
        $router->put('{id}', [UserWishController::class, 'update']);
        $router->delete('{id}', [UserWishController::class, 'destroy']);
    });
});

// news
Route::group(['prefix' => 'news'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [NewsController::class, 'index']);
        $router->get('/{id}', [NewsController::class, 'show']);
        $router->post('', [NewsController::class, 'store']);
        $router->put('{id}', [NewsController::class, 'update']);
        $router->delete('{id}', [NewsController::class, 'destroy']);
    });
});

// campaign_banners
Route::group(['prefix' => 'campaign-banners'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [CampaignBannerController::class, 'index']);
        $router->get('/{id}', [CampaignBannerController::class, 'show']);
        $router->post('', [CampaignBannerController::class, 'store']);
        $router->put('{id}', [CampaignBannerController::class, 'update']);
        $router->delete('{id}', [CampaignBannerController::class, 'destroy']);
    });
});

// campaign_periods
Route::group(['prefix' => 'campaign-periods'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [CampaignPeriodController::class, 'index']);
        $router->get('/{id}', [CampaignPeriodController::class, 'show']);
        $router->post('', [CampaignPeriodController::class, 'store']);
        $router->put('{id}', [CampaignPeriodController::class, 'update']);
        $router->delete('{id}', [CampaignPeriodController::class, 'destroy']);
    });
});

// campaigns
Route::group(['prefix' => 'campaigns'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [CampaignController::class, 'index']);
        $router->get('/{id}', [CampaignController::class, 'show']);
        $router->post('', [CampaignController::class, 'store']);
        $router->put('{id}', [CampaignController::class, 'update']);
        $router->delete('{id}', [CampaignController::class, 'destroy']);
    });
});

// payments
Route::group(['prefix' => 'payments'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [PaymentController::class, 'index']);
        $router->get('/{id}', [PaymentController::class, 'show']);
        $router->post('', [PaymentController::class, 'store']);
        $router->put('{id}', [PaymentController::class, 'update']);
        $router->delete('{id}', [PaymentController::class, 'destroy']);
    });
});

// campaign_reports
Route::group(['prefix' => 'campaign-reports'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [CampaignReportController::class, 'index']);
        $router->get('/{id}', [CampaignReportController::class, 'show']);
        $router->post('', [CampaignReportController::class, 'store']);
        $router->put('{id}', [CampaignReportController::class, 'update']);
        $router->delete('{id}', [CampaignReportController::class, 'destroy']);
    });
});

// campaign_report_details
Route::group(['prefix' => 'campaign-report-details'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [CampaignReportDetailController::class, 'index']);
        $router->get('/{id}', [CampaignReportDetailController::class, 'show']);
        $router->post('', [CampaignReportDetailController::class, 'store']);
        $router->put('{id}', [CampaignReportDetailController::class, 'update']);
        $router->delete('{id}', [CampaignReportDetailController::class, 'destroy']);
    });
});

// withdraws
Route::group(['prefix' => 'withdraws'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [WithdrawController::class, 'index']);
        $router->get('/{id}', [WithdrawController::class, 'show']);
        $router->post('', [WithdrawController::class, 'store']);
        $router->put('{id}', [WithdrawController::class, 'update']);
        $router->delete('{id}', [WithdrawController::class, 'destroy']);
    });
});

// transactions
Route::group(['prefix' => 'transactions'], function ($router) {
    Route::group(['middleware' => 'auth:1,2,3'], function ($router) {
        $router->get('', [TransactionController::class, 'index']);
        $router->get('/{id}', [TransactionController::class, 'show']);
        $router->post('', [TransactionController::class, 'store']);
        $router->put('{id}', [TransactionController::class, 'update']);
        $router->delete('{id}', [TransactionController::class, 'destroy']);
    });
});
