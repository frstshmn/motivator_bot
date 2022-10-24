<?php

class TiubickController {

    private $users = [];
    private $path_to_config;

    public function __construct($path = "json/tiubicks.json") {
        $this->path_to_config = $path;
        $users = (array) $this->retrieveUsers();
        $this->users = $users;
    }

    public function retrieveUsers() {
        $this->users = json_decode(file_get_contents($this->path_to_config));
        return $this->users;
    }

    public function saveUsers($users) {
        if (file_put_contents($this->path_to_config, json_encode($users))) {
            return true;
        } else {
            return false;
        }
    }

    public function addTiubick($username) {
        $users = (array) $this->retrieveUsers();
        if (array_key_exists($username, $users) == false) {
            $users[$username] = array(
                "username" => "@" . $username,
                "days" => 0
            );
        }
        $this->saveUsers($users);
    }

    public function removeTiubick($username) {
        $users = (array) $this->retrieveUsers();
        if (array_key_exists($username, $users) == true) {
            unset($users[$username]);
        }
        $this->saveUsers($users);
    }

    public function addDay() {
        $users = (array) $this->retrieveUsers();
        foreach ( $users as $user ) {
            $user->days += 1;
        }
        $this->saveUsers($users);
    }

    public function callAll() {
        $titles = array(
            "<b>Ану тюбікі, хватить сігаретку свою посасувати, пиво те попівати, работаєм:</b>",
            "<b>Так, відклали телефони і бистренько прокачались:</b>",
            "<b>Час ставати краще, поднімайте жопи і качайтесь:</b>",
            "<b>Вейк ап ту реаліті...</b>",
        );

        $message = $titles[array_rand($titles)] . "\n\n";
        foreach ($this->users as $user) {
            $count = rand(1, 10);

            $exercise = array(
                ($count * 10) . " присідань",
                ($count * 2 + 10) . " відтискань",
                ($count * 10 + 10) . " пресу",
                ($count * 2 + 10) . " жиму на трицепс",
                ($count * 10 + 20) . " сек планки",
            );

            $message .= $user . " - " . $exercise[array_rand($exercise)] . "\n";
        }
        $message .= "\nГуд лак!";

        if (!empty($this->users)) {
            return $message;
        }
    }

    public function callSingle($user) {
        $titles = array(
            "<b>Ану тюбік, хватить сігаретку свою посасувати, пиво те попівати, работаєм:</b>",
            "<b>Так, відклав телефон і бистренько прокачався:</b>",
            "<b>Час ставати краще, поднімай жопу і качайтесь:</b>",
            "<b>Вейк ап ту реаліті...</b>",
        );

        $message = $titles[array_rand($titles)] . "\n\n";
        $count = rand(1, 10);

        $exercise = array(
            ($count * 10) . " присідань",
            ($count * 2 + 10) . " відтискань",
            ($count * 10 + 10) . " пресу",
            ($count * 2 + 10) . " жиму на трицепс",
            ($count * 10 + 20) . " сек планки",
        );

        $message .= "@" . $user . " - " . $exercise[array_rand($exercise)] . "\n";
        $message .= "\nГуд лак!";

        if (!empty($this->users)) {
            return $message;
        }
    }

    public function callHardMode() {

        $message = "<b>GigaChad завдання: впродовж дня маєте зробити: </b>\n\n";
        foreach ( $this->users as $user ) {
            $exercise = array(
                ((100 + $user->days * 2) < 300 ? (100 + $user->days * 2) : 300) . " присідань",
                ((50 + $user->days) < 150 ? (50 + $user->days) : 150) . " відтискань",
                ((100 + $user->days * 2) < 300 ? (100 + $user->days * 2) : 300) . " пресу",
                ((50 + $user->days) < 150 ? (50 + $user->days) : 150) . " жиму на трицепс",
                ((240 + $user->days) * 10 < 600 ? (240 + $user->days)  * 10 : 600) . " сек планки",
            );

            $message .= $user->username . " - " . $exercise[array_rand($exercise)] . "\n";
        }
        $message .= "\nГарного дня, тюбікі";

        if (!empty($this->users)) {
            return $message;
        }
    }
    
}