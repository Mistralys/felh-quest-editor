<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\DataTypes;

use function AppLocalize\t;

class RegularUnits extends BaseUnitCollection
{
    public function getCollectionLabel() : string
    {
        return t('Regular units');
    }

    protected function registerItems() : void
    {
        $this
            ->registerItem('Umberdroth', 'Umberdroth')
            ->registerItem('FireElemental', 'Fire elemental')
            ->registerItem('EarthElemental', 'Earth elemental')
            ->registerItem('AirElemental', 'Air elemental')
            ->registerItem('SwarmSpider', 'Swarm spider')
            ->registerItem('Wolf', 'Wolf')
            ->registerItem('Shewolf', 'She-Wolf')
            ->registerItem('Butcherman', 'Butcherman')
            ->registerItem('ClambercoilDragon', 'Clambercoil dragon')
            ->registerItem('StormDragon', 'Storm dragon')
            ->registerItem('BoneOgre', 'Bone ogre')
            ->registerItem('Waerloga', 'Warlord Waerloga (Quest of mastery)')
            ->registerItem('Ignys', 'Ignys')
            ->registerItem('Naja', 'Naja')
            ->registerItem('ScrapGolem', 'Scrap golem')
            ->registerItem('AssassinDemon', 'Assassin demon')
            ->registerItem('AshwakeDragon', 'Ashwake dragon')
            ->registerItem('Ophidian', 'Ophidian')
            ->registerItem('HoarderSpider', 'Hoarder spider')
            ->registerItem('TrollWarrior', 'Troll warrior')
            ->registerItem('Hunter', 'Hunter')
            ->registerItem('TrollShaman', 'Troll shaman')
            ->registerItem('ObsidianGolem', 'Obsidian golem');
    }
}
