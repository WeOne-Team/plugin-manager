<?php
use Illuminate\Support\Facades\Route;
use Weoneteam\PluginManager\Http\Controllers\PluginManagerController;

Route::get('/plugins', [PluginManagerController::class, 'index'])->name('plugin-manager.index');
