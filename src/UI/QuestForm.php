<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI;

use AppUtils\OutputBuffering;
use Mistralys\FELHQuestEditor\Quest;

class QuestForm
{
    private Quest $quest;

    public function __construct(Quest $quest)
    {
        $this->quest = $quest;
    }

    public function render() : string
    {
        OutputBuffering::start();
        $this->display();
        return OutputBuffering::get();
    }

    public function display() : void
    {
        $manager = $this->quest->getAttributeManager();

        $form = $manager->getForm();

        ?>
        <form method="post">
            <?php echo $form->render(); ?>
        </form>
        <?php
    }
}
