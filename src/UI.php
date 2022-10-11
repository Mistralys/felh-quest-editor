<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use Mistralys\FELHQuestEditor\UI\Icon;

class UI
{
    private static bool $showXMLTags = false;

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
}
