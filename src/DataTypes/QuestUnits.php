<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\DataTypes;

use function AppLocalize\t;

class QuestUnits extends BaseUnitCollection
{
    public function getCollectionLabel() : string
    {
        return t('Quest units');
    }

    protected function registerItems() : void
    {
        $this
            ->registerItem('EbbenWolf', 'Ebben wolf')
            ->registerItem('SkeletonC_Company', 'Company of skeletons')
            ->registerItem('Unit_Juggernaut_Ongr', 'Escaped juggernaut')
            ->registerItem('Unit_Quest_Archer', 'Archer')
            ->registerItem('Unit_Quest_Farmgirl', 'Farm girl')
            ->registerItem('Unit_Quest_Noblewoman', 'Noblewoman')
            ->registerItem('Quest_Spearman', 'Spearman')
            ->registerItem('Unit_Quest_Pioneer_Krax', 'Pioneer (Krax)');
    }
}
