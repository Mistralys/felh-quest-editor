<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes;

use AppUtils\HTMLTag;
use AppUtils\OutputBuffering;
use Mistralys\FELHQuestEditor\AttributeHandling\AttributeException;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute\EnumDependencyContainerInterface;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute\EnumDependencyInterface;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute\EnumItem;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute\EnumItemContainerInterface;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute\EnumItemContainerTrait;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute\EnumItemGroup;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseAttribute;
use Mistralys\FELHQuestEditor\UI;
use function AppLocalize\pt;
use function AppLocalize\t;

class EnumAttribute extends BaseAttribute implements EnumItemContainerInterface
{
    use EnumItemContainerTrait;

    private string $default = '';
    private bool $multiple = false;

    /**
     * @return $this
     */
    public function getAttribute() : self
    {
        return $this;
    }

    /**
     * @return $this
     */
    public function makeMultiple() : self
    {
        $this->multiple = true;
        return $this;
    }

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

    /**
     * @param string $default
     * @return $this
     */
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

    protected function getClientObjectName() : string
    {
        return $this->getElementID().'obj';
    }

    protected function displayElement() : void
    {
        UI::addJSHead(sprintf(
            "const %1\$s = new EnumAttribute('%2\$s')",
            $this->getClientObjectName(),
            $this->getElementID()
        ));

        UI::addJSOnload(sprintf(
            "%s.Change()",
            $this->getClientObjectName()
        ));

        $select = HTMLTag::create('select')
            ->name($this->getElementName())
            ->id($this->getElementID())
            ->attr('onchange', sprintf("%s.Change()", $this->getClientObjectName()))
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
            ->setEmptyAllowed()
            ->attr('value', $optionValue)
            ->attr('data-item-id', $item->getJSID())
            ->setContent($item->getLabel());

        if($optionValue === $elementValue) {
            $option->prop('selected');
        }

        echo $option;
    }

    public function getDescription() : string
    {
        $description = parent::getDescription();

        $items = $this->getItemsRecursive();

        foreach($items as $item)
        {
            $description .= $this->renderItemDescription($item);
        }

        return $description;
    }

    private function renderItemDescription(EnumItem $item) : string
    {
        $itemDescr = $item->getDescription();
        $quests = $item->getRelatedQuests();
        $dependencies = $item->getDependencies();

        if(empty($itemDescr) && empty($quests) && empty($dependencies)) {
            return '';
        }

        OutputBuffering::start();
        ?>
        <div class="enum-item-description" id="<?php echo $item->getJSID() ?>" style="display: none">
            <?php
            if(!empty($itemDescr)) {
                echo $itemDescr.'<br>';
            }

            if(!empty($dependencies))
            {
                pt('Dependencies:');
                ?>
                <ul>
                    <?php $this->displayDependencies($dependencies) ?>
                </ul>
                <?php
            }

            if(!empty($quests))
            {
                pt('Related quests:');
                ?>
                <br>
                <ul>
                <?php
                foreach($quests as $quest)
                {
                    $label = $quest->getLabel();

                    ?>
                    <li>
                        <?php echo $quest->getQuestID() ?>

                        <?php
                        if(!empty($label))
                        {
                            ?>
                                - <?php echo $label ?>
                            <?php
                        }
                        ?>
                    </li>
                    <?php
                }
                ?>
                </ul>
                <?php
            }
            ?>
        </div>
        <?php

        return OutputBuffering::get();
    }

    /**
     * @param EnumDependencyInterface[] $dependencies
     * @return void
     */
    private function displayDependencies(array $dependencies) : void
    {
        foreach($dependencies as $dependency)
        {
            if($dependency instanceof EnumDependencyContainerInterface)
            {
                ?>
                <b><?php echo $dependency->getLabel() ?></b>
                <ul>
                    <?php $this->displayDependencies($dependency->getDependencies()); ?>
                </ul>
                <?php
                continue;
            }

            ?>
            <li>
                <?php
                    echo $dependency->getDependentAttribute()->getLabel();

                    if(!$dependency->isDependencyOptional()) {
                        echo '*';
                    }

                    echo ' &raquo; ';
                    echo $dependency->getLabel()
                ?>
            </li>
            <?php
        }
    }
}
