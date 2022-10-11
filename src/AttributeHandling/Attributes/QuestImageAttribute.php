<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes;

use AppUtils\FileHelper\FileInfo;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseGraphicAttribute;

class QuestImageAttribute extends BaseGraphicAttribute
{
    /**
     * Starting letters of images that do not have
     * the right format, or should not be used.
     *
     * @var string[]
     */
    private array $exclude = array(
        '512_Mask',
        '5Seers',
        'CivWnd',
        'HighlvlShop',
        'LowLvlShop',
        'MedLvlShop',
        'Medallion_Frame',
        'WizardStudy'
    );

    protected function getGfxPathRelative() : string
    {
        return 'Gfx/Medallions';
    }

    protected function isFileValid(FileInfo $file) : bool
    {
        $name = $file->getBaseName();

        foreach($this->exclude as $search)
        {
            if (stripos($name, $search) === 0)
            {
                return false;
            }
        }

        return true;
    }
}
