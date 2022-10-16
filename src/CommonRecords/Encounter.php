<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\CommonRecords;

use AppUtils\ArrayDataCollection;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\AttributeHandling\RecordGroup;
use Mistralys\FELHQuestEditor\DataTypes\TacticalMapLocations;
use Mistralys\FELHQuestEditor\QuestChoice;
use Mistralys\FELHQuestEditor\UI;
use Mistralys\FELHQuestEditor\UI\Icon;
use function AppLocalize\t;
use function AppUtils\sb;

class Encounter extends BaseRecord
{
    private QuestChoice $choice;
    private RecordGroup $unitInstanceAttribute;

    /**
     * @var UnitInstance[]
     */
    private array $unitInstances = array();

    public function __construct(QuestChoice $choice, ArrayDataCollection $attribs)
    {
        $this->choice = $choice;
        parent::__construct($attribs);
    }

    public function getChoice() : QuestChoice
    {
        return $this->choice;
    }

    public function addUnitInstance(UnitInstance $unit) : self
    {
        $this->unitInstances[] = $unit;
        $this->unitInstanceAttribute->addRecord($unit);

        return $this;
    }

    protected function registerAttributes() : void
    {
        $settings = $this->attributeManager->addGroupSettings();

        $settings->registerBool('WillRespawn', t('Will respawn?'));
        $settings->registerBool('ForceLeaderOnly', t('Sovereign only?'))
            ->setDescription('Whether the sovereign will join the encounter alone, without their armies.');

        $settings->registerInt('Liklihood', t('Likelihood'))
            ->setDescription(t('Percentage value (without the percent sign, e.g. %1$s).', sb()->code(100)))
             ->setRequired();

        $settings->registerString('BattleIdentifier', t('Battle identifier'))
            ->setDescription(sb()
                ->t(
                    'A freeform name to identify the encounter, e.g. %1$s.',
                    sb()->code('BattleName')
                )
                ->t(
                    'It can be used to reference this encounter in a %1$s quest condition type.',
                    sb()->quote(t('Finish a battle'))
                )
            );

        $settings->registerInt('WanderingRadius', t('Wandering radius'));

        $settings->registerEnum('TacticalMap', t('Tactical map'))
            ->addDataCollection(TacticalMapLocations::create());

        $this->unitInstanceAttribute = $this->attributeManager->addRecordGroup('UnitInstance', t('Spawned units'))
            ->setMinRecords(1)
            ->setIcon(UI::icon()->units());
    }

    public function getLabel() : string
    {
        return t('Encounter');
    }

    public function getIcon() : ?Icon
    {
        return UI::icon()->encounter();
    }

    /**
     * @return UnitInstance[]
     */
    public function getUnitInstances() : array
    {
        return $this->unitInstances;
    }
}
