<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UniteController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WorkTypeController;
use App\Http\Controllers\FinishTypeController;
use App\Http\Controllers\UtilController;
use App\Http\Controllers\ImportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::GET('/', [LoginController::class, 'index'])
->name('pageLogin');

Route::GET('/page_login_admin', [LoginController::class, 'pageLoginAdmin'])
->name('page_login_admin');

Route::POST('/loginClient', [LoginController::class, 'authenticateClient'])
->name('loginClient');

Route::POST('/loginAdmin', [LoginController::class, 'authenticateAdmin'])
->name('loginAdmin');

Route::GET('/deconnect', [LoginController::class, 'deconnect'])
->name('deconnect');

Route::GET('/unite', [UniteController::class, 'listUnite'])
->name('unite');

Route::POST('/save_unite', [UniteController::class, 'save'])
->name('save_unite');

Route::GET('/delete_unite', [UniteController::class, 'delete'])
->name('delete_unite');

Route::GET('/tableau_bord', [StatistiqueController::class, 'tableauBord'])
->name('tableau_bord');

Route::GET('/histogramme', [StatistiqueController::class, 'histogramme'])
->name('histogramme');

Route::GET('/estimate_list', [EstimateController::class, 'list'])
->name('estimate_list');

Route::GET('/new_estimate', [EstimateController::class, 'newEstimate'])
->name('new_estimate');

Route::POST('/save_house_type', [EstimateController::class, 'saveHouseType'])
->name('save_house_type');

Route::POST('/save_estimate', [EstimateController::class, 'saveEstimate'])
->name('save_estimate');

Route::GET('/new_estimate2', [EstimateController::class, 'newEstimate2'])
->name('new_estimate2');

Route::GET('/export_estimate_pdf', [EstimateController::class, 'exportEstimatePdf'])
->name('export_estimate_pdf');

Route::GET('/payment_page', [PaymentController::class, 'paymentPage'])
->name('payment_page');

Route::POST('/save_payment', [PaymentController::class, 'savePayment'])
->name('save_payment');

Route::GET('/estimate_in_progress', [EstimateController::class, 'estimateInProgress'])
->name('estimate_in_progress');

Route::GET('/details_estimate', [EstimateController::class, 'detailsEstimate'])
->name('details_estimate');

Route::POST('/filter_by_month', [StatistiqueController::class, 'filterByMonthInYear'])
->name('filter_by_month');

Route::POST('/filter_by_year', [StatistiqueController::class, 'filterByYear'])
->name('filter_by_year');

Route::GET('/work_type', [WorkTypeController::class, 'listWorkType'])
->name('work_type');

Route::GET('/modif_work_type', [WorkTypeController::class, 'modifWorkType'])
->name('modif_work_type');

Route::GET('/new_work_type', [WorkTypeController::class, 'newWorkType'])
->name('new_work_type');

Route::POST('/update_work_type', [WorkTypeController::class, 'updateWorkType'])
->name('update_work_type');

Route::POST('/save_work_type', [WorkTypeController::class, 'saveWorkType'])
->name('save_work_type');

Route::POST('/search_work_type', [WorkTypeController::class, 'searchWorkType'])
->name('search_work_type');

Route::GET('/delete_work_type', [WorkTypeController::class, 'deleteWorkType'])
->name('delete_work_type');

Route::GET('/deleted_work_type', [WorkTypeController::class, 'workTypeDeleted'])
->name('deleted_work_type');

Route::GET('/restore_work_type', [WorkTypeController::class, 'restoreWorkType'])
->name('restore_work_type');

Route::GET('/force_deleted_work_type', [WorkTypeController::class, 'forceDeletedWorkType'])
->name('force_deleted_work_type');

Route::GET('/finish_type', [FinishTypeController::class, 'listFinishType'])
->name('finish_type');

Route::POST('/save_finish_type', [FinishTypeController::class, 'save'])
->name('save_finish_type');

Route::GET('/delete_finish_type', [FinishTypeController::class, 'delete'])
->name('delete_finish_type');

Route::GET('/edit_finish_type', [FinishTypeController::class, 'edit'])
->name('edit_finish_type');

Route::POST('/update_finish_type', [FinishTypeController::class, 'update'])
->name('update_finish_type');

Route::GET('/reinitialize', [UtilController::class, 'reinitialize'])
->name('reinitialize');

Route::GET('/import_page', [ImportController::class, 'importPage'])
->name('import_page');

Route::POST('/import_house_work_estimate', [ImportController::class, 'importHouseWorkEstimate'])
->name('import_house_work_estimate');

Route::POST('/import_payment', [ImportController::class, 'importPayment'])
->name('import_payment');

Route::GET('/work_progress', [EstimateController::class, 'insertWorkProgressPage'])
->name('work_progress');

Route::POST('/save_work_progress', [EstimateController::class, 'saveWorkProgress'])
->name('save_work_progress');