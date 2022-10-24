<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\ArrayDataCollection;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\AttributeHandling\RecordGroup;
use Mistralys\FELHQuestEditor\CommonRecords\Encounter;
use Mistralys\FELHQuestEditor\UI\Icon;
use function AppLocalize\t;
use function AppUtils\sb;

class QuestChoice extends BaseRecord
{
    public const TAG_NAME = 'QuestChoiceDef';

    private QuestObjective $objective;
    private int $number;
    private ?Encounter $encounter = null;
    private RecordGroup $encounterAttribute;

    public function __construct(QuestObjective $questObjective, int $number, ArrayDataCollection $attribs)
    {
        $this->number = $number;
        $this->objective = $questObjective;
        parent::__construct($attribs);

        $this->subLabel = $this->getDescription();
    }

    public function getLabel() : string
    {
        return t('Choice %1$s', '#'.$this->number);
    }

    public function getIcon() : ?Icon
    {
        return UI::icon()->choices();
    }

    public function getObjective() : QuestObjective
    {
        return $this->objective;
    }

    protected function registerAttributes() : void
    {
        $group = $this->attributeManager->addGroupSettings();

        $group->registerString('Description', 'Description')
            ->setRequired();

        $group->registerInt('NextObjectiveID', 'Next objective ID');

        $group->registerBool('AutoSelectChoice', t('Auto select choice'));

        $this->encounterAttribute = $this->attributeManager->addRecordGroup('encounter', t('Encounter'))
            ->setMaxRecords(1)
            ->setMinRecords(1)
            ->setIcon(UI::icon()->encounter());
    }

    public function getDescription() : string
    {
        return $this->attributes->getString('Description');
    }

    public function setEncounter(Encounter $encounter) : self
    {
        $this->encounter = $encounter;
        $this->encounterAttribute->addRecord($encounter);
        return $this;
    }

    public function getEncounter() : ?Encounter
    {
        return $this->encounter;
    }
}
