<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\DataTypes;

use Mistralys\FELHQuestEditor\DataTypes\GoodieHuts\GoodieHut;
use function AppLocalize\t;

/**
 * @method GoodieHut[] getAll()
 */
class GoodieHuts extends BaseDataTypeCollection
{
    private static ?GoodieHuts $instance = null;

    public static function create() : GoodieHuts
    {
        if(!isset(self::$instance)) {
            self::$instance = new GoodieHuts();
        }

        return self::$instance;
    }

    public function getCollectionLabel() : string
    {
        return t('Goodie huts');
    }

    protected function getDataTypeClass() : string
    {
        return GoodieHut::class;
    }

    protected function registerItems() : void
    {
        $this
            ->registerItem('GH_Quest_Workshop', 'Workshop')
            ->registerItem('GH_Quest_AbandonedVillage', 'Abandoned village')
            ->registerItem('GH_Quest_Tomb', 'Tomb')
            ->registerItem('Treasure_RuinedHouse', 'Ruined house')
            ->registerItem('GH_Quest_Cave', 'Cave')
            ->registerItem('GH_Quest_Ruins', 'Ruins')
            ->registerItem('GH_Quest_Troll', 'Troll camp')
            ->registerItem('Treasure_Caravan', 'Treasure caravan')
            ->registerItem('Treasure_CrumbledStatue', 'Crumbled statue')
            ->registerItem('GH_Quest_LostHorse', 'Lost horse')
            ->registerItem('GH_Quest_Garrote', 'Garrotte lair')
            ->registerItem('Lair_Ophidian', 'Ophidian lair')
            ->registerItem('Lair_ObsidianGolem', 'Obsidian golem lair')
            ->registerItem('GH_Quest_StormDragonLair', 'Storm dragon lair')
            ->registerItem('GH_Quest_BlackWidow', 'Spider nest')
            ->registerItem('GH_Quest_RavenousHarridan', 'Necromancer home')
            ->registerItem('Lair_RavenousHarridan', 'Ravenous harridan lair')
            ->registerItem('GH_Quest_DemonTomb', 'Demon\'s tomb')
            ->registerItem('GH_ForgottenTemple', 'Forgotten temple')
            ->registerItem('GH_BanditCamp', 'Bandit camp')
            ->registerItem('GH_Quest_Field', 'Tilda field')
            ->registerItem('GH_Quest_Estate', 'Estate')
            ->registerItem('GH_Quest_GreatWolf', 'Great wolf lair')
            ->registerItem('GH_Quest_RatNest', 'Rat nest')
            ->registerItem('GH_Quest_Chest', 'Treasure chest')
            ->registerItem('GH_NajaDen', 'Naja lair')
            ->registerItem('GH_Quest_SwampTree', 'Swamp lair')
            ->registerItem('GH_Quest_WildingShaman', 'Wilding camp')
            ->registerItem('GH_Quest_DarklingShaman', 'Darkling camp')
            ->registerItem('GH_Quest_Battlefield', 'Ancient battlefield')
            ->registerItem('GH_Quest_IceElemental', 'Frozen fields')
            ->registerItem('GH_Quest_FireElemental', 'Burnt ground')
            ->registerItem('GH_Quest_Troll', 'Troll camp');
    }
}
