<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/parse-csv-import', 'UsersController@parseCsvImport')->name('users.parseCsvImport');
    Route::post('users/process-csv-import', 'UsersController@processCsvImport')->name('users.processCsvImport');
    Route::resource('users', 'UsersController');

    // Tiket
    Route::delete('tikets/destroy', 'TiketController@massDestroy')->name('tikets.massDestroy');
    Route::post('tikets/media', 'TiketController@storeMedia')->name('tikets.storeMedia');
    Route::post('tikets/ckmedia', 'TiketController@storeCKEditorImages')->name('tikets.storeCKEditorImages');
    Route::post('tikets/parse-csv-import', 'TiketController@parseCsvImport')->name('tikets.parseCsvImport');
    Route::post('tikets/process-csv-import', 'TiketController@processCsvImport')->name('tikets.processCsvImport');
    Route::resource('tikets', 'TiketController');

    // Event
    Route::delete('events/destroy', 'EventController@massDestroy')->name('events.massDestroy');
    Route::post('events/media', 'EventController@storeMedia')->name('events.storeMedia');
    Route::post('events/ckmedia', 'EventController@storeCKEditorImages')->name('events.storeCKEditorImages');
    Route::post('events/parse-csv-import', 'EventController@parseCsvImport')->name('events.parseCsvImport');
    Route::post('events/process-csv-import', 'EventController@processCsvImport')->name('events.processCsvImport');
    Route::resource('events', 'EventController');

    // Banner
    Route::delete('banners/destroy', 'BannerController@massDestroy')->name('banners.massDestroy');
    Route::post('banners/media', 'BannerController@storeMedia')->name('banners.storeMedia');
    Route::post('banners/ckmedia', 'BannerController@storeCKEditorImages')->name('banners.storeCKEditorImages');
    Route::resource('banners', 'BannerController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Faq Category
    Route::delete('faq-categories/destroy', 'FaqCategoryController@massDestroy')->name('faq-categories.massDestroy');
    Route::resource('faq-categories', 'FaqCategoryController');

    // Faq Question
    Route::delete('faq-questions/destroy', 'FaqQuestionController@massDestroy')->name('faq-questions.massDestroy');
    Route::resource('faq-questions', 'FaqQuestionController');

    // Transaksi
    Route::delete('transaksis/destroy', 'TransaksiController@massDestroy')->name('transaksis.massDestroy');
    Route::post('transaksis/media', 'TransaksiController@storeMedia')->name('transaksis.storeMedia');
    Route::post('transaksis/withdraw', 'TransaksiController@withdraw')->name('transaksis.withdraw');
    Route::post('transaksis/ckmedia', 'TransaksiController@storeCKEditorImages')->name('transaksis.storeCKEditorImages');
    Route::post('transaksis/parse-csv-import', 'TransaksiController@parseCsvImport')->name('transaksis.parseCsvImport');
    Route::post('transaksis/process-csv-import', 'TransaksiController@processCsvImport')->name('transaksis.processCsvImport');
    Route::resource('transaksis', 'TransaksiController');

    // Sponsor
    Route::delete('sponsors/destroy', 'SponsorController@massDestroy')->name('sponsors.massDestroy');
    Route::post('sponsors/media', 'SponsorController@storeMedia')->name('sponsors.storeMedia');
    Route::post('sponsors/ckmedia', 'SponsorController@storeCKEditorImages')->name('sponsors.storeCKEditorImages');
    Route::resource('sponsors', 'SponsorController');

    // Setting
    Route::delete('settings/destroy', 'SettingController@massDestroy')->name('settings.massDestroy');
    Route::resource('settings', 'SettingController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
