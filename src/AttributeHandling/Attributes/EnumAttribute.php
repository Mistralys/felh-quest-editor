<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes;

use AppUtils\HTMLTag;
use Mistralys\FELHQuestEditor\AttributeHandling\AttributeException;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute\EnumItem;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute\EnumItemContainerInterface;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute\EnumItemContainerTrait;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute\EnumItemGroup;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseAttribute;
use function AppLocalize\t;

class EnumAttribute extends BaseAttribute implements EnumItemContainerInterface
{
    use EnumItemContainerTrait;

    private string $default = '';

    public function addEnumGroup(string $label) : EnumItemGroup
    {
        $group = new EnumItemGroup($this, $label);
        $this->registerItem($group);
        return $group;
    }

    public function getDefault() : string
    {
        if(!empty($this->default)) {
            return $this->default;
        }

        $default = $this->resolveDefaultItem();

        if($default !== null) {
            return $default->getID();
        }

        return '';
    }

    public function setDefault(string $default) : self
    {
        if($this->itemIDExists($default)) {
            $this->default = $default;
        }

        return $this;
    }

    public function hasValue(string $value) : bool
    {
        return $this->itemIDExists($value);
    }

    /**
     * @param string $value
     * @return string
     * @throws AttributeException
     */
    public function getLabelByValue(string $value) : string
    {
        return $this->getItemByID($value)->getLabel();
    }

    protected function displayElement() : void
    {
        $select = HTMLTag::create('select')
            ->name($this->getElementName())
            ->id($this->getElementID())
            ->addClass('form-control');

        echo $select->renderOpen();
        echo '<option value="">'.t('Please select...').'</option>';
        $this->displayEnumItems($this->enumItems, $this->getFormValue());
        echo $select->renderClose();
    }

    private function displayEnumItems(array $items, string $elementValue) : void
    {
        foreach($items as $item)
        {
            if($item instanceof EnumItemGroup)
            {
                $this->displayEnumGroup($item, $elementValue);
            }
            else
            {
                $this->displayEnumItem($item, $elementValue);
            }
        }
    }

    private function displayEnumGroup(EnumItemGroup $group, string $elementValue) : void
    {
        $tag = HTMLTag::create('optgroup')
            ->attr('label', $group->getLabel());

        echo $tag->renderOpen();
        $this->displayEnumItems($group->getItems(), $elementValue);
        echo $tag->renderClose();
    }

    private function displayEnumItem(EnumItem $item, string $elementValue) : void
    {
        $optionValue = $item->getID();

        $option =  HTMLTag::create('option')
            ->setEmptyAllowed(true)
            ->attr('value', $optionValue)
            ->setContent($item->getLabel());

        if($optionValue === $elementValue) {
            $option->prop('selected');
        }

        echo $option;
    }
}
