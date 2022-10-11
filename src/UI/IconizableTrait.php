<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI;

use function AppUtils\sb;

trait IconizableTrait
{
    private ?Icon $icon = null;

    public function getIcon() : Icon
    {
        return $this->icon;
    }

    public function hasIcon() : bool
    {
        return isset($this->icon);
    }

    /**
     * @param Icon|NULL $icon
     * @return $this
     */
    public function setIcon(?Icon $icon) : self
    {
        $this->icon = $icon;
        return $this;
    }

    public function renderIconLabel(string $label) : string
    {
        if(isset($this->icon)) {
            return $this->icon.'&#160;'.$label;
        }

        return $label;
    }
}
