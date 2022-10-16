<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\DataTypes;

use Mistralys\FELHQuestEditor\DataTypes\Units\Unit;

abstract class BaseUnitCollection extends BaseDataTypeCollection
{
    protected function getDataTypeClass() : string
    {
        return Unit::class;
    }
}
