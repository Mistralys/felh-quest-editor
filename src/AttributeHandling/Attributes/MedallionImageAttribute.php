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
        return strpos($file->getBaseName(), 'M_') === 0;
    }
}
