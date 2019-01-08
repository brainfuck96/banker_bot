<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$botman->hears('/course', 'App\Http\Controllers\PBCourseValue@showCourse');

$botman->hears('Start conversation', BotManController::class.'@startConversation');
