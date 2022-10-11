<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI;

use AppUtils\OutputBuffering;
use AppUtils\Request;

abstract class BasePage
{
    protected Request $request;

    public function __construct()
    {
        $this->request = Request::getInstance();
    }

    abstract public function getTitle() : string;

    abstract public function display() : void;

    public function render() : string
    {
        OutputBuffering::start();
        $this->display();
        return OutputBuffering::get();
    }
}
