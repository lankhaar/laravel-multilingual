<?php

use Illuminate\Support\Facades\Route;
use Lankhaar\Multilingual\Enum\LocaleIdentifierType;
use Lankhaar\Multilingual\Http\Controllers;
use Lankhaar\Multilingual\Service\ConfigService;

Route::group(['middleware' => ['web']], function () {
    Route::get('/multilingual/switch/{locale}', Controllers\MultilingualController::class . '@changeLanguage')->name('switch-locale');
});
