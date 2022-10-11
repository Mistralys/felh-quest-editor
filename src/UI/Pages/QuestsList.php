<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI\Pages;

use Mistralys\FELHQuestEditor\QuestsCollection;
use Mistralys\FELHQuestEditor\UI\BasePage;
use function AppUtils\sb;

class QuestsList extends BasePage
{
    public const URL_NAME = 'quests';

    private QuestsCollection $collection;

    public function __construct(QuestsCollection $collection)
    {
        $this->collection = $collection;

        parent::__construct();
    }

    public function getTitle() : string
    {
        return 'Quests list';
    }

    public function display() : void
    {
        ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Label</th>
                <th>Tactical map</th>
                <th>File</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $quests = $this->collection->getAll();
            foreach ($quests as $quest)
            {
                ?>
                <tr>
                    <td><?php echo $quest->getInternalName() ?></td>
                    <td><?php echo sb()->link($quest->getDisplayName(), $quest->getURLEdit()) ?></td>
                    <td><?php echo $quest->getTacticalMapLabel() ?></td>
                    <td><?php echo $quest->getSourceFile()->getBaseName() ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    }
}
