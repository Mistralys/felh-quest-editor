<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\CommonRecords;

use Mistralys\FELHQuestEditor\AttributeHandling\RecordGroup;

interface TreasureContainerInterface
{
    public function addTreasure(Treasure $treasure) : void;

    /**
     * @return Treasure[]
     */
    public function getTreasures() : array;

    public function getTreasureGroup() : RecordGroup;
}
