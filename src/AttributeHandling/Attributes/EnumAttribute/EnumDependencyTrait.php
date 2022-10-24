<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use AppUtils\ClassHelper;
use AppUtils\Interface_Stringable;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseAttribute;

/**
 * @see EnumDependencyInterface
 */
trait EnumDependencyTrait
{
    public function setDescription($description) : self
    {
        $this->getEnumItem()->setDescription($description);
        return $this;
    }

    public function getDependentAttribute() : BaseAttribute
    {
        return ClassHelper::requireObjectInstanceOf(
            BaseAttribute::class,
            $this->getEnumItem()
                ->getAttributeManager()
                ->getAttributeByName($this->getDependentAttributeName())
        );
    }

    /**
     * @param string $questID
     * @param string|number|Interface_Stringable $label
     * @return EnumDependencyTrait|EnumItemDependency
     */
    public function addRelatedQuest(string $questID, $label='') : self
    {
        $this->getEnumItem()->addRelatedQuest($questID, $label);
        return $this;
    }

    public function done() : EnumItem
    {
        return $this->getEnumItem();
    }
}
