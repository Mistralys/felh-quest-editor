<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\DataTypes;

use AppUtils\ClassHelper;

abstract class BaseDataTypeCollection
{
    /**
     * @var array<string,BaseDataType>
     */
    private array $items = array();

    public function __construct()
    {
        $this->registerItems();

        usort($this->items, static function(BaseDataType $a, BaseDataType $b) {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });
    }

    abstract public function getCollectionLabel() : string;
    abstract protected function registerItems() : void;

    /**
     * @return class-string
     */
    abstract protected function getDataTypeClass() : string;

    /**
     * @return BaseDataType[]
     */
    public function getAll() : array
    {
        return array_values($this->items);
    }

    protected function registerItem(string $id, string $label) : self
    {
        $class = $this->getDataTypeClass();

        $this->items[$id] = ClassHelper::requireObjectInstanceOf(
            BaseDataType::class,
            new $class($id, $label)
        );

        return $this;
    }
}
