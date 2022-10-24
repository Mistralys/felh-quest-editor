<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\CommonRecords;

use AppUtils\ArrayDataCollection;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\UI;
use Mistralys\FELHQuestEditor\UI\Icon;
use function AppLocalize\t;
use function AppUtils\sb;

class UnitInstance extends BaseRecord
{
    public const TAG_NAME = 'UnitInstance';

    private Encounter $encounter;

    public function __construct(Encounter $encounter, ArrayDataCollection $attribs)
    {
        $this->encounter = $encounter;
        parent::__construct($attribs);
    }

    public function getEncounter() : Encounter
    {
        return $this->encounter;
    }

    protected function registerAttributes() : void
    {
        $settings = $this->attributeManager->addGroupSettings();

        $settings->registerUnitsEnum('UnitType', t('Unit type'))
            ->setRequired();

        $settings->registerEnum('UnitSubclassType', t('Unit class'))
            ->setDescription(t(
                'When choosing a champion unit, make sure to select %1$s here.',
                sb()->quote(t('Champion'))
            ))
            ->addEnumItem('Champion', t('Champion'));

        $settings->registerInt('Level', t('Level'))
            ->setRequired()
            ->getRawValue();

        $settings->registerString('UnitName', t('Display name'))
            ->setDescription(t('Optional custom name for the unit.'));

        $settings->registerEnum('UnitGroupingType', t('Grouping type'))
            ->setDescription(sb()
                ->t('By default, a single unit of the selected type is spawned.')
                ->t('Choose %1$s to spawn a full party with several units (in the same tile).', t('Party'))
                ->noteBold()
                ->t('Do not use this with a champion unit.')
            )
            ->addEnumItem('UnitGroupingType_Party', t('Party'));
    }

    public function getLabel() : string
    {
        return t('Unit instance');
    }

    public function getIcon() : ?Icon
    {
        return UI::icon()->units();
    }
}
