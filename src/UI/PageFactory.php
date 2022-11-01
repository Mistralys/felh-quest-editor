<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI;

use Mistralys\FELHQuestEditor\FilesReader;
use Mistralys\FELHQuestEditor\Request;
use Mistralys\FELHQuestEditor\UI;
use Mistralys\FELHQuestEditor\UI\Pages\BuildCache;
use Mistralys\FELHQuestEditor\UI\Pages\EditQuest;
use Mistralys\FELHQuestEditor\UI\Pages\QuestsList;
use Mistralys\FELHQuestEditor\UI\Pages\ViewGraphicFile;
use Mistralys\FELHQuestEditor\UI\Pages\ViewRawData;

class PageFactory
{
    private string $activePageID;
    private FilesReader $reader;
    private Request $request;

    public function __construct(Request $request, FilesReader $reader)
    {
        $this->request = $request;
        $this->activePageID = $request->getParam(UI::REQUEST_VAR_PAGE);
        $this->reader = $reader;
    }

    public function instantiate() : BasePage
    {
        switch ($this->activePageID)
        {
            case ViewGraphicFile::URL_NAME:
                return new ViewGraphicFile($this->request);

            case ViewRawData::URL_NAME:
                return new ViewRawData($this->request, $this->reader);

            case EditQuest::URL_NAME:
                $collection = $this->reader->getCollection();
                return new EditQuest($this->request, $collection->requireByRequest());

            case BuildCache::URL_NAME:
                return new BuildCache($this->request, $this->reader);
        }

        $this->activePageID = QuestsList::URL_NAME;
        $collection = $this->reader->getCollection();
        return new QuestsList($this->request, $collection);
    }

    public function getActivePageID() : string
    {
        return $this->activePageID;
    }
}
