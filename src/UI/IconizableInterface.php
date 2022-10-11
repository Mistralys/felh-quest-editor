<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI;

interface IconizableInterface
{
    public function setIcon(?Icon $icon) : self;

    public function getIcon() : ?Icon;

    public function hasIcon() : bool;
}
