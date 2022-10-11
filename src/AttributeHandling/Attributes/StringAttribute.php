<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes;

use AppUtils\HTMLTag;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseAttribute;

class StringAttribute extends BaseAttribute
{
    private string $default = '';

    public function setDefault(string $default) : self
    {
        $this->default = $default;
        return $this;
    }

    public function getDefault() : string
    {
        return $this->default;
    }

    protected function displayElement() : void
    {
        echo HTMLTag::create('input')
            ->name($this->getElementName())
            ->id($this->getElementID())
            ->addClass('form-control')
            ->attr('type', 'text')
            ->attr('value', $this->getFormValue());
    }
}
