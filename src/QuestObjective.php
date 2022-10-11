<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\ArrayDataCollection;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\AttributeHandling\RecordGroup;

class QuestObjective extends BaseRecord
{
    /**
     * @var QuestChoice[]
     */
    private array $choices = array();
    private Quest $quest;
    private RecordGroup $choicesAttribute;

    public function __construct(Quest $quest, ArrayDataCollection $attribs)
    {
        $this->quest = $quest;
        parent::__construct($attribs);
    }

    protected function registerAttributes() : void
    {
        $settings = $this->attributeManager
            ->addGroup('settings', 'Settings')
            ->setIcon(UI::icon()->settings());

        $settings->registerString('ObjectiveID', 'Objective ID')
            ->setRequired();

        $settings->registerString('NextObjectiveID', 'Next objective ID');

        $settings->registerString('ChoiceText', 'Choice text');

        $this->choicesAttribute = $this->attributeManager
            ->addRecordGroup('choices', 'Choices')
            ->setIcon(UI::icon()->choices())
            ->setRecordsAddable('choice');
    }

    public function getLabel() : string
    {
        return 'Objective #'.$this->getID();
    }

    public function getID() : int
    {
        return $this->attributes->getInt('ObjectiveID');
    }

    public function getNextObjectiveID() : int
    {
        return $this->attributes->getInt('NextObjectiveID');
    }

    public function addChoice(QuestChoice $choice) : void
    {
        $this->choices[] = $choice;
        $this->choicesAttribute->addRecord($choice);
    }

    public function getChoices() : array
    {
        return $this->choices;
    }
}
