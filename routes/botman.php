<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello My Friend!');
});

$botman->hears('/course', 'App\Http\Controllers\PBCourseValueController@showCourse');

$botman->hears('Start conversation', BotManController::class.'@startConversation');
