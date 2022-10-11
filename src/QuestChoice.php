<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\ArrayDataCollection;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;

class QuestChoice extends BaseRecord
{
    private QuestObjective $objective;
    private int $number;

    public function __construct(QuestObjective $objective, int $number, ArrayDataCollection $attribs)
    {
        $this->number = $number;
        $this->objective = $objective;
        parent::__construct($attribs);
    }

    public function getLabel() : string
    {
        return 'Choice #'.$this->number;
    }

    public function getObjective() : QuestObjective
    {
        return $this->objective;
    }

    protected function registerAttributes() : void
    {
        $group = $this->attributeManager->addGroup('settings', 'Settings');

        $group->registerString('Description', 'Description');

        $group->registerInt('NextObjectiveID', 'Next objective ID');
    }

    public function getDescription() : string
    {
        return $this->attributes->getString('Description');
    }
}
