<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI;

use AppUtils\OutputBuffering;
use Mistralys\FELHQuestEditor\Request;

abstract class BasePage
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    abstract public function getTitle() : string;

    abstract public function display() : void;

    abstract public function getAbstract() : string;

    public function render() : string
    {
        OutputBuffering::start();
        $this->display();
        return OutputBuffering::get();
    }

    public function handleActions() : void
    {
    }
}
