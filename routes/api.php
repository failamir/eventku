<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin'], function () {
    // Pendaftar
    Route::post('beli', 'ApiController@beliApi')->name('beliApi');
    Route::post('daftar', 'ApiController@daftar')->name('daftar');
    Route::get('profile', 'ApiController@profile')->name('profile');
    Route::post('updateprofile', 'ApiController@updateprofile')->name('updateprofile');
    Route::get('transaksi', 'ApiController@transaksi')->name('transaksi');
    Route::get('tiket', 'ApiController@tiket')->name('tiket');
    Route::get('list_tiket', 'ApiController@list_tiket')->name('list_tiket');
    Route::post('notification', 'ApiController@notificationHandler')->name('notification');
    Route::post('scan', 'ApiController@scanqr')->name('scanqr');
    Route::post('checkin', 'ApiController@checkin')->name('checkin');
    Route::post('status_tiket', 'ApiController@status_tiket')->name('status_tiket');
    Route::post('checkin2', 'ApiController@checkin2')->name('checkin2');
    Route::post('checkout', 'ApiController@checkout')->name('checkout');
    Route::post('pendaftars/media', 'PendaftarApiController@storeMedia')->name('pendaftars.storeMedia');
    // Route::apiResource('pendaftars', 'PendaftarApiController');
    Route::get('pendaftars', 'ApiController@pendaftar')->name('pendaftars.index');
    Route::get('list_checkin', 'ApiController@list_checkin');
    Route::get('list_checkout', 'ApiController@list_checkout');
    // Tiket
    Route::post('tikets/media', 'TiketApiController@storeMedia')->name('tikets.storeMedia');
    Route::apiResource('tikets', 'TiketApiController');

    // Event
    Route::post('events/media', 'EventApiController@storeMedia')->name('events.storeMedia');
    Route::apiResource('events', 'EventApiController');

    // Banner
    Route::post('banners/media', 'BannerApiController@storeMedia')->name('banners.storeMedia');
    Route::apiResource('banners', 'BannerApiController');

    // Transaksi
    Route::post('transaksis/media', 'TransaksiApiController@storeMedia')->name('transaksis.storeMedia');
    Route::apiResource('transaksis', 'TransaksiApiController');

    // Sponsor
    Route::post('sponsors/media', 'SponsorApiController@storeMedia')->name('sponsors.storeMedia');
    Route::apiResource('sponsors', 'SponsorApiController');

    // Setting
    Route::apiResource('settings', 'SettingApiController');

    // Event
    Route::apiResource('events', 'EventApiController');

});

// Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
//     // Users
//     Route::apiResource('users', 'UsersApiController');

//     // Tiket
//     Route::post('tikets/media', 'TiketApiController@storeMedia')->name('tikets.storeMedia');
//     Route::apiResource('tikets', 'TiketApiController');

//     // Event
//     Route::post('events/media', 'EventApiController@storeMedia')->name('events.storeMedia');
//     Route::apiResource('events', 'EventApiController');

//     // Banner
//     Route::post('banners/media', 'BannerApiController@storeMedia')->name('banners.storeMedia');
//     Route::apiResource('banners', 'BannerApiController');

//     // Transaksi
//     Route::post('transaksis/media', 'TransaksiApiController@storeMedia')->name('transaksis.storeMedia');
//     Route::apiResource('transaksis', 'TransaksiApiController');

//     // Sponsor
//     Route::post('sponsors/media', 'SponsorApiController@storeMedia')->name('sponsors.storeMedia');
//     Route::apiResource('sponsors', 'SponsorApiController');

//     // Setting
//     Route::apiResource('settings', 'SettingApiController');
// });
