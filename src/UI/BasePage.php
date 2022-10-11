<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI;

use AppUtils\OutputBuffering;

abstract class BasePage
{
    abstract public function getTitle() : string;

    abstract public function display() : void;

    public function render() : string
    {
        OutputBuffering::start();
        $this->display();
        return OutputBuffering::get();
    }
}
