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
use Mistralys\FELHQuestEditor\CommonRecords\TreasureContainerInterface;
use Mistralys\FELHQuestEditor\CommonRecords\TreasureContainerTrait;
use Mistralys\FELHQuestEditor\DataTypes\TacticalMapLocations;
use Mistralys\FELHQuestEditor\UI\Icon;
use Mistralys\FELHQuestEditor\UI\Pages\EditQuest;
use function AppLocalize\t;
use function AppUtils\sb;

class Quest extends BaseRecord implements TreasureContainerInterface
{
    use TreasureContainerTrait;

    public const TAG_NAME = 'QuestDef';

    public const TAG_TACTICAL_MAP_ID = 'PrefQuestLoc';
    public const TAG_DESCRIPTION = 'Description';
    public const TAG_DISPLAY_NAME = 'DisplayName';
    public const TAG_SHORT_TEXT_ACCEPT = 'ShortTextAccept';
    public const TAG_SHORT_TEXT_DENY = 'ShortTextDeny';
    public const TAG_QUEST_CLASS = 'QuestClass';
    public const TAG_REWARD_TEXT = 'RewardText';
    public const TAG_REPEATABLE = 'Repeatable';
    public const TAG_ALLOW_QUEST_REJECTION = 'AllowQuestRejection';

    /**
     * @var QuestObjective[]
     */
    private array $objectives = array();
    private FileInfo $sourceFile;
    private RecordGroup $objectivesGroup;
    private RecordGroup $treasureGroup;

    public function __construct(ArrayDataCollection $attribs, FileInfo $sourceFile)
    {
        $this->sourceFile = $sourceFile;

        parent::__construct($attribs);
    }

    protected function registerAttributes() : void
    {
        $texts = $this->attributeManager
            ->addGroup('texts', t('Texts'))
            ->setIcon(UI::icon()->texts());

        $texts->registerString(self::TAG_DISPLAY_NAME, t('Display name'))
            ->setRequired();

        $texts->registerMultiLineString(self::TAG_DESCRIPTION, self::TAG_DESCRIPTION)
            ->setRequired();

        $texts->registerString(self::TAG_SHORT_TEXT_ACCEPT, t('Accept text'))
            ->setRequired();

        $texts->registerString(self::TAG_SHORT_TEXT_DENY, t('Deny text'));

        $texts->registerString(self::TAG_REWARD_TEXT, t('Reward text'));

        $general = $this->attributeManager
            ->addGroup('settings', t('Settings'))
            ->setIcon(UI::icon()->settings());

        $general->registerEnum(self::TAG_QUEST_CLASS, t('Quest class'))
            ->addEnumItem('Minor', t('Minor quest'))
            ->addEnumItem('Major', t('Major quest'));

        $general->registerEnum('SpawnRating', t('Spawn rating'))
            ->addEnumItem('1', '1 - Trivial quests, e.g. Bacco')
            ->addEnumItem('2', '2 - ')
            ->addEnumItem('3', '3 - Substantial')
            ->addEnumItem('4', '4 - Major')
            ->addEnumItem('5', '5 - Essential, e.g. the quest of mastery')
            ->setDescription('Sets the importance of the quest.')
            ->setRequired();

        $general->registerBool('IsStartingPointQuest', t('Is starting point?'))
            ->setRequired();

        $general->registerBool(self::TAG_ALLOW_QUEST_REJECTION, t('Can be rejected?'))
            ->setRequired();

        $general->registerBool('AICanGoOnQuest', t('AI compatible?'))
            ->setDefault('0')
            ->setDescription(t('Whether the AI players can go on this quest.'));

        $general->registerBool(self::TAG_REPEATABLE, t('Is repeatable?'))
            ->setRequired()
            ->setDescription(t('Whether the quest can happen several times in the same game.'));

        $general->registerEnum(self::TAG_TACTICAL_MAP_ID, t('Tactical map'))
            ->addDataCollection(TacticalMapLocations::create())
            ->setDescription(t('Selects the map to use for the tactical battle.'));


        // ------------------------------------------------------------
        // GRAPHICS
        // ------------------------------------------------------------

        $graphics = $this->attributeManager
            ->addGroup('graphics', t('Graphics'))
            ->setIcon(UI::icon()->graphics());

        $graphics->registerMedallion('RewardImage', t('Reward image'));

        $graphics->registerQuestImage('Image', t('Quest image'));

        $graphics->registerString('FullscreenMovie', 'Fullscreen movie')
            ->setDescription(t('Relative path to a %1$s movie file.', sb()->code('.bik')));

        // ------------------------------------------------------------
        // TRIGGER
        // ------------------------------------------------------------

        $trigger = $this->attributeManager
            ->addGroup('trigger', t('Trigger'))
            ->setIcon(UI::icon()->trigger());

        $trigger->registerEnum('TriggerType', t('Trigger type'))
            ->addEnumItem('TurnNumber', t('Turn number'))
            ->addEnumItem('QuestLocation', t('Quest location'));

        // QuestLocation:
        // - Always with TriggerOrigin: EventLocation.
        //
        // TurnNumber:
        // - Always with TriggerData: Turn number,
        // - Sometimes with TriggerOrigin: EventLocation.
        // - With TriggerChance and PostTriggerChance

        $trigger->registerEnum('TriggerOrigin', t('Trigger origin'))
            ->addEnumItem('EventLocation', t('Event location'));

        // Turn number for trigger type "TurnNumber"
        $trigger->registerString('TriggerData', t('Trigger data'));

        $trigger->registerInt('TriggerChance', t('Trigger chance'))
            ->setDescription(t('Use %1$s or higher to ensure it will be triggered.', sb()->code('1000')));

        $trigger->registerInt('PostTriggerChance', t('Post trigger chance'));

        $this->objectivesGroup = $this->attributeManager
            ->addRecordGroup('objectives', t('Objectives'))
            ->setIcon(UI::icon()->objective())
            ->setRecordsAddable('objective')
            ->setDescription(t('List of objectives that can be fulfilled by this quest.'));

        $this->treasureGroup = $this->attributeManager
            ->addRecordGroup('treasure', t('Treasure'))
            ->setIcon(UI::icon()->treasure());
    }

    public function getTreasureGroup() : RecordGroup
    {
        return $this->treasureGroup;
    }

    /**
     * @param array<string,string> $params
     * @return string
     */
    public function getURLEdit(array $params=array()) : string
    {
        $params[QuestsCollection::REQUEST_VAR_QUEST_ID] = $this->getInternalName();

        return UI::getPageURL(EditQuest::URL_NAME, $params);
    }

    public function getSourceFile() : FileInfo
    {
        return $this->sourceFile;
    }

    public function getInternalName() : string
    {
        return $this->attributes->getString('InternalName');
    }

    public function isRepeatable() : bool
    {
        return $this->attributes->getBool(self::TAG_REPEATABLE);
    }

    public function getLabel() : string
    {
        return $this->getDisplayName();
    }

    public function getIcon() : ?Icon
    {
        return UI::icon()->quest();
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
        $this->objectivesGroup->addRecord($objective);
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
