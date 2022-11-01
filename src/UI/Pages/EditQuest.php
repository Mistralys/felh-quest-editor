<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI\Pages;

use Mistralys\FELHQuestEditor\Quest;
use Mistralys\FELHQuestEditor\Request;
use Mistralys\FELHQuestEditor\UI\BasePage;
use function AppUtils\sb;

class EditQuest extends BasePage
{
    private Quest $quest;

    public function __construct(Request $request, Quest $quest)
    {
        $this->quest = $quest;

        parent::__construct($request);
    }

    public const URL_NAME = 'edit';

    public function getTitle() : string
    {
        return (string)sb()->t('Edit quest:')->add($this->quest->getInternalName());
    }

    public function getAbstract() : string
    {
        return '';
    }

    public function display() : void
    {
        echo $this->quest->getForm()->render();
    }
}
