<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Exercises\Erd\EntityController;

  Route::post('student/exercise/entity/next_step', [EntityController::class, 'nextStep'])
?>
