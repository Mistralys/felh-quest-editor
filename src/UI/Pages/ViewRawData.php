<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI\Pages;

use AppUtils\FileHelper;
use AppUtils\JSHelper;
use Mistralys\FELHQuestEditor\FilesReader;
use Mistralys\FELHQuestEditor\UI\BasePage;

class ViewRawData extends BasePage
{
    public const URL_NAME = 'raw-data';

    private FilesReader $reader;

    public function __construct(FilesReader $reader)
    {
        $this->reader = $reader;
    }

    public function getTitle() : string
    {
        return 'Raw quest data';
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
                        </button>
                    </h2>
                    <div id="<?php echo $jsID ?>-el" class="accordion-collapse collapse" aria-labelledby="<?php echo $jsID ?>-heading" data-bs-parent="#<?php echo $containerID ?>">
                        <div class="accordion-body">
                            <?php
                            foreach($quests as $quest)
                            {
                                ?>
                                <pre style="color:#444;font-family:monospace;font-size:14px;background:#f0f0f0;border-radius:5px;border:solid 1px #333;padding:16px;margin:12px 0;max-height:500px;overflow:auto"><?php print_r($quest) ?></pre>
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
