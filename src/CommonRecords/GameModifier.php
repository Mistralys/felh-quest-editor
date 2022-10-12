<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\CommonRecords;

use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\DataTypes\GoodieHuts;
use Mistralys\FELHQuestEditor\UI;
use function AppLocalize\t;
use function AppUtils\sb;

class GameModifier extends BaseRecord
{
    protected function registerAttributes() : void
    {
        $actions = $this->attributeManager->addGroup('actions', 'Actions')
            ->setIcon(UI::icon()->actions());

        $actions->registerString('InternalName', 'Internal name')
            ->setRequired();

        $actions->registerEnum('ModType', 'Modifier target')
            ->addEnumItem('Map', 'Map')
            ->addEnumItem('Player', 'Player')
            ->addEnumItem('Unit', 'Unit')
            ->addEnumItem('Resource', 'Resource')
            ->addEnumItem('GiveItem', 'Give item')
            ->setDescription(sb()
                ->bold('Give item:')
                ->ul(array(
                    (string)sb()
                    ->add('Specific item:')
                    ->ul(array(
                        'Enter the name in the Attribute field.',
                        'Optional: Set a custom item name in the string value field.',
                        'Optional: Add a value in the integer value (often set to 100).'
                    )),
                ))
                ->bold('Map:')
                ->ul(array(
                    sb()
                        ->add('Create goodie hut:')
                        ->ul(array(
                            'Select the attribute "Create goodie hut".',
                            'The name of the hut can be set in the string value field.',
                            'The unit class is used to define the type of hut to spawn.',
                            'Set the radius in which it will spawn.'
                        )),
                    sb()
                        ->add('Spawn monster:')
                        ->ul(array(
                            'Select the attribute "Spawn monster".',
                            'Set the unit level in the integer value.',
                            'Set the unit name in the unit class field.',
                            'Set the radius in which it will spawn.'
                        ))
                ))
                ->bold('Unit:')
                ->ul(array(
                    sb()
                        ->add('Give experience:')
                        ->ul(array(
                            'Choose the attribute "Give experience"',
                            'Set the amount in the integer value.'
                        )),
                    sb()
                        ->add('Add unit to current army:')
                        ->ul(array(
                            'Choose the corresponding attribute',
                            'Set the unit name in the string value.',
                            'Choose the unit class "Unit" for a regular unit.',
                            'Choose the unit class "Champion" for a hero unit.',
                            'Optional: Set the unit\'s level in the integer value.',
                            'Optional: Set a custom unit name in the second string value.'
                        ))
                ))
                ->bold('Resource:')
                ->ul(array(
                    (string)sb()
                        ->add('Add fame:')
                        ->ul(array(
                            'Choose the attribute "Fame"',
                            'Set the amount in the integer value.'
                        ))
                ))
                ->bold('Player:')
                ->ul(array(
                    sb()
                        ->add('Give gold:')
                        ->ul(array(
                            'Choose the attribute "Give gold"',
                            'Set the amount in the integer value.'
                        )),
                    sb()
                        ->add('Unlock improvement:')
                        ->ul(array(
                            'Choose the attribute "Unlock improvement"',
                            'Set the name of the improvement in the string value.'
                        ))
                ))
            );

        $actions->registerEnum('Attribute','Target action')
            ->addEnumGroup('Unit actions')
                ->addEnumItem('GiveExperience', 'Give experience')
                ->addEnumItem('UnitJoinArmy', ' Add new unit to current army')
                ->done()
            ->addEnumGroup('Resource actions')
                ->addEnumItem('Fame', 'Increase fame')
                ->done()
            ->addEnumGroup('Map actions')
                ->addEnumItem('CreateGoodieHut', 'Create goodie hut')
                ->addEnumItem('CreateShard', 'Create shard')
                ->addEnumItem('SpawnMonster', 'Spawn monster')
                ->done()
            ->addEnumGroup('Player actions')
                ->addEnumItem('UnlockSpell', 'Unlock a spell')
                ->addEnumItem('Treasury', 'Give gold')
                ->addEnumItem('UnlockImprovement', 'Unlock an improvement')
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
            'KiteShield_Obsidian'
        );

        $spellNames = array(
            'ManaBlast',
            'ManaShield',
            'Confusion',
            'VatulasDragonslayer'
        );

        $unitNames = array(
            'Umberdoth',
            'EbbenWolf',
            'FireElemental',
            'SwarmSpider',
            'Wolf',
            'Butcherman',
            'SkeletonC_Company',
            'ClambercoilDragon',
            'Ignys',
            'Naja',

            'Unit_Juggernaut_Ongr',
            'Unit_Quest_Archer',
            'Unit_Quest_Noblewoman',
            'Quest_Spearman',
            'Unit_Quest_Pioneer_Krax',

            'Champion_Araine',
            'Champion_Daxus',
            'Champion_Ascian',
            'Mausolos', // Champion
            'Champion_Bacco'
        );

        $improvementNames = array(
            'TheatreOfTheWind'
        );

        $shardNames = array(
            'ElementalAirShard02'
        );

        $settings = $this->attributeManager->addGroup('settings', t('Settings'))
            ->setIcon(UI::icon()->settings());

        $settings->registerString('StrVal', 'Value: String 1')
            ->setDescription(sb()
                ->add('Item levels:')
                ->ul(array(
                    'Uncommon',
                    'Rare'
                ))
            );

        $settings->registerString('StrVal2', 'Value: String 2');

        $settings->registerInt('Value', 'Value: Integer');
        $settings->registerEnum('Unitclass','Unit class')
            ->addEnumGroup('Map locations')
                ->addDataCollection(GoodieHuts::create())
                ->done()
            ->addEnumGroup('Unit classes')
                ->addEnumItem('Unit', 'Regular unit')
                ->addEnumItem('Champion', 'Champion unit')
                ->done();

        $settings->registerInt('Radius', 'Radius')
            ->setDescription(sb()
                ->add('Use with:')
                ->ul(array(
                    'Type[Map]->Attribute[CreateGoodieHut]'
                ))
            );
    }

    public function getLabel() : string
    {
        return 'Game modifier';
    }
}
