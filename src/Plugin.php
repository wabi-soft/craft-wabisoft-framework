<?php

namespace wabisoft\framework;


use Craft;
use craft\log\MonologTarget;
use Monolog\Formatter\LineFormatter;
use Psr\Log\LogLevel;
use yii\log\Logger;

class Plugin extends \craft\base\Plugin
{
    public function init()
    {
        parent::init();
        /*
         * @link: https://putyourlightson.com/articles/adding-logging-to-craft-plugins-with-monolog
         */
        $this->_registerLogTarget();
    }

    /**
     * Logs a message to our custom log target.
     */
    public function log(string $message, int $type = Logger::LEVEL_INFO): void
    {
        Craft::getLogger()->log($message, $type, 'wabisoft');
    }

    /**
     * Registers a custom log target, keeping the format as simple as possible.
     */
    private function _registerLogTarget(): void
    {
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
