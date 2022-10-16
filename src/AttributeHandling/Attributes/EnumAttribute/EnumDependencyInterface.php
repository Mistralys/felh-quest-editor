<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

interface EnumDependencyInterface
{
    public function getLabel() : string;
}
