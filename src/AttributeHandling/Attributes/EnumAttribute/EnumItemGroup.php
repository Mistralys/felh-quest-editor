<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use AppUtils\ConvertHelper;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseAttribute;

class EnumItemGroup extends EnumItem implements EnumItemContainerInterface
{
    use EnumItemContainerTrait;

    public function __construct(EnumAttribute $attribute, string $label)
    {
         parent::__construct($attribute, 'group-'.ConvertHelper::transliterate($label), $label);
    }

    public function getDependentAttribute() : BaseAttribute
    {
        return $this->attribute;
    }
}
