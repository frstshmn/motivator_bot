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
            $bot->sendMessage("Шось не так", $bot->getToken());
        }
    } else if (str_contains($data['text'], '/leave')) {
        if ( $tiubicks->removeTiubick($data['username']) ) {
            $bot->sendMessage("Шо, здувся, тюбік? Теряйся, слабак");
        } else {
            $bot->sendMessage("Шось не так", $bot->getToken());
        }
    } else if (str_contains($data['text'], '/get_task')) {
        if ($bot->getIsHard() != true) {
            $bot->sendMessage($tiubicks->callSingle($data['username']));
        } else {
            $bot->sendMessage("Відпочивайте поки", $bot->getToken());
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
        }
    }
}