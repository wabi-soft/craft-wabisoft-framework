<?php

namespace wabisoft\framework\services;

use Craft;

class Logging
{
    public static function info($message) {
        Craft::getLogger()->log($message, 1, 'wabisoft');
    }
}
