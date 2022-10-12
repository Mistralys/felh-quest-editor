<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use AppUtils\ConvertHelper;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

class EnumItemGroup extends EnumItem implements EnumItemContainerInterface
{
    use EnumItemContainerTrait;

    private EnumAttribute $attribute;

    public function __construct(EnumAttribute $attribute, string $label)
    {
        $this->attribute = $attribute;

         parent::__construct('group-'.ConvertHelper::transliterate($label), $label);
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
}
