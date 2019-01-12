<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('/help', function ($bot) {
    $bot->reply('Hello My Friend!');
    $bot->reply('Please start whith command /menu !');
});

$botman->hears('/show', 'App\Http\Controllers\PBCourseValueController@showCourse');
$botman->hears('/arhive', 'App\Http\Controllers\PBCourseValueController@showCourseArhive');

$botman->hears('/menu', BotManController::class.'@startConversation');

$botman->hears('/start', BotManController::class.'@startConversation');
