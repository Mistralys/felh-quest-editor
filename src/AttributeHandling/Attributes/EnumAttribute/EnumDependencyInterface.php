<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use AppUtils\Interface_Stringable;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseAttribute;

interface EnumDependencyInterface
{
    public function getLabel() : string;

    public function getDependentAttributeName() : string;

    public function isDependencyOptional() : bool;

    public function getDependentAttribute() : BaseAttribute;

    /**
     * @param string|number|Interface_Stringable $description
     * @return $this
     */
    public function setDescription($description) : self;

    /**
     * Adds a quest that showcases this enum item.
     *
     * @param string $questID
     * @param string|number|Interface_Stringable $label
     * @return $this
     */
    public function addRelatedQuest(string $questID, $label='') : self;

    public function getEnumItem() : EnumItem;
}
