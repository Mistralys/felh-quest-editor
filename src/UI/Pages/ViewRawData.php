<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI\Pages;

use AppUtils\FileHelper;
use AppUtils\JSHelper;
use Mistralys\FELHQuestEditor\FilesReader;
use Mistralys\FELHQuestEditor\Request;
use Mistralys\FELHQuestEditor\UI\BasePage;
use function AppLocalize\t;
use function AppUtils\sb;

class ViewRawData extends BasePage
{
    public const URL_NAME = 'raw-data';

    private FilesReader $reader;

    public function __construct(Request $request, FilesReader $reader)
    {
        $this->reader = $reader;

        parent::__construct($request);
    }

    public function getTitle() : string
    {
        return t('Raw quest data');
    }

    public function getAbstract() : string
    {
        return (string)sb()
            ->t('This shows the raw parsed individual quest data, as read from the game files.')
            ->note()
            ->t('While the structure is based on the source XML, it has some peculiarities based on the way the XML is parsed.');
    }

    public function display() : void
    {
        $data = $this->reader->getRawData();
        $containerID = JSHelper::nextElementID();

        ?>
        <div class="accordion" id="<?php echo $containerID ?>">
        <?php
        foreach($data as $file => $quests)
        {
            $jsID = JSHelper::nextElementID();

            ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="<?php echo $jsID ?>-heading">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $jsID ?>-el" aria-expanded="true" aria-controls="<?php echo $jsID ?>-el">
                            <?php echo FileHelper::relativizePath($file, FELHQM_GAME_FOLDER) ?>
                            &#160;
                            <span class="muted">(<?php echo count($quests) ?>)</span>
                        </button>
                    </h2>
                    <div id="<?php echo $jsID ?>-el" class="accordion-collapse collapse" aria-labelledby="<?php echo $jsID ?>-heading" data-bs-parent="#<?php echo $containerID ?>">
                        <div class="accordion-body">
                            <?php
                            foreach($quests as $quest)
                            {
                                ?>
                                <pre class="quest-data"><?php print_r($quest) ?></pre>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php
        }

        ?>
        </div>
        <?php
    }
}
