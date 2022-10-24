<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;
use Mistralys\FELHQuestEditor\DataTypes\BaseDataTypeCollection;

/**
 * @see EnumItemContainerTrait
 */
interface EnumItemContainerInterface
{
    public const ERROR_ITEM_DOES_NOT_EXIST = 119801;

    /**
     * @param string $id
     * @param string $label
     * @return $this
     */
    public function addEnumItem(string $id, string $label) : self;

    public function addEnumItemR(string $id, string $label) : EnumItem;

    /**
     * Adds all items from the data type collection to the Enum.
     *
     * @param BaseDataTypeCollection $collection
     * @return $this
     */
    public function addDataCollection(BaseDataTypeCollection $collection) : self;

    /**
     * Attempts to resolve an item that can be used as the
     * default in the list.
     *
     * @return EnumItem|null
     */
    public function resolveDefaultItem() : ?EnumItem;

    public function itemIDExists(string $id) : bool;

    public function getItemByID(string $id) : EnumItem;

    /**
     * @return EnumItem[]
     */
    public function getItems() : array;

    public function getAttribute() : EnumAttribute;

    public function getItemsRecursive(array $result=array()) : array;
}
