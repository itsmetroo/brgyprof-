<?php

use Illuminate\Support\Facades\Route;

//Admin Panel
//Account Login Admin
use App\Http\Controllers\AdminPanel\Setting\Account\UserController;
//Dashboard Module
use App\Http\Controllers\AdminPanel\DashboardController;
//Resident Module
use App\Http\Controllers\AdminPanel\ResidentInfoController;
//Blotter Module
use App\Http\Controllers\AdminPanel\BlotterController;
//Setttlement Module
use App\Http\Controllers\AdminPanel\Settlement\PersonInvolveController;
use App\Http\Controllers\AdminPanel\Settlement\ScheduleController;
use App\Http\Controllers\AdminPanel\Settlement\UnscheduleController;
use App\Http\Controllers\AdminPanel\Settlement\ScheduleTodayController;
use App\Http\Controllers\AdminPanel\Settlement\SettledCasesController;
use App\Http\Controllers\AdminPanel\CertificateController;
use App\Http\Controllers\AdminPanel\PrintController;
use Barryvdh\DomPDF\Facade as PDF;
//Setting Module:Account Section
use App\Http\Controllers\AdminPanel\Setting\Account\AccountController;

//Setting Module:Barangay Section
use App\Http\Controllers\AdminPanel\Setting\Barangay\BrgyOfficialController;
use App\Http\Controllers\AdminPanel\Setting\Barangay\BarangayimageController;
//Admin Panel End

//Client Side

//Account Login
use App\Http\Controllers\ClientSide\ResidentAccountController;
//Home Module
use App\Http\Controllers\ClientSide\ClearanceController;
//Schedule
use App\Http\Controllers\ClientSide\ScheduleClientController;
use App\Http\Controllers\ClientSide\HomeController;
//AccountSettings
use App\Http\Controllers\ClientSide\ResidentUserAccountController;

// Blotter
use App\Http\Controllers\ClientSide\BlotterController as ClientBlotterController;

//Client Side End
//Testing Area
use App\Http\Controllers\BooksController;
use App\Http\Controllers\PagesController;

//Redirect
Route::get('/', function () {
    return redirect('/barangay/home');
});

//Admin Panel Start

//Dashboard Module
Route::get('/dashboard', [DashboardController::class, 'dashboard']);

//Resident Module
Route::resource('resident', ResidentInfoController::class);
Route::get('resident/person/{resident_id}', [ResidentInfoController::class, 'person']);
Route::get('resident/person/{resident_id}/blotter/', [ResidentInfoController::class, 'blotter']);



// Blotter Module
Route::get('/blotter', [BlotterController::class, 'show']);
Route::resource('blotters', BlotterController::class);
Route::resource('personinvolves', PersonInvolveController::class);

//Settlement Module
Route::get('/schedule', [ScheduleController::class, 'index']);
Route::resource('schedules', ScheduleController::class);
Route::resource('unschedules', UnscheduleController::class);
Route::resource('scheduletoday', ScheduleTodayController::class);
Route::resource('settled', SettledCasesController::class);

//Certificate Module
// Remove the duplicate resource route since we're defining specific routes below
// Route::resource('certificate', CertificateController::class);

// Certificate Management Routes
Route::prefix('certificate')->middleware(['web'])->group(function () {
    Route::get('/', [App\Http\Controllers\AdminPanel\CertificateController::class, 'index'])->name('certificate.index');
    Route::post('/store', [App\Http\Controllers\AdminPanel\CertificateController::class, 'store'])->name('certificate.store');
    Route::post('/store-request', [App\Http\Controllers\AdminPanel\CertificateController::class, 'storerequest'])->name('storerequest.post');
    Route::post('/update-status', [App\Http\Controllers\AdminPanel\CertificateController::class, 'updateRequestStatus'])->name('certificate.updatestatus');
    Route::delete('/delete/{id}', [App\Http\Controllers\AdminPanel\CertificateController::class, 'deleterequest'])->name('certificate.deleterequest');
    Route::get('/paid', [App\Http\Controllers\AdminPanel\CertificateController::class, 'certrequestpaid'])->name('certrequestpaid.index');
    Route::get('/unpaid', [App\Http\Controllers\AdminPanel\CertificateController::class, 'certrequestunpaid'])->name('certrequestunpaid.index');
    Route::get('/view/{id}', [App\Http\Controllers\AdminPanel\CertificateController::class, 'show'])->name('certificate.show');
    Route::get('/print/{id}', [App\Http\Controllers\AdminPanel\PrintController::class, 'printCertificate'])->name('certificate.print');
    
    // Certificate type management
    Route::get('/type', [App\Http\Controllers\AdminPanel\CertificateController::class, 'certificate_type'])->name('certificate_type.index');
    Route::post('/type', [App\Http\Controllers\AdminPanel\CertificateController::class, 'certtypesubmit'])->name('certtypesubmit.post');
    Route::get('/type/{cert_id}/edit', [App\Http\Controllers\AdminPanel\CertificateController::class, 'certtypeedit'])->name('certtypesubmit.edit');
    Route::delete('/type/{cert_id}', [App\Http\Controllers\AdminPanel\CertificateController::class, 'certtypedelete'])->name('certtypedelete.delete');
});

//Maintenance Moduule
// Barangay Setting Section
Route::resource('setting/maintenance', BrgyOfficialController::class);
Route::get('setting/maintenance/official/table', [BrgyOfficialController::class, 'barangay'])->name('barangay.index');
Route::post('setting/maintenance/official/table', [BrgyOfficialController::class, 'barangayPOST'])->name('barangay.post');
Route::get('setting/maintenance/official/table/{barangay}/edit', [BrgyOfficialController::class, 'barangayedit'])->name('barangay.edit');
Route::delete('setting/maintenance/official/table/{barangay}/', [BrgyOfficialController::class, 'barangaydelete'])->name('barangay.destroy');
Route::post('setting/maintenance/barangay/image', [BarangayimageController::class, 'store'])->name('image.store');

// Account Setting Section
Route::resource('/setting/account', AccountController::class);
Route::post("/setting/account/form",[AccountController::class, 'accountSettingCheck'])->name("accountSettingCheck");

    // Session
    Route::get("/setting/account/session/table", [AccountController::class, 'getSessionTable'])->name("getSessionTable");

    // Resident Manage Account
    Route::get("/setting/account/resident_account/table", [AccountController::class, 'getResidentAccountTable'])->name("ResidentAccountTable");
    Route::patch("/setting/account/resident_account/{id}", [AccountController::class, 'resident_update']);
    Route::delete("/setting/account/resident_account/{id}", [AccountController::class, 'resident_delete']);
   


    // User Login Section
    Route::get("/login", [UserController::class, 'login']);
    Route::get("/profile", [UserController::class, 'profile']);
    Route::post("/login", [UserController::class, 'check']);
    Route::get("/logout", [UserController::class, 'logout']);

//Admin Panel End

// Client Side Start

//Certificate page
Route::get("/barangay/certificate/", [ClearanceController::class, 'index'])->name("certificateclient.index");
Route::post("/barangay/certificate/", [ClearanceController::class, 'store'])->name("certificateclient.store");

//Schedule page
Route::get('/barangay/schedule/', [ScheduleClientController::class, 'index'])->name("scheduleclientindex.get");
Route::get('/barangay/schedule/{schedule}', [ScheduleClientController::class, 'show'])->name("scheduleclientshow.get");
Route::get('/barangay/schedule/print/{schedule_id}', [ScheduleClientController::class, 'printclient'])->name("scheduleclientprint.get");

//Miscellaneous
Route::get('barangay/home', [HomeController::class, 'resident_home']);
Route::get('/barangay/news', [HomeController::class, 'resident_news']);
Route::get('/barangay/info', [HomeController::class, 'resident_info']);

//Account Setting
Route::get('/barangay/accountsetting', [ResidentAccountController::class, 'index']);
Route::get("/barangay/{resident_id}/edit", [ResidentAccountController::class, 'edit']);
Route::post("/barangay/accountsetting/check",[ResidentAccountController::class, 'clientaccountsettingcheck'])->name("client_accountsetting_check");

//Blotter
Route::get("/barangay/blotter/{residentid}", [ClientBlotterController::class, 'index']);
Route::get("/barangay/blotter/pdf/{resident_id}/{blotterid}", [ClientBlotterController::class, 'pdf']);
Route::resource("/barangay/blotter", ClientBlotterController::class);

// Client Login
Route::get("/barangay/login", [ResidentUserAccountController::class, 'client_login']);
Route::post("/barangay/login", [ResidentUserAccountController::class, 'client_check']);
Route::get("/barangay/register", [ResidentUserAccountController::class, 'client_register']);
Route::post("/barangay/register", [ResidentUserAccountController::class, 'client_register_check']);
Route::get("/barangay/logout", [ResidentUserAccountController::class, 'client_logout']);
Route::get("/barangay/forgot_password", [ResidentUserAccountController::class, 'client_forgot_password']);

//barangay Side End

// Testing Area
Route::resource('books', BooksController::class);
Route::get('/invoice', function () {
    return view('Testing.invoice');
    $pdf = PDF::loadView('Testing.invoice')->setOptions(['defaultFont' => 'sans-serif']);;
    return $pdf->download('invoice.pdf');
});
Route::get('/invoice-pdf', function () {
    $pdf = PDF::loadView('Testing.invoice-pdf')->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true, 'format' => 'letter']);;
    return $pdf->download('invoice.pdf');
});

Route::get('/certificates', function () {
    return view('Testing.certificate');
});
Route::get('/certificate-pdf', function () {
    $pdf = PDF::loadView('Testing.certificate-pdf')->setPaper('A4','portrait')->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled' => true]) ;
    return $pdf->download('certificate.pdf');
});
Route::resource('books', BooksController::class);
Route::get('sampledata', [PagesController::class, 'sampledata']);

// Testing Area End
