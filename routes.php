<?php
use Illuminate\Support\Facades\Route;

Route::
        namespace('JosephCrowell\MagicForms\Classes\FilePond')
    ->prefix('josephcrowell/magicforms')
    ->group(function () {
        Route::post('/process', 'FilePondController@upload')->name('filepond.upload');
        Route::delete('/process', 'FilePondController@delete')->name('filepond.delete');
    });
