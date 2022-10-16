<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use AppUtils\Interface_Stringable;

trait EnumDependencyContainerTrait
{
    /**
     * @var EnumDependencyInterface[]
     */
    protected array $enumDependencies = array();

    /**
     * @param string|Interface_Stringable $attributeName
     * @param $description
     * @param bool $optional
     * @return $this
     */
    public function addDependency(string $attributeName, $description, bool $optional=false) : self
    {
        $this->enumDependencies[] = new EnumItemDependency($attributeName, $description, $optional);
        return $this;
    }

    public function addDependencySet(string $label) : EnumItemDependencySet
    {
        $set = new EnumItemDependencySet($this->getEnumItem(), $label);
        $this->enumDependencies[] = $set;
        return $set;
    }

    public function done() : EnumItem
    {
        return $this->getEnumItem();
    }
}
