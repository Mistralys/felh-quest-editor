<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\DataTypes;

use Mistralys\FELHQuestEditor\DataTypes\TacticalMapLocations\TacticalMapLocation;

/**
 * @method TacticalMapLocation[] getAll()
 */
class TacticalMapLocations extends BaseDataTypeCollection
{
    private static ?TacticalMapLocations $instance = null;

    public static function create() : TacticalMapLocations
    {
        if (!isset(self::$instance))
        {
            self::$instance = new TacticalMapLocations();
        }

        return self::$instance;
    }

    protected function getDataTypeClass() : string
    {
        return TacticalMapLocation::class;
    }

    protected function registerItems() : void
    {
        $this
            ->registerItem('QuestLoc_Ruin', 'Ruin')
            ->registerItem('QuestLoc_Inn1', 'Inn 1')
            ->registerItem('QuestLoc_Inn2', 'Inn 2')
            ->registerItem('QuestLoc_Graveyard', 'Graveyard')
            ->registerItem('QuestLoc_Palisade', 'Palisade')
            ->registerItem('QuestLoc_Utheron', 'Utheron')
            ->registerItem('QuestLoc_RefugeeCamp', 'Refugee camp')
            ->registerItem('QuestLoc_Hut', 'Hut')
            ->registerItem('QuestLoc_Camp', 'Camp')
            ->registerItem('QuestLoc_StoneRing', 'Stone ring')
            ->registerItem('QuestLoc_Cave', 'Cave')
            ->registerItem('QuestLoc_Shard', 'Shard')
            ->registerItem('QuestLoc_Dungeon', 'Dungeon')
            ->registerItem('QuestLoc_Arena', 'Arena')
            ->registerItem('QuestLoc_Gate1', 'Gate 1')
            ->registerItem('QuestLoc_Gate2', 'Gate 2')
            ->registerItem('QuestLoc_Gate3', 'Gate 3')
            ->registerItem('QuestLoc_Dragon1', 'Dragon 1')
            ->registerItem('QuestLoc_Dragon2', 'Dragon 2')
            ->registerItem('QuestLoc_Dragon3', 'Dragon 3');
    }
}
