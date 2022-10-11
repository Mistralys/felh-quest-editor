<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI\Pages;

use Mistralys\FELHQuestEditor\Quest;
use Mistralys\FELHQuestEditor\UI\BasePage;

class EditQuest extends BasePage
{
    private Quest $quest;

    public function __construct(Quest $quest)
    {
        $this->quest = $quest;
    }

    public const URL_NAME = 'edit';

    public function getTitle() : string
    {
        return 'Edit: '.$this->quest->getInternalName();
    }

    public function display() : void
    {
        echo $this->quest->getForm()->render();
    }
}
