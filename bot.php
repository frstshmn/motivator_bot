<?php

header('Content-Type: text/html; charset=utf-8');

require_once("app/Bot.php");
require_once("app/TiubickController.php");

$bot = new Bot();
$tiubicks = new TiubickController();
$data = $bot->getData();

if (!empty($data['text'])) {
    if (str_contains($data['text'], '/join')) {
        if ( $tiubicks->addTiubick($data['username']) ) {
            $bot->sendMessage("Всьо, бікам стронгер, варріор!");
        } else {
            $bot->sendMessage("Шось не так");
        }
    } else if (str_contains($data['text'], '/leave')) {
        if ( $tiubicks->removeTiubick($data['username']) ) {
            $bot->sendMessage("Шо, здувся, тюбік? Теряйся, слабак");
        } else {
            $bot->sendMessage("Шось не так");
        }
    } else if (str_contains($data['text'], '/get_task')) {
        if ($bot->getIsHard() != true) {
            $bot->sendMessage($tiubicks->callSingle($data['username']));
        } else {
            $bot->sendMessage("Відпочивайте поки");
        }
    }
} else {
    if (!empty($_GET['action'])) {
        if ($_GET['action'] == 'call') {
            if (rand(1, 3) == 2 && $bot->getIsHard() != true) {
                $bot->sendMessage($tiubicks->callAll());
            }
        } else if ($_GET['action'] == 'hardmode') {
            $tiubicks->addDay();

            if (rand(1, 4) == 2) {
                $bot->sendMessage($tiubicks->callHardMode());
                $bot->setIsHard(true);
            } else {
                $bot->setIsHard(false);
            }
        } else if ($_GET['action'] == 'resttime') {
            $string = "<b>ЦІЛИЙ ТИЖДЕНЬ ПАХАЛИ, А ТЕПЕР ДЕНЬ РАСКУМАРА</b>\n\n";
            $string .= "Вечір п'ятниці, того забивайте на все, відпочивайте, як востаннє\n";
            $string .= "План на сьогодні: йобнути пивка чи іншого вкусного напойчику, затянути плотну дуделочку, разорвати центр або позаліпати шось дома\n";
            $string .= "Всім вдалого дня, приємних вихідних, живіть життя!\n\n";
            $string .= $tiubicks->mentionAll();

            $bot->sendVideo($string, "https://frstshmn.tech/motivator_bot/media/resttime.mp4");
        }
    }
}