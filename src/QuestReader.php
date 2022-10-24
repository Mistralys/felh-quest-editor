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

        return $data[Quest::TAG_NAME] ?? array();
    }

    private function parseQuest(array $questData, FileInfo $sourceFile) : void
    {
        $quest = new Quest($this->detectAttributes($questData), $sourceFile);

        if(isset($questData[QuestObjective::TAG_NAME]))
        {
            $this->convertToList($questData, QuestObjective::TAG_NAME);

            foreach ($questData[QuestObjective::TAG_NAME] as $objectiveDef)
            {
                $this->parseObjective($quest, $objectiveDef);
            }
        }

        $this->processTreasure($questData, $quest);

        $this->quests->add($quest);
    }

    private function parseObjective(Quest $quest, $objectiveData) : void
    {
        $objective = new QuestObjective($quest, $this->detectAttributes($objectiveData));

        if(isset($objectiveData[QuestChoice::TAG_NAME]))
        {
            $this->convertToList($objectiveData, QuestChoice::TAG_NAME);

            foreach($objectiveData[QuestChoice::TAG_NAME] as $idx => $choiceData)
            {
                $this->parseChoice($objective, $idx, $choiceData);
            }
        }

        $this->processTreasure($objectiveData, $objective);

        if(isset($objectiveData[QuestCondition::TAG_NAME]))
        {
            $this->convertToList($objectiveData, QuestCondition::TAG_NAME);

            foreach($objectiveData[QuestCondition::TAG_NAME] as $conditionData)
            {
                $this->parseCondition($objective, $conditionData);
            }
        }

        $quest->addObjective($objective);
    }

    private function processTreasure(array $data, TreasureContainerInterface $container) : void
    {
        if(!isset($objectiveData[Treasure::TAG_NAME]))
        {
            return;
        }

        $this->convertToList($data, Treasure::TAG_NAME);
        $this->parseTreasure($container, $data[Treasure::TAG_NAME]);
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

    /**
     * @param TreasureContainerInterface $container
     * @param array<int,array{GameModifier:array<string,mixed>}> $gameModifiers
     * @return void
     */
    private function parseTreasure(TreasureContainerInterface $container, array $gameModifiers) : void
    {
        // Treasures do not have any attributes themselves:
        // They are a collection of GameModifier tags.
        $treasure = new Treasure(new ArrayDataCollection(array()));

        foreach($gameModifiers as $gameModifier)
        {
            $this->parseGameModifier($treasure, $gameModifier[GameModifier::TAG_NAME]);
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

        if(!isset($encounterData[UnitInstance::TAG_NAME]))
        {
            return;
        }

        $this->convertToList($encounterData, UnitInstance::TAG_NAME);
        
        foreach($encounterData[UnitInstance::TAG_NAME] as $unitData)
        {
            $unit = new UnitInstance($encounter, $this->detectAttributes($unitData));
            $encounter->addUnitInstance($unit);
        }
    }

    /**
     * Because of the way that the XMLHelper converts XML to
     * JSON, nodes that only contain a single record will not
     * be an indexed array of entries, even if they usually are.
     *
     * This ensures that the target data key is always a list.
     *
     * @param array<mixed> $dataSet
     * @param string $tagName
     * @return void
     */
    private function convertToList(array &$dataSet, string $tagName) : void
    {
        if(!isset($dataSet[$tagName][0])) {
            $dataSet[$tagName] = array($dataSet[$tagName]);
        }
    }

    private function parseChoice(QuestObjective $objective, int $number, array $choiceData) : void
    {
        $choice = new QuestChoice($objective, $number, $this->detectAttributes($choiceData));

        $objective->addChoice($choice);

        if(isset($choiceData[Encounter::TAG_NAME])) {
            $this->parseEncounter($choice, $choiceData[Encounter::TAG_NAME]);
        }
    }

    private function parseCondition(QuestObjective $objective, $conditionData) : void
    {
        $conditionDef = new QuestCondition($objective, $this->detectAttributes($conditionData));

        if(isset($conditionData[Objective::TAG_NAME]))
        {
            $conditionDef->setObjective(new Objective($this->detectAttributes($conditionData[Objective::TAG_NAME])));
        }

        $objective->addCondition($conditionDef);
    }
}
