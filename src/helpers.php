<?php

if (! function_exists('FlashMessage')) {
    function FlashMessage()
    {
        return \PytoCryto\Flash\FlashMessage::getInstance();
    }
}

if (! function_exists('info')) {
    function info()
    {
        return FlashMessage()->info(...func_get_args());
    }
}

if (! function_exists('success')) {
    function success()
    {
        return FlashMessage()->success(...func_get_args());
    }
}

if (! function_exists('warning')) {
    function warning()
    {
        return FlashMessage()->warning(...func_get_args());
    }
}

if (! function_exists('error')) {
    function error()
    {
        return FlashMessage()->error(...func_get_args());
    }
}

if (! function_exists('display')) {
    function display()
    {
        return FlashMessage()->display(...func_get_args());
    }
}
