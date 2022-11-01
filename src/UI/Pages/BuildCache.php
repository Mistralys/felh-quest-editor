<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI\Pages;

use AppUtils\ConvertHelper;
use Mistralys\FELHQuestEditor\FilesReader;
use Mistralys\FELHQuestEditor\Request;
use Mistralys\FELHQuestEditor\UI;
use Mistralys\FELHQuestEditor\UI\BasePage;
use function AppLocalize\pt;
use function AppLocalize\pts;
use function AppLocalize\t;
use function AppUtils\sb;

class BuildCache extends BasePage
{
    public const URL_NAME = 'cache';
    public const REQUEST_VAR_REFRESH = 'refresh';

    private FilesReader $reader;

    public function __construct(Request $request, FilesReader $reader)
    {
        $this->reader = $reader;

        parent::__construct($request);
    }

    public function getTitle() : string
    {
        return t('Data cache');
    }

    public function getAbstract() : string
    {
        return (string)sb()
            ->t('To avoid parsing game files every time, a data cache file is generated.')
            ->t('Whenever game changes are modified, this must be generated anew.')
            ->t('The cache is tied to the selected game files, so each selection has a unique cache file.');
    }

    public function handleActions() : void
    {
        if(!$this->request->getBool(self::REQUEST_VAR_REFRESH))
        {
            return;
        }

        $this->reader->rebuildCache();

        $this->request->redirectWithSuccessMessage(
            $this->reader->getURLCache(),
            t(
                'The data cache has been updated successfully at %1$s.',
                sb()->time()
            )
        );
    }

    public function display() : void
    {
        ?>
        <p>
            <?php pt('Selected game files:') ?>
        </p>
        <ul>
            <li><?php echo implode('</li><li>', $this->reader->getSelectedFiles()) ?></li>
        </ul>
        <?php

        if($this->reader->hasCache())
        {
        ?>
            <p>
                <?php pts('Cache last built on:') ?>
                <?php
                    echo ConvertHelper::date2listLabel(
                        $this->reader->getCacheFile()->getModifiedDate(),
                        true,
                        true
                    );
                ?>
            </p>
            <p>
                <?php
                    echo UI::button(t('Refresh cache'))
                        ->setIcon(UI::icon()->refresh())
                        ->link($this->reader->getURLRefreshCache());

                ?>
            </p>
        <?php
        }
        else
        {
            ?>
            <p>
                <?php pt('The cache has never been built.'); ?>
            </p>
            <p>
                <?php
                echo UI::button(t('Build the cache'))
                    ->setIcon(UI::icon()->refresh())
                    ->makePrimary()
                    ->link($this->reader->getURLRefreshCache());
                ?>
            </p>
            <?php
        }
    }
}
