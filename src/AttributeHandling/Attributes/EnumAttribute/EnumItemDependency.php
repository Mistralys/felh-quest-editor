<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use AppUtils\Interface_Stringable;

class EnumItemDependency implements EnumDependencyInterface
{
    use EnumDependencyTrait;

    private string $attributeName;
    private string $label;
    private bool $optional;
    private EnumItem $item;

    /**
     * @param string $attributeName
     * @param string|Interface_Stringable $label
     * @param bool $optional
     */
    public function __construct(EnumItem $item, string $attributeName, $label, bool $optional)
    {
        $this->item = $item;
        $this->optional = $optional;
        $this->attributeName = $attributeName;
        $this->label = (string)$label;
    }

    public function getEnumItem() : EnumItem
    {
        return $this->item;
    }

    public function isDependencyOptional() : bool
    {
        return $this->optional;
    }

    public function getDependentAttributeName() : string
    {
        return $this->attributeName;
    }

    public function getLabel() : string
    {
        return $this->label;
    }
}
