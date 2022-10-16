<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\ArrayDataCollection;
use AppUtils\FileHelper\FileInfo;
use AppUtils\XMLHelper;
use Mistralys\FELHQuestEditor\CommonRecords\Encounter;
use Mistralys\FELHQuestEditor\CommonRecords\GameModifier;
use Mistralys\FELHQuestEditor\CommonRecords\GameModifierContainerInterface;
use Mistralys\FELHQuestEditor\CommonRecords\Objective;
use Mistralys\FELHQuestEditor\CommonRecords\Treasure;
use Mistralys\FELHQuestEditor\CommonRecords\TreasureContainerInterface;
use Mistralys\FELHQuestEditor\CommonRecords\UnitInstance;

class QuestReader
{
    private QuestsCollection $quests;

    public function __construct()
    {
        $this->quests = new QuestsCollection(array());
    }

    public function getCollection() : QuestsCollection
    {
        return $this->quests;
    }

    public function parseFile(FileInfo $path) : void
    {
        $data = $this->getFileData($path);

        if($data === null) {
            return;
        }

        foreach($data as $questDef)
        {
            $this->parseQuest($questDef, $path);
        }
    }

    public function getFileData(FileInfo $path) : ?array
    {
        $data = XMLHelper::convertFile(
            $path
                ->requireExists()
                ->requireReadable()
                ->getPath()
        )
            ->toArray();

        return $data['QuestDef'] ?? array();
    }

    private function parseQuest(array $questData, FileInfo $sourceFile) : void
    {
        $quest = new Quest($this->detectAttributes($questData), $sourceFile);

        if(isset($questData['QuestObjectiveDef']))
        {
            foreach ($questData['QuestObjectiveDef'] as $objectiveDef)
            {
                $this->parseObjective($quest, $objectiveDef);
            }
        }

        if(isset($questData['Treasure']))
        {
            $this->parseTreasure($quest, $questData['Treasure']);
        }

        $this->quests->add($quest);
    }

    private function parseObjective(Quest $quest, $objectiveData) : void
    {
        $objective = new QuestObjective($quest, $this->detectAttributes($objectiveData));

        if(isset($objectiveData['QuestChoiceDef']))
        {
            if(!isset($objectiveData['QuestChoiceDef'][0])) {
                $objectiveData['QuestChoiceDef'] = array($objectiveData['QuestChoiceDef']);
            }

            foreach($objectiveData['QuestChoiceDef'] as $idx => $choiceData)
            {
                $choice = new QuestChoice($objective, $idx, $this->detectAttributes($choiceData));
                $objective->addChoice($choice);

                if(isset($choiceData['Encounter'])) {
                    $this->parseEncounter($choice, $choiceData['Encounter']);
                }
            }

            if(isset($questData['Treasure']))
            {
                $this->parseTreasure($objective, $questData['Treasure']);
            }
        }

        if(isset($objectiveData['QuestConditionDef']))
        {
            if(!isset($objectiveData['QuestConditionDef'][0])) {
                $objectiveData['QuestConditionDef'] = array($objectiveData['QuestConditionDef']);
            }

            foreach($objectiveData['QuestConditionDef'] as $conditionData)
            {
                $conditionDef = new QuestCondition($objective, $this->detectAttributes($conditionData));

                if(isset($conditionData['Objective']))
                {
                    $conditionDef->setObjective(new Objective($this->detectAttributes($conditionData['Objective'])));
                }

                $objective->addCondition($conditionDef);
            }
        }

        $quest->addObjective($objective);
    }

    private function detectAttributes(array $dataSet) : ArrayDataCollection
    {
        $attribs = array();

        foreach($dataSet as $key => $val)
        {
            if($key === '@attributes') {
                foreach($val as $name => $attVal) {
                    $attribs[$name] = $attVal;
                }
                continue;
            }

            if(is_array($val) && isset($val['@text']))
            {
                $attribs[$key] = $val['@text'];
            }
        }

        return ArrayDataCollection::create($attribs);
    }

    private function parseTreasure(TreasureContainerInterface $container, array $gameModifiers) : void
    {
        $treasure = new Treasure(new ArrayDataCollection(array()));

        // Ensure it's a list
        if(!isset($gameModifiers[0])) {
            $gameModifiers = array($gameModifiers);
        }

        foreach($gameModifiers as $gameModifier)
        {
            $this->parseGameModifier($treasure, $gameModifier['GameModifier']);
        }

        $container->addTreasure($treasure);
    }

    private function parseGameModifier(GameModifierContainerInterface $container, array $modifierDef) : void
    {
        $modifier = new GameModifier($this->detectAttributes($modifierDef));

        $container->addGameModifier($modifier);
    }

    private function parseEncounter(QuestChoice $choice, $encounterData) : void
    {
        $encounter = new Encounter($choice, $this->detectAttributes($encounterData));

        $choice->setEncounter($encounter);

        if(!isset($encounterData['UnitInstance']))
        {
            return;
        }
        
        if(!isset($encounterData['UnitInstance'][0])) {
            $encounterData['UnitInstance'] = array($encounterData['UnitInstance']);
        }

        foreach($encounterData['UnitInstance'] as $unitData)
        {
            $unit = new UnitInstance($encounter, $this->detectAttributes($unitData));
            $encounter->addUnitInstance($unit);
        }
    }
}
