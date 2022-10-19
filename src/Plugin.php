<?php

namespace wabisoft\framework;


use Craft;
use craft\log\MonologTarget;
use Monolog\Formatter\LineFormatter;
use Psr\Log\LogLevel;
use yii\base\Event;
use craft\web\View;
use craft\events\RegisterTemplateRootsEvent;

class Plugin extends \craft\base\Plugin
{
    public function init()
    {
        parent::init();
        /*
         * @link: https://putyourlightson.com/articles/adding-logging-to-craft-plugins-with-monolog
         */
        Craft::getLogger()->dispatcher->targets[] = new MonologTarget([
            'name' => 'wabisoft',
            'categories' => ['wabisoft'],
            'level' => LogLevel::INFO,
            'logContext' => false,
            'allowLineBreaks' => false,
            'formatter' => new LineFormatter(
                format: "%datetime% %message%\n",
                dateFormat: 'Y-m-d H:i:s',
            ),
        ]);
    }

}
