<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\DataTypes;

use Mistralys\FELHQuestEditor\DataTypes\GoodieHuts\GoodieHut;
use Mistralys\FELHQuestEditor\DataTypes\PlayerAbilities\PlayerAbility;
use function AppLocalize\t;

/**
 * @method GoodieHut[] getAll()
 */
class PlayerAbilities extends BaseDataTypeCollection
{
    private static ?PlayerAbilities $instance = null;

    public static function create() : PlayerAbilities
    {
        if (!isset(self::$instance))
        {
            self::$instance = new PlayerAbilities();
        }

        return self::$instance;
    }

    public function getCollectionLabel() : string
    {
        return t('Player abilities');
    }

    protected function getDataTypeClass() : string
    {
        return PlayerAbility::class;
    }

    protected function registerItems() : void
    {
        $this
            ->registerItem('A_Prestige', 'Prestige');
    }
}
