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
        if (file_put_contents($this->path_to_config, json_encode($users, JSON_PRETTY_PRINT))) {
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
                "days" => 0,
                "exercises" => array()
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

    public function addExercise($username, $exercise) {
        $users = (array) $this->retrieveUsers();
        if (!empty($users[$username]->exercises->{$exercise[2]})) {
            $users[$username]->exercises->{$exercise[2]} += $exercise[0];
        } else {
            $users[$username]->exercises->{$exercise[2]} = $exercise[0];
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
                [($count * 5 + 10), "присідань", "squat"],
                [($count * 2 + 10), "відтискань", "pushup"],
                [($count * 10 + 10), "пресу", "press"],
                [($count * 2 + 10), "жиму на трицепс", "triceps"],
                [($count * 5 + 20), "сек планки", "plank"],
            );

            $generated = $exercise[array_rand($exercise)];

            $message .= $user->username . " - " . $generated[0] . " " . $generated[1] . "\n";

            $this->addExercise(substr($user->username, 1), $generated);
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
            [($count * 5 + 10), "присідань", "squat"],
            [($count * 2 + 10), "відтискань", "pushup"],
            [($count * 5 + 10), "пресу", "press"],
            [($count * 3 + 10), "жиму на трицепс", "triceps"],
            [($count * 5 + 30), "сек планки", "plank"],
        );

        $generated = $exercise[array_rand($exercise)];

        $message .= "@" . $user . " - " . $generated[0] . " " . $generated[1] . "\n";
        $this->addExercise($user, $generated);
        $message .= "\nГуд лак!";

        if (!empty($this->users)) {
            return $message;
        }
    }

    public function callHardMode() {

        $message = "<b>GigaChad завдання: впродовж дня маєте зробити: </b>\n\n";
        foreach ( $this->users as $user ) {
            $exercise = array(
                [((100 + $user->days * 2) < 300 ? (100 + $user->days * 2) : 300), "присідань" , "squat"],
                [((50 + $user->days) < 150 ? (50 + $user->days) : 150), "відтискань" , "pushup"],
                [((100 + $user->days * 2) < 300 ? (100 + $user->days * 2) : 300), "пресу" , "press"],
                [((50 + $user->days) < 150 ? (50 + $user->days) : 150), "жиму на трицепс" , "triceps"],
                [((240 + $user->days) * 10 < 600 ? (240 + $user->days)  * 10 : 600), "сек планки" , "plank"],
            );

            $generated = $exercise[array_rand($exercise)];

            $message .= $user->username . " - " . $generated[0] . " " . $generated[1] . "\n";

            $this->addExercise($user, $generated);
        }
        $message .= "\nГарного дня, тюбікі";

        if (!empty($this->users)) {
            return $message;
        }
    }

    public function mentionAll() {
        $string = "";
        foreach ( $this->users as $user ) {
            $string .= $user->username . " ";
        }

        if (!empty($this->users)) {
            return $string;
        }
    }
    
}