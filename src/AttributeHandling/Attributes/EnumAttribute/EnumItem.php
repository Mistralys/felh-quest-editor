<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

class EnumItem implements EnumDependencyContainerInterface
{
    use EnumDependencyContainerTrait;

    protected string $id;
    protected string $label;
    protected EnumAttribute $attribute;

    public function __construct(EnumAttribute $attribute, string $id, string $label)
    {
        $this->attribute = $attribute;
        $this->id = $id;
        $this->label = $label;
    }

    public function getEnumItem() : EnumItem
    {
        return $this;
    }

    /**
     * Method chaining utility method: goes back to
     * the parent enum attribute after adding items
     * to the group.
     *
     * @return EnumAttribute
     */
    public function done() : EnumAttribute
    {
        return $this->attribute;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getID() : string
    {
        return $this->id;
    }
}
