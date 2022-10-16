<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\DataTypes;

use Mistralys\FELHQuestEditor\DataTypes\Spells\Spell;
use function AppLocalize\t;

/**
 * @method Spell[] getAll()
 */
class Spells extends BaseDataTypeCollection
{
    private static ?Spells $instance = null;

    public static function create() : Spells
    {
        if (!isset(self::$instance))
        {
            self::$instance = new Spells();
        }

        return self::$instance;
    }

    public function getCollectionLabel() : string
    {
        return t('Spells');
    }

    protected function getDataTypeClass() : string
    {
        return Spell::class;
    }

    protected function registerItems() : void
    {
        $this
            ->registerItem('ManaBlast', 'Mana blast')
            ->registerItem('ManaShield', 'Mana shield')
            ->registerItem('Confusion', 'Confusion')
            ->registerItem('VatulasDragonslayer', 'Vatula\'s dragon slayer')
            ->registerItem('CurePlague', 'Cure plague')
            ->registerItem('ShadowWorld', 'Shadow world')
            ->registerItem('Alchemy', 'Alchemy');
    }
}