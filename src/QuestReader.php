<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\ArrayDataCollection;
use AppUtils\FileHelper\FileInfo;
use AppUtils\XMLHelper;
use Mistralys\FELHQuestEditor\CommonRecords\GameModifier;
use Mistralys\FELHQuestEditor\CommonRecords\GameModifierContainerInterface;
use Mistralys\FELHQuestEditor\CommonRecords\Treasure;
use Mistralys\FELHQuestEditor\CommonRecords\TreasureContainerInterface;

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
                if(is_string($idx)) {
                    echo '<pre style="color:#444;font-family:monospace;font-size:14px;background:#f0f0f0;border-radius:5px;border:solid 1px #333;padding:16px;margin:12px 0;">';
                    print_r($objectiveData);
                    echo '</pre>';
                }

                
                $choiceDef = new QuestChoice($objective, $idx, $this->detectAttributes($choiceData));

                // treasure?

                $objective->addChoice($choiceDef);
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
        $modifier = new GameModifier(ArrayDataCollection::create($modifierDef));

        $container->addGameModifier($modifier);
    }
}
