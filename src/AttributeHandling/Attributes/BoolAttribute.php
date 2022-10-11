<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes;

use AppUtils\HTMLTag;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseAttribute;

class BoolAttribute extends BaseAttribute
{
    protected function displayElementScaffold() : void
    {
        ?>
        <div class="form-check">
            {ELEMENT}
            <label for="<?php echo $this->getElementID() ?>" class="form-check-label">
                <?php echo $this->getLabel() ?>
            </label>
            <div class="element-description">
                <?php echo $this->getDescription() ?>
            </div>
        </div>
        <?php
    }

    public function displayElement() : void
    {
        $tag = HTMLTag::create('input')
            ->name($this->getElementName())
            ->id($this->getElementID())
            ->addClass('form-check-input')
            ->attr('type', 'checkbox')
            ->attr('value', '1');

        if($this->isEnabled()) {
            $tag->prop('checked');
        }

        echo $tag;
    }

    public function isEnabled() : bool
    {
        return $this->getFormValue() === '1';
    }
}
