<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI\Pages;

use Mistralys\FELHQuestEditor\QuestsCollection;
use Mistralys\FELHQuestEditor\Request;
use Mistralys\FELHQuestEditor\UI;
use Mistralys\FELHQuestEditor\UI\BasePage;
use function AppLocalize\t;
use function AppUtils\sb;

class QuestsList extends BasePage
{
    public const URL_NAME = 'quests';

    private QuestsCollection $collection;

    public function __construct(Request $request, QuestsCollection $collection)
    {
        $this->collection = $collection;

        parent::__construct($request);
    }

    public function getTitle() : string
    {
        return t('Quests list');
    }

    public function getAbstract() : string
    {
        return (string)sb()->t('This shows all quests available in the selected game files.');
    }

    public function display() : void
    {
        ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Label</th>
                <th>Repeatable?</th>
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
                    <td><?php echo UI::bool($quest->isRepeatable()) ?></td>
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
