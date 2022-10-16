<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

class EnumItemDependencySet implements EnumDependencyContainerInterface
{
    use EnumDependencyContainerTrait;

    private string $label;
    private EnumDependencyContainerInterface $container;

    public function __construct(EnumDependencyContainerInterface $container, string $label)
    {
        $this->container = $container;
        $this->label = $label;
    }

    public function getEnumItem() : EnumItem
    {
        return $this->container->getEnumItem();
    }

    public function getContainer() : EnumDependencyContainerInterface
    {
        return $this->container;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function done() : EnumItem
    {
        return $this->getEnumItem();
    }
}
