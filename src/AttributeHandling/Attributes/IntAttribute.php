<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes;

use AppUtils\HTMLTag;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseAttribute;

class IntAttribute extends BaseAttribute
{
    private int $max = -1;
    private int $min = 0;

    public function setMax(int $max) : self
    {
        $this->max = $max;
        return $this;
    }

    public function setMin(int $min) : self
    {
        $this->min = $min;
        return $this;
    }

    protected function displayElement() : void
    {
        echo HTMLTag::create('input')
            ->name($this->getElementName())
            ->id($this->getElementID())
            ->addClass('form-control')
            ->attr('type', 'text')
            ->attr('value', $this->getFormValue())
            ->render();
    }
}
