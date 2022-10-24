<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\CommonRecords;

use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\DataTypes\GoodieHuts;
use Mistralys\FELHQuestEditor\UI;
use Mistralys\FELHQuestEditor\UI\Icon;
use function AppLocalize\t;
use function AppUtils\sb;

class GameModifier extends BaseRecord
{
    public const TAG_NAME = 'GameModifier';

    public const TAG_MODIFIER_TYPE = 'ModType';
    public const TAG_INTERNAL_NAME = 'InternalName';
    public const TAG_ACTION = 'Attribute';
    public const TAG_STRING_VALUE = 'StrVal';
    public const TAG_STRING_VALUE_2 = 'StrVal2';
    public const TAG_BOOLEAN_VALUE = 'BoolVal1';
    public const TAG_INTEGER_VALUE = 'Value';
    public const TAG_RADIUS = 'Radius';
    public const TAG_UNIT_CLASS = 'Unitclass';

    protected function registerAttributes() : void
    {
        $settings = $this->attributeManager->addGroupSettings();

        $settings->registerString(self::TAG_INTERNAL_NAME, t('Internal name'))
            ->setRequired();

        $actions = $this->attributeManager->addGroup('actions', t('Actions'))
            ->setIcon(UI::icon()->actions());

        $actions->registerEnum(self::TAG_MODIFIER_TYPE, t('Modifier type'))
            ->addEnumItemR('Map', t('Map modifier'))
                ->addDependencySet(t('Create a goodie hut'))
                    ->addDependency(self::TAG_STRING_VALUE, t('Enter the goodie hut identifier.'))
                    ->addDependency(self::TAG_UNIT_CLASS, t('Choose the type of hut to spawn.'))
                    ->addDependency(self::TAG_RADIUS, t('Set the tile radius in which to spawn it.'))
                    ->done()
                ->addDependencySet(t('Spawn a monster'))
                    ->addDependency(self::TAG_INTEGER_VALUE, t('Enter the unit level.'))
                    ->addDependency(self::TAG_UNIT_CLASS, t('Enter the unit identifier.'))
                    ->addDependency(self::TAG_RADIUS, t('Set the tile radius in which to spawn it.'))
                    ->done()
                ->addDependencySet(t('Create a resource'))
                    ->addDependency(self::TAG_STRING_VALUE, t('Enter the resource identifier.'))
                    ->addDependency(self::TAG_INTEGER_VALUE, t('Enter the amount of the resource to add.'))
                    ->addDependency(self::TAG_RADIUS, t('Enter the tile radius in which to spawn it.'))
                    ->done()
                ->addDependencySet(t('Create a world property'))
                    ->addDependency(self::TAG_STRING_VALUE, sb()
                        ->t('Set the property name.')
                        ->t('The only one known to have been used is %1$s:', sb()->code('TD_Groundfire'))
                        ->t('See the file %1$s.', sb()->code('data/English/Core Tiles/TD_GroundFire.xml'))
                    )
                    ->addDependency(self::TAG_INTEGER_VALUE, t('Set the value to %1$s.', sb()->code(30)))
                    ->addDependency(self::TAG_RADIUS, t('Set the radius to %1$s.', sb()->code('-1')))
                    ->done()
                ->addDependencySet(t('Block/unblock a tile'))
                    ->addDependency(self::TAG_BOOLEAN_VALUE, sb()
                        ->t('Set the block state:')
                        ->t('Active = blocked, inactive = unblocked.')
                    )
                    ->addDependency(self::TAG_RADIUS, t('Set this to %1$s to block the current tile.', sb()->code('1')))
                    ->done()
                ->done()
            ->addEnumItem('Player', t('Player modifier'))
            ->addEnumItemR('Unit', t('Unit modifier'))
                ->addDependencySet(t('Give experience'))
                    ->addDependency(self::TAG_STRING_VALUE, t('Enter the amount of experience to give.'))
                    ->done()
                ->addDependencySet(t('Add a unit to current army'))
                    ->addDependency(self::TAG_STRING_VALUE, t('Enter the unit identifier.'))
                    ->addDependency(self::TAG_UNIT_CLASS, t('Select the unit class (important for champions).'))
                    ->addDependency(self::TAG_INTEGER_VALUE, t('Enter a level for the unit.'), true)
                    ->addDependency(self::TAG_STRING_VALUE_2, t('Enter a custom name for the unit.'), true)
                    ->done()
                ->addDependencySet(t('Change hit points'))
                    ->addDependency(self::TAG_INTEGER_VALUE, t('Set the amount in the integer value (can be negative).'))
                    ->done()
            ->done()
            ->addEnumItem('Resource', t('Resource modifier'))
            ->addEnumItemR('GiveItem', t('Item modifier'))
                ->addDependency(self::TAG_ACTION, t('Enter the item identifier.'))
                ->addDependency(self::TAG_STRING_VALUE, t('Enter a custom name for the item.'), true)
                ->addDependency(self::TAG_INTEGER_VALUE, t('Usually set to 100, for unknown reasons.'))
                ->done()
            ->setDescription(sb()
                ->bold('Resource:')
                ->nl()
                ->note()
                ->add('Select the resource action in the action field.')
                ->ul(array(
                    sb()
                        ->add('Add fame:')
                        ->ul(array(
                            'Set the amount in the integer value.'
                        )),
                    sb()
                        ->add('Change population:')
                        ->ul(array(
                            'Set the amount in the integer value (negative amounts allowed).'
                        )),
                    sb()
                        ->add('Add metal:')
                        ->ul(array(
                            'Set the amount in the integer value.'
                        ))
                ))
                ->bold('Player:')
                ->nl()
                ->note()
                ->add('Select the player action in the action field.')
                ->ul(array(
                    sb()
                        ->add('Give gold:')
                        ->ul(array(
                            'Set the amount in the integer value.'
                        )),
                    sb()
                        ->add('Unlock improvement:')
                        ->ul(array(
                            'Set the name of the improvement in the string value.'
                        )),
                    sb()
                        ->add('Unlock spell:')
                        ->ul(array(
                            'Set the name of the spell in the string value.'
                        )),
                    sb()
                        ->add('Change ability bonus:')
                        ->ul(array(
                            'Set the target ability name in the string value.',
                            'Set the amount in the integer field (negative values allowed).'
                        )),
                    sb()
                        ->add('All units gain a level:')
                        ->ul(array(
                            sprintf(
                                'Set the target units class in the string value (e.g. %s).',
                                sb()->code('WildCreatures')
                            ),
                            'Set the amount of levels in the integer value.'
                        )),
                    sb()
                        ->add('Damage all units:')
                        ->ul(array(
                            'Set the amount of damage in the integer value.'
                        ))
                ))
            );

        $actions->registerEnum(self::TAG_ACTION,t('Action to take'))
            ->setDescription(t('Selects the action to take, which must match the selected modifier type.'))
            ->addEnumGroup('Unit actions')
                ->addEnumItem('GiveExperience', 'Give experience')
                ->addEnumItem('UnitJoinArmy', ' Add new unit to current army')
                ->addEnumItem('CurHealth', 'Change hit points')
                ->done()
            ->addEnumGroup('Resource actions')
                ->addEnumItem('Fame', 'Increase fame')
                ->addEnumItem('Population', 'Change population')
                ->addEnumItem('Metal', 'Add metal')
                ->done()
            ->addEnumGroup('Map actions')
                ->addEnumItem('CreateGoodieHut', 'Create goodie hut')
                ->addEnumItem('CreateShard', 'Create shard')
                ->addEnumItem('SpawnMonster', 'Spawn monster')
                ->addEnumItem('CreateResourceHoard', 'Create resource')
                ->addEnumItem('AllPlayersDeclareWar', 'All players declare mutual war')
                ->addEnumItem('CreateWorldProp', 'Create world property')
                ->addEnumItem('AllPlayersResearchComplete', 'All players complete their current research')
                ->addEnumItem('AllCitiesUnitTrainingComplete', 'All cities finish training their current unit')
                ->addEnumItem('BlockTile', 'Block a map tile')
                ->done()
            ->addEnumGroup('Player actions')
                ->addEnumItem('UnlockSpell', 'Unlock a spell')
                ->addEnumItem('Treasury', 'Give gold')
                ->addEnumItem('UnlockImprovement', 'Unlock an improvement')
                ->addEnumItem('AbilityBonus', 'Change ability bonus')
                ->addEnumItem('AllUnitsGainLevel', 'All units gain a level')
                ->addEnumItem('DamageAllUnits', 'Damage all units')
                ->done();

        $itemNames = array(
            'HateStone',
            'PlateHelmet_Ivory',
            'Broadsword_Champions',
            'PerformersHorse',
            'BootsOfTheSpider',
            'CloakOfStars',
            'Loot_SpiderSilk',
            'Longsword_Shadow',
            'KiteShield_Obsidian',
            'StuddedCollar_Amethyst',
            'Dagger_Butchers',
            'Staff_Freezing',
            'Staff_Furnace',
            'Staff_Banishing',
            'Broadsword_Berserkers',
            'BattleAxe',
            'RoundShield_Ghost',
            'ArcticWolfCloak',
            'Potion_Restoration',
            'TildaHerbs',
            'Warhammer_Blessed',
            'EreogsToken',
            'BandOfAgility',
            'Loot_DeadRat',
            'Club_Doom',
            'Longsword_Shadow',
            'Longsword_Assassins',
            'Mount_Pony',
            'Shortbow_Ignys',
            'Mushroom',
            'Axe_Ignys',
            'AmuletOfLife',
            'Longbow_Guiding',
            'WarBoarMount',
            'KiteShield_PhoenixShield',
            'Shortsword_Hunters',
            'Shortbow_Hunters',
            'PlateHelmet_Ivory',
            'PerformersHorse',
            'BootsOfTheSpider',
            'PlateBreastpiece_Impenetrable',
            'Greatsword_Void',
            'Longsword_Razor',
            'Maul_CurgensHammer',
            'Loot_DragonEye',
            'Maul_Doom'
        );

        $resources = array(
            'Resource_TwlightBees',
            'Metal',
            'Gold'
        );

        $improvementNames = array(
            'TheatreOfTheWind'
        );

        $shardNames = array(
            'ElementalAirShard02'
        );

        $values = $this->attributeManager->addGroup('values', t('Values'))
            ->setIcon(UI::icon()->values())
            ->setDescription(sb()
                ->t('The fields are used to enter any values that the selected action may need.')
                ->t('Refer to the action fields for hints on which values are needed, and how to fill them.')
            );

        $values->registerString(self::TAG_STRING_VALUE, 'Value: String 1');
        $values->registerString(self::TAG_STRING_VALUE_2, 'Value: String 2');
        $values->registerBool(self::TAG_BOOLEAN_VALUE, 'Value: Boolean');
        $values->registerInt(self::TAG_INTEGER_VALUE, 'Value: Integer');

        $values->registerEnum(self::TAG_UNIT_CLASS, t('Unit class'))
            ->addEnumGroup('Map locations')
            ->addDataCollection(GoodieHuts::create())
            ->done()
            ->addEnumGroup('Unit classes')
            ->addEnumItem('Unit', 'Regular unit')
            ->addEnumItem('Champion', 'Champion unit')
            ->done();

        $values->registerInt(self::TAG_RADIUS, t('Radius'));
    }

    public function getActionID() : string
    {
        return $this->attributes->getString(self::TAG_ACTION);
    }

    public function getActionLabel() : string
    {
        $actionID = $this->getActionID();

        if(!empty($actionID))
        {
            $enum = $this->attributeManager->getEnumByName(self::TAG_ACTION);

            if($enum->hasValue($actionID))
            {
                return $enum->getLabelByValue($actionID);
            }

            return $actionID;
        }

        return '';
    }

    public function getModifierType() : string
    {
        return $this->attributes->getString(self::TAG_MODIFIER_TYPE);
    }

    public function getModifierLabel() : string
    {
        $type = $this->getModifierType();

        if(!empty($type))
        {
            return $this->attributeManager
                ->getEnumByName(self::TAG_MODIFIER_TYPE)
                ->getLabelByValue($type);
        }

        return '';
    }

    public function getRadius() : int
    {
        return $this->attributes->getInt(self::TAG_RADIUS);
    }

    public function getLabel() : string
    {
        return t('Game modifier');
    }

    public function getIcon() : ?Icon
    {
        return UI::icon()->modifiers();
    }

    public function getIdentifier() : string
    {
        return $this->attributes->getString(self::TAG_INTERNAL_NAME);
    }

    public function getSubLabel() : string
    {
        $label = sb();

        $label->code($this->getIdentifier());

        $modifierLabel = $this->getModifierLabel();
        $actionLabel = $this->getActionLabel();

        if(!empty($modifierLabel) && !empty($actionLabel)) {
            $label
                ->nl()
                ->add($modifierLabel)
                ->add(':')
                ->add($actionLabel);
        }

        return (string)$label;
    }
}
