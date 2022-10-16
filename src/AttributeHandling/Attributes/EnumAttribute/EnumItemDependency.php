<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use AppUtils\Interface_Stringable;

class EnumItemDependency implements EnumDependencyInterface
{
    private string $attributeName;
    private string $label;
    private bool $optional;

    /**
     * @param string $attributeName
     * @param string|Interface_Stringable $label
     * @param bool $optional
     */
    public function __construct(string $attributeName, $label, bool $optional)
    {
        $this->optional = $optional;
        $this->attributeName = $attributeName;
        $this->label = (string)$label;
    }

    public function isOptional() : bool
    {
        return $this->optional;
    }

    public function getAttributeName() : string
    {
        return $this->attributeName;
    }

    public function getLabel() : string
    {
        return $this->label;
    }
}
