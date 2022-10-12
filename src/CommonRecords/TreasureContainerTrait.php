<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\CommonRecords;

trait TreasureContainerTrait
{
    /**
     * @var Treasure[]
     */
    protected array $treasures = array();

    public function addTreasure(Treasure $treasure) : void
    {
        $this->treasures[] = $treasure;
        $this->getTreasureGroup()->addRecord($treasure);
    }

    public function getTreasures() : array
    {
        return $this->treasures;
    }
}
