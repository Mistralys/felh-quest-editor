<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use AppUtils\Interface_Stringable;

interface EnumDependencyContainerInterface extends EnumDependencyInterface
{
    /**
     * @param string|Interface_Stringable $attributeName
     * @param $description
     * @param bool $optional
     * @return $this
     */
    public function addDependency(string $attributeName, $description, bool $optional=false) : self;

    public function addDependencySet(string $label) : EnumItemDependencySet;

    /**
     * @return EnumDependencyInterface[]
     */
    public function getDependencies() : array;
}
