<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes;

use AppUtils\FileHelper\FileInfo;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseGraphicAttribute;

class IconImageAttribute extends BaseGraphicAttribute
{
    private array $selected = array();

    protected function getGfxPathRelative() : string
    {
        return 'Gfx/Icons';
    }

    public function selectIcon32() : self
    {
        return $this->selectType(static function(FileInfo $file) : bool {
            $size = getimagesize($file->getPath());
            return $size[0] === 32;
        });
    }

    private function selectType(callable $type) : self
    {
        $this->selected[] = $type;

        return $this;
    }

    protected function isFileValid(FileInfo $file) : bool
    {
        foreach($this->selected as $type)
        {
            if($type($file) !== true) {
                return false;
            }
        }

        return true;
    }
}
