<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\DataTypes;

use function AppLocalize\t;

class ChampionUnits extends BaseUnitCollection
{
    public function getCollectionLabel() : string
    {
        return t('Champion units');
    }

    protected function registerItems() : void
    {
        $this
            ->registerItem('Champion_Araine', 'Araine')
            ->registerItem('Champion_Daxus', 'Daxus')
            ->registerItem('Champion_Ascian', 'Ascian')
            ->registerItem('Champion_Haitan', 'Haitan')
            ->registerItem('Champion_Mausolos', 'Mausolos')
            ->registerItem('Champion_Neville', 'Neville')
            ->registerItem('Champion_Listrid', 'Listrid')
            ->registerItem('Champion_Baylur', 'Baylur')
            ->registerItem('Champion_Waerloga', 'Waerloga')
            ->registerItem('Champion_Arneson', 'Arneson')
            ->registerItem('Champion_Bacco', 'Bacco')
            ->registerItem('Champion_Huhrus', 'Huhrus')
            ->registerItem('Champion_Pralius', 'Pralius');
    }
}
