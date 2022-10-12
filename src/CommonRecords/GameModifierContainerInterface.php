<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\CommonRecords;

use Mistralys\FELHQuestEditor\AttributeHandling\RecordGroup;

interface GameModifierContainerInterface
{
    public function addGameModifier(GameModifier $modifier) : void;

    /**
     * @return GameModifier[]
     */
    public function getGameModifiers() : array;

    public function getGameModifiersGroup() : RecordGroup;
}
