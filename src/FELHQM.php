<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use function AppLocalize\t;

class FELHQM
{
    public static function getName() : string
    {
        return t('Fallen Enchantress: Legendary Heroes Quest Editor');
    }

    public static function getShortName() : string
    {
        return t('Quest Editor');
    }

    private static ?Logger $logger = null;

    public static function log() : Logger
    {
        if(!isset(self::$logger))
        {
            self::$logger = new Logger('FELHQM');
            self::$logger->pushHandler(new StreamHandler('php://stdout'));
        }

        return self::$logger;
    }
}
