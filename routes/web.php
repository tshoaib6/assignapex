<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamDetailController;
use App\Http\Controllers\RegionCityController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\PostProcessorChecklistController;
use App\Http\Controllers\ReviewerRejectionController;
use App\Http\Controllers\PostProcessorRejectionController;
use App\Http\Controllers\CSTFormController;
use App\Http\Controllers\RouteMpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserRouteController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\PixelController;
use App\Http\Controllers\ImportController;


Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
});

Route::get('/create-storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created successfully.';
})->middleware('auth');
Route::view('/legal/apexassign', 'privacypolicy');
Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Users

Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
 Route::get('users/{userId}/delete', [UserController::class, 'destroy']);

// Roles

  Route::resource('roles', RoleController::class)->only(['index','create','store','edit', 'update']);
  Route::get('roles/{roleId}/delete', [RoleController::class, 'destroy']);

// team
Route::resource('team', TeamDetailController::class)->only(['index','create','store','edit', 'update']);
Route::get('team/{id}/delete', [TeamDetailController::class, 'destroy'])->name('team.delete');

Route::resource('scenarios', ScenarioController::class);
Route::resource('checklists', ChecklistController::class);

Route::resource('post-processor-checklists', PostProcessorChecklistController::class);

Route::resource('reviewer_rejections', ReviewerRejectionController::class);

Route::resource('post_processor_rejections', PostProcessorRejectionController::class);


// RegionCity
Route::resource('region', RegionCityController::class);

// cst


Route::get('/cstform', [CSTFormController::class, 'index'])->name('cstform.index');
Route::get('/cstform-create', [CSTFormController::class, 'create'])->name('cstform.create');
Route::post('/cstform-submit', [CSTFormController::class, 'store'])->name('cstform.store');
Route::get('/get-checkpoint-options', [CstFormController::class, 'getCheckpointOptions'])->name('get.checkpoint.options');
Route::post('/store-route', [CstFormController::class, 'getCheckpointOptions'])->name('route_mp.store');
Route::get('/add-route', [RouteMpController::class, 'create'])->name('add.route.form');
Route::delete('/cstrequest/{id}', [CstFormController::class, 'destroyrequest'])->name('cstrequest.destroy');
Route::get('/regions/cities', [CSTFormController::class, 'getCitiesByRegion'])->name('regions.cities');
Route::get('/get-pixel-details', [CSTFormController::class, 'getPixelDetails'])->name('get.pixel.details');

// PM: edit / update / cancel approved requests
Route::get('/cst-request/{id}/edit',   [CSTFormController::class, 'editRequest'])->name('cstform.request.edit');
Route::put('/cst-request/{id}/update', [CSTFormController::class, 'updateRequest'])->name('cstform.request.update');
Route::post('/cst-request/{id}/cancel',[CSTFormController::class, 'cancelRequest'])->name('cstform.request.cancel');

// Apex history
Route::get('/apex-history', [CSTFormController::class, 'apexHistory'])->name('apex.history');

// approve / reject
Route::post('/cst-request/{id}/approve', [CstFormController::class, 'approve'])->name('cst-request.approve');
Route::get('/cst-view/{id}/', [CstFormController::class, 'viewreport'])->name('cstform.view');

Route::post('/cst-request/{id}/reject', [CstFormController::class, 'reject'])->name('cst-request.reject');
// assign tester
Route::get('/cst/flow/{id}', [CstFormController::class, 'assignTester'])->name('assign.tester');
// Route::post('/request/tester', [CstFormController::class, 'requestTester'])->name('request.tester');
Route::post('/request/tester', [CstFormController::class, 'requestTester'])->name('request.tester');


//checklist
Route::get('/tester/checklist/{id}', [CstFormController::class, 'testerchecklist'])->name('tester.checklists');
Route::post('/store/checklist', [CstFormController::class, 'storechecklist'])->name('store.selectedchecklists');
Route::get('field/test/{id}', [CstFormController::class, 'fieldtest'])->name('field.test');
Route::post('store.fieldtest', [CstFormController::class, 'fieldteststore'])->name('fieldtest.store');
Route::get('/test/logfile/{id}', [CstFormController::class, 'logfile'])->name('logfile.test');
Route::post('store.logfile', [CstFormController::class, 'storetestlog'])->name('logfile.test.store');
Route::post('/cst-request/{id}/send-back', [CstFormController::class, 'revertToStep4'])->name('cst.revertToStep4');

Route::get('/post-processor/checklist/{id}', [CstFormController::class, 'postprocessorchecklist'])->name('post.checklist');
Route::post('/store/post-checklist', [CstFormController::class, 'storepostchecklist'])->name('postprocessor.storechecklist');

Route::post('/postprocessor/store', [CstFormController::class, 'storepostprocessor'])->name('postprocessor.store');
Route::post('/postprocessor/storereport', [CstFormController::class, 'storepostprocessorreport'])->name('postprocessor.storereport');
Route::post('/submit-ninth-form', [CstFormController::class, 'submitNinthForm'])->name('submit.ninth.form');
Route::post('/submit-tenth-form', [CstFormController::class, 'submitTenthForm'])->name('submit.tenth.form');
Route::post('/team-leader-evaluation-submit', [CstFormController::class, 'submitEvaluation'])->name('team.leader.evaluation.submit');
Route::post('/cst/final-acceptance', [CstFormController::class, 'submitfinalForm'])->name('cst.final.acceptance.submit');

// todo
Route::get('/todo-list', [CstFormController::class, 'todolist'])->name('my.todo');

// redo list
Route::get('/redo-list', [CstFormController::class, 'redolist'])->name('cst.redo');
// pp validation redo
Route::get('/redo-datavalidation/{id}', [CstFormController::class, 'ppvalidationredo'])->name('redo.ppvalidation');
// pp checklist redo
Route::get('/redo-checklistvalidation/{id}', [CstFormController::class, 'ppchecklistredo'])->name('pp.checklist.redo');
// pp report redo
Route::get('/redo-ppreport/{id}', [CstFormController::class, 'ppreportredo'])->name('pp.report.redo');
// team leader evaluation redo
Route::get('/redo-evaluation/{id}', [CstFormController::class, 'ppevaluationredo'])->name('evaluation.teamleader.redo');
// final acceptance redo
Route::get('/redo-finalacceptance/{id}', [CstFormController::class, 'redoacceptance'])->name('final.acceptance.redo');


Route::resource('pixels', PixelController::class);

// pricing

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
Route::put('/pricing/{id}', [PricingController::class, 'update'])->name('pricing.update');





Route::get('/emailconfig', [UserController::class, 'emailconfig'])->name('email.config');
Route::post('/update-emailconfig', [UserController::class, 'updateemailconfig'])->name('store.emailconfig');

// Import
Route::get('/import', [ImportController::class, 'index'])->name('import.index');
Route::post('/import/pixels', [ImportController::class, 'importPixels'])->name('import.pixels');
Route::post('/import/regions', [ImportController::class, 'importRegions'])->name('import.regions');


//notifaction



Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/delete/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');
});


require __DIR__.'/auth.php';
