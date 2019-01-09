<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('/help', function ($bot) {
    $bot->reply('Hello My Friend!');
});

$botman->hears('/show', 'App\Http\Controllers\PBCourseValueController@showCourse');
$botman->hears('/arhive', 'App\Http\Controllers\PBCourseValueController@showCourseArhive');

$botman->hears('/menu', BotManController::class.'@startConversation');
