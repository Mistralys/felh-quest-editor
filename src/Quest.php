<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\ArrayDataCollection;
use AppUtils\ClassHelper;
use AppUtils\FileHelper\FileInfo;
use AppUtils\Request;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\AttributeHandling\RecordGroup;
use Mistralys\FELHQuestEditor\UI\Pages\EditQuest;
use function AppUtils\sb;

class Quest extends BaseRecord
{
    public const TAG_TACTICAL_MAP_ID = 'PrefQuestLoc';
    public const TAG_DESCRIPTION = 'Description';
    public const TAG_DISPLAY_NAME = 'DisplayName';
    public const TAG_SHORT_TEXT_ACCEPT = 'ShortTextAccept';
    public const TAG_QUEST_CLASS = 'QuestClass';

    /**
     * @var QuestObjective[]
     */
    private array $objectives = array();
    private FileInfo $sourceFile;
    private RecordGroup $objectivesAttribute;

    public function __construct(ArrayDataCollection $attribs, FileInfo $sourceFile)
    {
        $this->sourceFile = $sourceFile;

        parent::__construct($attribs);
    }

    protected function registerAttributes() : void
    {
        $texts = $this->attributeManager
            ->addGroup('texts', 'Texts')
            ->setIcon(UI::icon()->texts());

        $texts->registerString(self::TAG_DISPLAY_NAME, 'Display name')
            ->setRequired();

        $texts->registerMultiLineString(self::TAG_DESCRIPTION, self::TAG_DESCRIPTION)
            ->setRequired();

        $texts->registerString(self::TAG_SHORT_TEXT_ACCEPT, 'Accept text')
            ->setRequired();

        $general = $this->attributeManager
            ->addGroup('settings', 'Settings')
            ->setIcon(UI::icon()->settings());

        $general->registerEnum(
            self::TAG_QUEST_CLASS,
            'Quest class',
            array(
                'Minor' => 'Minor',
                'Major' => 'Major'
            )
        );

        $general->registerEnum(
            'SpawnRating',
            'Spawn rating',
            array(
                '1' => '1 - Trivial quests, e.g. Bacco',
                '2' => '2 - ',
                '3' => '3 - Substantial',
                '4' => '4 - Major',
                '5' => '5 - Essential, e.g. the quest of mastery'
            )
        )
            ->setDescription('Sets the importance of the quest.')
            ->setRequired();

        $general->registerBool('IsStartingPointQuest', 'Is starting point?')
            ->setRequired();

        $general->registerBool('AllowQuestRejection', 'Can be rejected?')
            ->setRequired();

        $general->registerBool('AICanGoOnQuest', 'AI compatible?')
            ->setDefault('0')
            ->setDescription('Whether the AI players can go on this quest.');

        $general->registerBool('Repeatable', 'Is repeatable?')
            ->setRequired()
            ->setDescription('Whether the quest can happen several times in the same game.');

        $general->registerEnum(
            self::TAG_TACTICAL_MAP_ID,
            'Tactical map',
            array(
                'QuestLoc_Ruin' => 'Ruin',
                'QuestLoc_Inn1' => 'Inn 1',
                'QuestLoc_Inn2' => 'Inn 2',
                'QuestLoc_Graveyard' => 'Graveyard',
                'QuestLoc_Palisade' => 'Palisade',
                'QuestLoc_Utheron' => 'Utheron',
                'QuestLoc_RefugeeCamp' => 'Refugee camp',
                'QuestLoc_Hut' => 'Hut',
                'QuestLoc_Camp' => 'Camp',
                'QuestLoc_StoneRing' => 'Stone ring',
                'QuestLoc_Cave' => 'Cave',
                'QuestLoc_Shard' => 'Shard',
                'QuestLoc_Dungeon' => 'Dungeon',
                'QuestLoc_Arena' => 'Arena',
                'QuestLoc_Gate1' => 'Gate 1',
                'QuestLoc_Gate2' => 'Gate 2',
                'QuestLoc_Gate3' => 'Gate 3',
                'QuestLoc_Dragon1' => 'Dragon 1',
                'QuestLoc_Dragon2' => 'Dragon 2',
                'QuestLoc_Dragon3' => 'Dragon 3'
            )
        )
            ->setDescription('Selects the map to use for the tactical battle.');

        // ------------------------------------------------------------
        // TRIGGER
        // ------------------------------------------------------------

        $trigger = $this->attributeManager
            ->addGroup('trigger', 'Trigger')
            ->setIcon(UI::icon()->trigger());

        $trigger->registerEnum(
            'TriggerType',
            'Trigger type',
            array(
                'TurnNumber' => 'Turn number',
                'QuestLocation' => 'Quest location'
            )
        );

        // QuestLocation:
        // - Always with TriggerOrigin: EventLocation.
        //
        // TurnNumber:
        // - Always with TriggerData: Turn number,
        // - Sometimes with TriggerOrigin: EventLocation.
        // - With TriggerChance and PostTriggerChance

        $trigger->registerEnum(
            'TriggerOrigin',
            'Trigger origin',
            array(
                'EventLocation' => 'Event location'
            )
        );

        // Turn number for trigger type "TurnNumber"
        $trigger->registerString('TriggerData', 'Trigger data');

        $trigger->registerInt('TriggerChance', 'Trigger chance')
            ->setDescription(sprintf('Use %1$s to ensure it will be triggered.', sb()->code('1000')));

        $trigger->registerInt('PostTriggerChance', 'Post trigger chance');

        $this->objectivesAttribute = $this->attributeManager
            ->addRecordGroup('objectives', 'Objectives')
            ->setIcon(UI::icon()->objective())
            ->setRecordsAddable('objective')
            ->setDescription('List of objectives that can be fulfilled by this quest.');
    }

    /**
     * @param array<string,string> $params
     * @return string
     */
    public function getURLEdit(array $params=array()) : string
    {
        $params['page'] = EditQuest::URL_NAME;
        $params['quest'] = $this->getInternalName();

        return Request::getInstance()->buildURL($params);
    }

    public function getSourceFile() : FileInfo
    {
        return $this->sourceFile;
    }

    public function getInternalName() : string
    {
        return $this->attributes->getString('InternalName');
    }

    public function getLabel() : string
    {
        return $this->getDisplayName();
    }

    public function getDisplayName() : string
    {
        return $this->attributes->getString(self::TAG_DISPLAY_NAME);
    }

    public function getDescription() : string
    {
        return $this->attributes->getString(self::TAG_DESCRIPTION);
    }

    public function addObjective(QuestObjective $objective) : void
    {
        $this->objectives[] = $objective;
        $this->objectivesAttribute->addRecord($objective);
    }

    public function getTacticalMapID() : string
    {
        return $this->attributes->getString(self::TAG_TACTICAL_MAP_ID);
    }

    public function getTacticalMapLabel() : string
    {
        $attribute = ClassHelper::requireObjectInstanceOf(
            EnumAttribute::class,
            $this->attributeManager->getAttributeByName(self::TAG_TACTICAL_MAP_ID)
        );

        $id = $this->getTacticalMapID();

        if($attribute->hasValue($id))
        {
            return $attribute->getLabelByValue($this->getTacticalMapID());
        }

        return '';
    }
}
