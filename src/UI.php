<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\FileHelper;
use Mistralys\FELHQuestEditor\UI\Icon;
use newrelic\DistributedTracePayload;

class UI
{
    private static bool $showXMLTags = false;

    /**
     * @var string[]
     */
    private static array $jsAutoLoad = array();

    public static function icon() : Icon
    {
        return new Icon('');
    }

    public static function setShowXMLTags(bool $enabled) : void
    {
        self::$showXMLTags = $enabled;
    }

    public static function isShowXMLTagsEnabled() : bool
    {
        return self::$showXMLTags;
    }

    public static function addJSOnload(string $statement) : void
    {
        if(!in_array($statement, self::$jsAutoLoad)) {
            self::$jsAutoLoad[] = $statement;
        }
    }

    public static function getJSAutoLoad() : array
    {
        return self::$jsAutoLoad;
    }

    private static ?string $version = null;

    public static function getVersion() : string
    {
        if(!isset(self::$version))
        {
            self::$version = FileHelper::readContents(__DIR__ . '/../VERSION');
        }

        return self::$version;
    }
}
