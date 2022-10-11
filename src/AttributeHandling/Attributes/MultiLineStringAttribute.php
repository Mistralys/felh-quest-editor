<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes;

use AppUtils\HTMLTag;

class MultiLineStringAttribute extends StringAttribute
{
    protected function displayElement() : void
    {
        echo HTMLTag::create('textarea')
            ->name($this->getElementName())
            ->id($this->getElementID())
            ->addClass('form-control')
            ->attr('rows', '4')
            ->setContent($this->getFormValue())
            ->render();
    }
}
