<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\ArrayDataCollection;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\AttributeHandling\RecordGroup;
use Mistralys\FELHQuestEditor\CommonRecords\KnownQuests;
use Mistralys\FELHQuestEditor\CommonRecords\Objective;
use Mistralys\FELHQuestEditor\UI\Icon;
use function AppLocalize\t;
use function AppUtils\sb;

class QuestCondition extends BaseRecord
{
    public const TAG_NAME = 'QuestConditionDef';

    public const TAG_ID = 'ID';
    public const TAG_TEXT_DATA = 'TextData';
    public const TAG_MORE_TEXT_DATA = 'MoreTextData';
    public const TAG_NUMERIC_DATA = 'NumericData';
    public const TAG_FLAG = 'Flag';
    public const TAG_TYPE = 'Type';
    public const TAG_CLASS = 'Class';
    public const TAG_COMPLETION_TEXT = 'CompletionText';
    public const TAG_FACTION = 'Faction';

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

        $settings->registerEnum(self::TAG_CLASS, t('Outcome'))
            ->addEnumItem('Success', t('Success'))
            ->addEnumItemR('Failure', t('Failure'))
                ->addDependency(
                    self::TAG_TYPE,
                    sb()
                        ->t('Choose what will cause the condition to fail.')
                        ->t(
                            'For example, check if a specific unit has been killed with the %1$s cause.',
                            sb()->quote(t('A unit is killed'))
                        )
                )
                ->done()
            ->addEnumItemR('ChoiceUnlock', t('Unlock choice'))
                ->addDependency(
                    self::TAG_TYPE,
                    sb()
                        ->t('Choose what will cause the choice to be unlocked.')
                        ->t(
                            'For example, check for a specific amount of mana in the player\'s mana pool, by using the %1$s cause.',
                            sb()->quote(t('Check for a resource'))
                        )
                )
                ->done()
            ->setDescription(t('After choosing a possible outcome, choose what will cause it to be selected.'));

        $settings->registerEnum(self::TAG_TYPE, t('Caused by'))
            ->addEnumItemR('UnitEntersQuestLocation', t('Unit enters quest location'))
                ->addRelatedQuest(KnownQuests::QUEST_ALCHEMISTS_OFFER)
                ->addRelatedQuest(KnownQuests::QUEST_CLOAK_OF_STARS)
                ->addRelatedQuest(KnownQuests::QUEST_MAUSOLOS)
                ->done()
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
                ->addRelatedQuest(KnownQuests::QUEST_ARCTIC_WOLF_CLOAK, t('Example with gold'))
                ->addRelatedQuest(KnownQuests::QUEST_THREE_SONS, t('Example with mana'))
                ->addRelatedQuest(KnownQuests::QUEST_CLOAK_OF_STARS, t('Example with crystals'))
                ->done()
            ->addEnumItemR('ClearGoodieHut', t('Clear a goodie hut'))
                ->addDependency(self::TAG_TEXT_DATA, t('Enter the goodie hut type.'))
                ->addDependency(self::TAG_FLAG, t('Select the flag %1$s.', sb()->quote(t('Reveal target'))))
                ->addRelatedQuest(KnownQuests::QUEST_ALCHEMISTS_OFFER)
                ->addRelatedQuest(KnownQuests::QUEST_ALCHEMY)
                ->addRelatedQuest(KnownQuests::QUEST_MAUSOLOS)
                ->addRelatedQuest(KnownQuests::QUEST_POTION_OF_RESTORATION)
                ->done()
            ->addEnumItemR('BattleFinished', t('Finish a battle'))
                ->addDependency(
                    self::TAG_TEXT_DATA,
                    sb()
                        ->t('Requires an encounter.')
                        ->t('Enter the encounter\'s custom battle identifier here.')
                )
                ->addRelatedQuest(KnownQuests::QUEST_ALCHEMISTS_OFFER)
                ->addRelatedQuest(KnownQuests::QUEST_BACCO)
                ->addRelatedQuest(KnownQuests::QUEST_ALCHEMISTS_OFFER)
                ->addRelatedQuest(KnownQuests::QUEST_ALCHEMY)
                ->addRelatedQuest(KnownQuests::QUEST_EXILE_OF_ICE)
                ->done()
            ->addEnumItemR('CheckForItem', t('Check for an item'))
                ->addDependency(self::TAG_TEXT_DATA, t('Enter the item type ID.'))
                ->addDependency(self::TAG_NUMERIC_DATA, t('Enter the amount of items required.'))
                ->addDependency(self::TAG_COMPLETION_TEXT, t('Enter a success message to display.'), true)
                ->addRelatedQuest(KnownQuests::QUEST_ARCTIC_WOLF_CLOAK)
                ->addRelatedQuest(KnownQuests::QUEST_POTION_OF_RESTORATION)
                ->addRelatedQuest(KnownQuests::QUEST_RATS_IN_THE_RUINS)
                ->addRelatedQuest(KnownQuests::QUEST_HAITAN_STAFF_OF_BANISHING)
                ->addRelatedQuest(KnownQuests::QUEST_EXILE_OF_ICE)
                ->done()
            ->addEnumItemR('UnitKilled', t('A unit is killed'))
                ->addDependency(self::TAG_TEXT_DATA, t('Enter the unit type ID.'))
                ->addDependency(self::TAG_NUMERIC_DATA, t('Enter the expected amount of units.'))
                ->addDependency(self::TAG_FACTION, t('Select the faction of the target unit.'))
                ->addDependency(self::TAG_COMPLETION_TEXT, t('Enter a message to show when this condition is complete.'), true)
                ->addRelatedQuest(KnownQuests::QUEST_ESCORT_NOBLE_WOMAN)
                ->addRelatedQuest(KnownQuests::QUEST_ESCORT_RAIDERS_BRIBE)
                ->addRelatedQuest(KnownQuests::QUEST_ESCORT_SLAVERS_FARM_GIRL)
                ->done();

        $settings->registerEnum(self::TAG_FLAG, t('Flag'))
            ->makeMultiple()
            ->addEnumItemR('RevealTarget', t('Reveal target'))
                ->setDescription(t('Scrolls to the target location on the map, typically a goodie hut.'))
                ->addRelatedQuest(KnownQuests::QUEST_ALCHEMISTS_OFFER)
                ->addRelatedQuest(KnownQuests::QUEST_ALCHEMY)
                ->addRelatedQuest(KnownQuests::QUEST_MAUSOLOS)
                ->done()
            ->addEnumItemR('DestroyUnit', t('Destroy a unit'))
                ->setDescription(t('Used in escort missions to remove the unit once the mission has completed.'))
                ->addRelatedQuest(KnownQuests::QUEST_ESCORT_NOBLE_WOMAN)
            ->addRelatedQuest(KnownQuests::QUEST_ESCORT_RAIDERS_BRIBE)
                ->addRelatedQuest(KnownQuests::QUEST_ESCORT_SLAVERS_FARM_GIRL)
                ->done()
            ->addEnumItemR('UnitUnescorted', t('Unit unescorted'))
                ->setDescription(t('Used in escort missions to check if the unit has been correctly escorted.'))
                ->addRelatedQuest(KnownQuests::QUEST_ESCORT_NOBLE_WOMAN)
                ->addRelatedQuest(KnownQuests::QUEST_ESCORT_RAIDERS_BRIBE)
                ->addRelatedQuest(KnownQuests::QUEST_ESCORT_SLAVERS_FARM_GIRL)
                ->done()
            ->addEnumItemR('AllConditionsMet', t('All conditions met'))
                ->setDescription(sb()->t('Used in a single quest in the whole game.')->t('Unsure if it actually works.'))
                ->addRelatedQuest(KnownQuests::QUEST_THE_OBSIDIAN_SHIELD)
                ->done();

        $settings->registerEnum(self::TAG_FACTION, t('Faction'))
            ->addEnumItemR('Player', t('Player'))
                ->setDescription(t(' Used only in escort missions, to create the unit to escort for the player\'s faction.'))
                ->addRelatedQuest(KnownQuests::QUEST_ESCORT_NOBLE_WOMAN)
                ->addRelatedQuest(KnownQuests::QUEST_ESCORT_RAIDERS_BRIBE)
                ->addRelatedQuest(KnownQuests::QUEST_ESCORT_SLAVERS_FARM_GIRL);

        $values = $this->attributeManager->addGroup('values', t('Values'))
            ->setIcon(UI::icon()->values());

        $values->registerString(self::TAG_TEXT_DATA, (string)sb()->t('Value:')->t('String'));
        $values->registerString(self::TAG_MORE_TEXT_DATA, (string)sb()->t('Value:')->t('String')->add('('.t('Additional').')'));

        $values->registerString(self::TAG_NUMERIC_DATA, (string)sb()->t('Value:')->t('Integer'));

        $texts = $this->attributeManager->addGroupTexts();

        $texts->registerString(self::TAG_COMPLETION_TEXT, t('Completion message'));
        $texts->registerString('CannotCompleteText', t('Cannot complete message'));
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
