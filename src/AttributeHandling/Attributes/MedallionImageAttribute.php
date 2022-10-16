<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes;

use AppUtils\FileHelper\FileInfo;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseGraphicAttribute;

class MedallionImageAttribute extends BaseGraphicAttribute
{
    protected function getGfxPathRelative() : string
    {
        return 'Gfx/Medallions';
    }

    protected function isFileValid(FileInfo $file) : bool
    {
        $size = getimagesize($file->getPath());

        // Must be square
        if($size[0] !== $size[1]) {
            return false;
        }

        if($size[0] < 256) {
            return false;
        }

        return true;
    }
}
