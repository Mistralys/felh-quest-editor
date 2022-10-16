<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\ArrayDataCollection;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\AttributeHandling\RecordGroup;
use Mistralys\FELHQuestEditor\CommonRecords\TreasureContainerInterface;
use Mistralys\FELHQuestEditor\CommonRecords\TreasureContainerTrait;
use Mistralys\FELHQuestEditor\UI\Icon;
use function AppLocalize\t;
use function AppUtils\sb;

class QuestObjective extends BaseRecord implements TreasureContainerInterface
{
    use TreasureContainerTrait;

    /**
     * @var QuestChoice[]
     */
    private array $choices = array();

    /**
     * @var QuestCondition[]
     */
    private array $conditions = array();

    private Quest $quest;
    private RecordGroup $choicesAttribute;
    private RecordGroup $conditionsAttribute;
    private RecordGroup $treasureAttribute;

    public function __construct(Quest $quest, ArrayDataCollection $attribs)
    {
        $this->quest = $quest;
        parent::__construct($attribs);
    }

    public function getTreasureGroup() : RecordGroup
    {
        return $this->treasureAttribute;
    }

    protected function registerAttributes() : void
    {
        $settings = $this->attributeManager
            ->addGroup('settings', 'Settings')
            ->setIcon(UI::icon()->settings());

        $settings->registerString('ObjectiveID', 'Objective ID')
            ->setRequired();

        $settings->registerString('NextObjectiveID', 'Next objective ID');

        $settings->registerBool('PropupObjectiveMsg', 'Show objective popup?');

        $texts = $this->attributeManager
            ->addGroup('texts', 'Texts')
            ->setIcon(UI::icon()->texts());

        $texts->registerMultiLineString('ChoiceText', t('Choices text'));

        $graphics = $this->attributeManager->addGroupGraphics();

        $graphics->registerMedallion('ChoiceMedallion', t('Choices medallion'));

        $graphics->registerEnum('ChoiceMedallionFrame', t('Choices medallion frame'))
            ->addEnumItem('Medallion_Frame_17.png', t('Default frame'));

        $this->conditionsAttribute = $this->attributeManager
            ->addRecordGroup('conditions', 'Conditions')
            ->setIcon(UI::icon()->conditions())
            ->setRecordsAddable('conditions');

        $this->choicesAttribute = $this->attributeManager
            ->addRecordGroup('choices', 'Choices')
            ->setIcon(UI::icon()->choices())
            ->setRecordsAddable('choice');

        $this->treasureAttribute = $this->attributeManager
            ->addRecordGroup('treasure', 'Treasure')
            ->setIcon(UI::icon()->treasure());
    }

    public function getLabel() : string
    {
        return t('Objective %1$s', '#'.$this->getID());
    }

    public function getSubLabel() : string
    {
        $nextObjectiveID = $this->getNextObjectiveID();

        if($nextObjectiveID > 0)
        {
            return (string)sb()
                ->html('<span class="muted">')
                ->add(UI::icon()->nextObjective()->addClass('objective-progression-icon'))
                ->add('#'.$nextObjectiveID)
                ->html('</span>');
        }

        return (string)sb()
            ->html('<span class="muted">')
            ->add(UI::icon()->lastObjective()->addClass('objective-progression-icon'))
            ->t('End')
            ->html('</span>');
    }

    public function getIcon() : ?Icon
    {
        return UI::icon()->objective();
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

    public function addCondition(QuestCondition $condition) : void
    {
        $this->conditions[] = $condition;
        $this->conditionsAttribute->addRecord($condition);
    }

    public function getConditions() : array
    {
        return $this->conditions;
    }

    public function getChoices() : array
    {
        return $this->choices;
    }
}
