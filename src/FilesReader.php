<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\FileHelper;
use AppUtils\FileHelper\FileInfo;

class FilesReader
{
    public const FILE_CORE_QUESTS = 'core';
    public const FILE_DLC02_QUESTS = 'dlc02';

    /**
     * @var string[]
     */
    private array $officialFiles = array(
        self::FILE_CORE_QUESTS => 'data\English\CoreQuests.xml',
        self::FILE_DLC02_QUESTS => 'data\English\DLC02_Quests.xml'
    );

    /**
     * @var string[]
     */
    private array $selected = array();

    public static function create() : FilesReader
    {
        return new self();
    }

    public function selectAllOffical() : self
    {
        foreach($this->officialFiles as $file)
        {
            $this->selectRelativePath($file);
        }

        return $this;
    }

    public function selectByID(string $id) : self
    {
        if(isset($this->officialFiles[$id]))
        {
            return $this->selectRelativePath($this->officialFiles[$id]);
        }

        throw new QuestReaderException(
            'Unknown quest file ID.',
            sprintf(
                'No quest file found by ID [%s]. '.PHP_EOL.
                'Available IDs are: [%s]. '.PHP_EOL.
                'Use the %s class constants for the file IDs.',
                $id,
                implode(', ', array_keys($this->officialFiles)),
                self::class
            )
        );
    }

    public function selectCoreQuests() : self
    {
        return $this->selectByID(self::FILE_CORE_QUESTS);
    }

    public function selectDLC02Quests() : self
    {
        return $this->selectByID(self::FILE_DLC02_QUESTS);
    }

    public function selectRelativePath(string $path) : self
    {
        return $this->selectAbsolutePath(FELHQM_GAME_FOLDER.'/'.$path);
    }

    public function selectAbsolutePath(string $path) : self
    {
        $path = FileHelper::normalizePath($path);

        if(!in_array($path, $this->selected, true)) {
            $this->selected[] = $path;
        }

        return $this;
    }

    public function getCollection() : QuestsCollection
    {
        $reader = new QuestReader();

        foreach($this->selected as $file)
        {
            $info = FileInfo::factory($file);

            if($info->exists())
            {
                $reader->parseFile($info);
            }
        }

        return $reader->getCollection();
    }
}
