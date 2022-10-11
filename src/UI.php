<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use Mistralys\FELHQuestEditor\UI\Icon;
use newrelic\DistributedTracePayload;

class UI
{
    private static bool $showXMLTags = false;

    /**
     * @var string[]
     */
    private static array $jsAutoLoad = array();

    public static function icon(string $id='') : Icon
    {
        return new Icon($id);
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
}
