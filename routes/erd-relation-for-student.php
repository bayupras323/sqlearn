<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Exercises\Erd\RelationshipController;

  Route::post('student/exercise/relationship/next_step', [RelationshipController::class, 'nextStep']);
?>