<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use Mistralys\FELHQuestEditor\AttributeHandling\AttributeException;
use Mistralys\FELHQuestEditor\DataTypes\BaseDataTypeCollection;

/**
 * @see EnumItemContainerInterface
 */
trait EnumItemContainerTrait
{
    /**
     * @var array<string,EnumItem|EnumItemGroup>
     */
    private array $enumItems = array();

    /**
     * @param string $id
     * @param string $label
     * @return $this
     */
    public function addEnumItem(string $id, string $label) : self
    {
        return $this->registerItem(new EnumItem($id, $label));
    }

    protected function registerItem(EnumItem $item) : self
    {
        $this->enumItems[$item->getID()] = $item;
        return $this;
    }

    public function addDataCollection(BaseDataTypeCollection $collection) : self
    {
        $items = $collection->getAll();

        foreach($items as $item) {
            $this->addEnumItem($item->getID(), $item->getLabel());
        }

        return $this;
    }

    public function resolveDefaultItem() : ?EnumItem
    {
        if(empty($this->enumItems))
        {
            return null;
        }

        foreach($this->enumItems as $item)
        {
            if($item instanceof EnumItemGroup)
            {
                if($item->resolveDefaultItem() !== null)
                {
                    return $item->resolveDefaultItem();
                }

                continue;
            }

            return $item;
        }

        return null;
    }

    public function itemIDExists(string $id) : bool
    {
        foreach($this->enumItems as $item)
        {
            if($item->getID() === $id)
            {
                return true;
            }

            if($item instanceof EnumItemGroup && $item->itemIDExists($id))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $id
     * @return EnumItem
     * @throws AttributeException
     */
    public function getItemByID(string $id) : EnumItem
    {
        foreach($this->enumItems as $item)
        {
            if($item->getID() === $id)
            {
                return $item;
            }

            if($item instanceof EnumItemGroup && $item->itemIDExists($id))
            {
                return $item->getItemByID($id);
            }
        }

        throw new AttributeException(
            'Enum item not found by ID.',
            sprintf(
                'No item was found with the ID [%s]. '.PHP_EOL.
                'Enum type: [%s] '.PHP_EOL.
                'Available item IDs: '.PHP_EOL.
                '- %s',
                $id,
                get_class($this),
                implode(PHP_EOL.'- ', array_keys($this->getItemIDs()))
            ),
            EnumItemContainerInterface::ERROR_ITEM_DOES_NOT_EXIST
        );
    }

    public function getItems() : array
    {
        return array_values($this->enumItems);
    }

    public function getItemIDs() : array
    {
        $result = array();

        foreach($this->enumItems as $item)
        {
            if($item instanceof EnumItemGroup) {
                $result = array_merge($result, $item->getItemIDs());
                continue;
            }

            $result[] = $item->getID();
        }

        return $result;
    }
}
