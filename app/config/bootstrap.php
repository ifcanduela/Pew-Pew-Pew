<?php


use ifcanduela\events\EventManager as E;

const SESSION_KEY = "MjgzOTkyNzY3MjIyODc2";
const USER_KEY = "user_id";

E::register("app.init", function () {
        # Initialize the debugbar
        if (pew("show_debugbar")) {
            $db = pew("debugbar");

            if (pew("request")->acceptsJson()) {
                $db->sendDataInHeaders();
            }
        }
});

function app_title(...$page_title)
{
    $page_title[] = pew("app_title");

    return join(" | ", array_filter($page_title));
}
