<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\FileHelper;
use AppUtils\FileHelper\FileInfo;
use AppUtils\FileHelper\JSONFile;
use AppUtils\Request;
use Mistralys\FELHQuestEditor\UI\Pages\BuildCache;

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
    private ?JSONFile $cacheFile = null;

    public static function create() : FilesReader
    {
        return new self();
    }

    public function resetCache() : void
    {
        $this->cacheFile = null;
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
            $this->resetCache();
        }

        return $this;
    }

    public function getCacheKey() : string
    {
        return md5(implode(
            ';',
            $this->selected
        ));
    }

    public function getCacheFile() : JSONFile
    {
        if(!isset($this->cacheFile))
        {
            $this->cacheFile = JSONFile::factory(sprintf(
                '%s/%s.reader.cache',
                FELHQM_CACHE_FOLDER,
                $this->getCacheKey()
            ));
        }

        return $this->cacheFile;
    }

    public function hasCache() : bool
    {
         return $this->getCacheFile()->exists();
    }

    public function getRawData() : array
    {
        if($this->hasCache())
        {
            return $this->loadCacheData();
        }

        $reader = new QuestReader();
        $result = array();

        foreach($this->selected as $file)
        {
            $info = FileInfo::factory($file);

            if($info->exists())
            {
                $result[$info->getPath()] = $reader->getFileData($info);
            }
        }

        $this->getCacheFile()->putData($result, false);

        return $result;
    }

    private function loadCacheData() : array
    {
        return $this->getCacheFile()->parse();
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

    /**
     * @param array<string,string|number> $params
     * @return string
     */
    public function getURLRefreshCache(array $params=array()) : string
    {
        $params[BuildCache::REQUEST_VAR_REFRESH] = 'yes';

        return $this->getURLCache($params);
    }

    /**
     * @param array<string,string|number> $params
     * @return string
     */
    public function getURLCache(array $params=array()) : string
    {
        return UI::getPageURL(BuildCache::URL_NAME, $params);
    }

    public function rebuildCache() : void
    {
        $file = $this->getCacheFile();

        if($file->exists()) {
            $file->delete();
        }

        $this->getRawData();
    }

    public function getSelectedFiles() : array
    {
        return $this->selected;
    }
}
