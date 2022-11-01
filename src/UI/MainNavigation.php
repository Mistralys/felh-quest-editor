<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI;

use Mistralys\FELHQuestEditor\UI\Pages\BuildCache;
use Mistralys\FELHQuestEditor\UI\Pages\QuestsList;
use Mistralys\FELHQuestEditor\UI\Pages\ViewRawData;
use function AppLocalize\t;

class MainNavigation
{
    public function getItems() : array
    {
        return array(
            QuestsList::URL_NAME => t('Quests list'),
            ViewRawData::URL_NAME => t('Raw data'),
            BuildCache::URL_NAME => t('Cache')
        );
    }
}
