<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes;

use AppUtils\HTMLTag;
use Mistralys\FELHQuestEditor\AttributeHandling\AttributeException;
use Mistralys\FELHQuestEditor\AttributeHandling\AttributeGroup;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseAttribute;

class EnumAttribute extends BaseAttribute
{
    public const ERROR_CANNOT_GET_LABEL_BY_VALUE = 119501;

    /**
     * @var array<string,string>
     */
    private array $items;

    private string $default = '';

    /**
     * @param string $name
     * @param string $label
     * @param array<string,string> $items
     */
    public function __construct(AttributeGroup $group, string $name, string $label, array $items)
    {
        parent::__construct($group, $name, $label);

        $this->items = $items;
    }

    public function getDefault() : string
    {
        if(!empty($this->default)) {
            return $this->default;
        }

        reset($this->items);

        return key($this->items);
    }

    public function setDefault(string $default) : self
    {
        if(isset($this->items[$default])) {
            $this->default = $default;
        }

        return $this;
    }

    public function hasValue(string $value) : bool
    {
        return isset($this->items[$value]);
    }

    /**
     * @param string $value
     * @return string
     * @throws AttributeException
     */
    public function getLabelByValue(string $value) : string
    {
        if(isset($this->items[$value])) {
            return $this->items[$value];
        }

        throw new AttributeException(
            'Cannot find enum item label by value.',
            sprintf(
                'The value [%s] does not exist in the items. '.PHP_EOL.
                'Available values: '.PHP_EOL.
                '- %s',
                $value,
                implode(PHP_EOL.'- ', array_keys($this->items))
            ),
            self::ERROR_CANNOT_GET_LABEL_BY_VALUE
        );
    }

    protected function displayElement() : void
    {
        $select = HTMLTag::create('select')
            ->name($this->getElementName())
            ->id($this->getElementID())
            ->addClass('form-control');

        echo $select->renderOpen();

        $elementValue = $this->getFormValue();

        foreach($this->items as $optionValue => $label)
        {
            $option =  HTMLTag::create('option')
                ->attr('value', (string)$optionValue)
                ->setContent($label);

            if($optionValue === $elementValue) {
                $option->prop('selected');
            }

            echo $option;
        }

        echo $select->renderClose();
    }
}
