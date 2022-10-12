<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\CommonRecords;

trait GameModifierContainerTrait
{
    /**
     * @var GameModifier[]
     */
    protected array $modifiers = array();

    public function addGameModifier(GameModifier $modifier) : void
    {
        $this->modifiers[] = $modifier;
        $this->getGameModifiersGroup()->addRecord($modifier);
    }

    public function getGameModifiers() : array
    {
        return $this->modifiers;
    }
}
