<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\ArrayDataCollection;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\AttributeHandling\RecordGroup;
use Mistralys\FELHQuestEditor\CommonRecords\Objective;
use Mistralys\FELHQuestEditor\UI\Icon;
use function AppLocalize\t;
use function AppUtils\sb;

class QuestCondition extends BaseRecord
{
    public const TAG_ID = 'ID';
    public const TAG_TEXT_DATA = 'TextData';
    public const TAG_NUMERIC_DATA = 'NumericData';
    public const TAG_FLAG = 'Flag';
    public const TAG_TYPE = 'Type';

    private QuestObjective $questObjective;
    private Objective $objective;
    private RecordGroup $objectiveAttribute;

    public function __construct(QuestObjective $questObjective, ArrayDataCollection $attribs)
    {
        $this->questObjective = $questObjective;
        parent::__construct($attribs);
    }

    public function setObjective(Objective $objective) : self
    {
        $this->objective = $objective;
        $this->objectiveAttribute->addRecord($objective);
        return $this;
    }

    protected function registerAttributes() : void
    {
        $this->objectiveAttribute = $this->attributeManager
            ->addRecordGroup('objective', t('Objective'))
            ->setMinRecords(1)
            ->setMaxRecords(1)
            ->setIcon(UI::icon()->objective());

        $settings = $this->attributeManager->addGroupSettings();

        $settings->registerInt(self::TAG_ID, t('ID'))
            ->setRequired()
            ->setDefault('0');

        $settings->registerEnum('Class', t('Outcome'))
            ->addEnumItem('Success', t('Success'))
            ->addEnumItem('Failure', t('Failure'))
            ->addEnumItem('ChoiceUnlock', t('Unlock a choice'))
            ->setDescription(t('After choosing a possible outcome, choose what will cause it to be selected.'));

        $settings->registerEnum(self::TAG_TYPE, t('Caused by'))
            ->addEnumItem('UnitEntersQuestLocation', t('Unit enters quest location'))
            ->addEnumItemR('CheckForResource', t('Check for a resource'))
                ->addDependency(
                    self::TAG_TEXT_DATA,
                    (string)sb()
                        ->t('Enter the resource type name:')
                        ->ul(array(
                            'Gold',
                            'Mana',
                            'RefinedCrystal'
                        ))
                )
                ->done()
            ->addEnumItemR('ClearGoodieHut', t('Clear a goodie hut'))
                ->addDependency(self::TAG_TEXT_DATA, t('Enter the goodie hut type.'))
                ->addDependency(self::TAG_FLAG, t('Select the flag %1$s.', sb()->quote(t('Reveal target'))))
                ->done()
            ->addEnumItemR('BattleFinished', t('Finish a battle'))
                ->addDependency(
                    self::TAG_TEXT_DATA,
                    sb()
                        ->t('Requires an encounter.')
                        ->t('Enter the encounter\'s custom battle identifier here.')
                )
                ->done()
            ->addEnumItem('CheckForItem', t('Check for an item'))
            ->addEnumItem('UnitKilled', t('A unit is killed'));

        $settings->registerEnum(self::TAG_FLAG, t('Flag'))
            ->addEnumItem('RevealTarget', t('Reveal target'));

        $settings->registerString(self::TAG_TEXT_DATA, (string)sb()->t('Value:')->t('String'));

        $settings->registerString(self::TAG_NUMERIC_DATA, (string)sb()->t('Value:')->t('Integer'));
    }

    public function getLabel() : string
    {
        return t('Condition #%1$s', $this->getID());
    }

    public function getIcon() : ?Icon
    {
        return UI::icon()->conditions();
    }

    public function getID() : int
    {
        return $this->attributes->getInt(self::TAG_ID);
    }
}
