<?php
use Illuminate\Support\Facades\Route;
use Weoneteam\PluginManager\Http\Controllers\PluginManagerController;

Route::group(['middleware' => ['auth','web'], 'prefix' => 'account/settings'], function () {

    Route::get('plugins', [PluginManagerController::class,'index'])->name('plugin-manager');
    Route::POST('plugins/active', [PluginManagerController::class,'active'])->name('plugin-manager.active');
    Route::POST('plugins/deactivate', [PluginManagerController::class,'deactivate'])->name('plugin-manager.deactivate');
    Route::POST('plugins/upload', [PluginManagerController::class,'upload'])->name('plugin-manager.upload');
});
