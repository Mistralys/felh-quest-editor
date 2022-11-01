<?php
/**
 * @package FELH Quest Editor
 * @subpackage UI Pages
 * @see \Mistralys\FELHQuestEditor\UI\Pages\ViewGraphicFile
 */

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI\Pages;

use AppUtils\FileHelper;
use AppUtils\FileHelper\FileInfo;
use AppUtils\FileHelper_Exception;
use Mistralys\FELHQuestEditor\UI\BasePage;
use function AppLocalize\t;

/**
 * Image viewer to display an image from a game folder.
 *
 * @package FELH Quest Editor
 * @subpackage UI Pages
 */
class ViewGraphicFile extends BasePage
{
    public const URL_NAME = 'view-graphic-file';

    public function getTitle() : string
    {
        return t('View graphic file');
    }

    public function getAbstract() : string
    {
        return '';
    }

    public function display() : void
    {
        $path = FileInfo::factory(FELHQM_GAME_FOLDER.'/'.$this->request->getParam('target'));

        if($path->exists() && in_array($path->getExtension(), array('png', 'jpg'), true)) {
            $this->sendFile($path->getPath());
        }

        $this->sendFile(__DIR__.'/../../../img/broken-image.jpg');
    }

    /**
     * @param string $path
     * @return never
     * @throws FileHelper_Exception
     */
    private function sendFile(string $path) : void
    {
        $mime = FileHelper::detectMimeType($path);
        header('Content-Type: '.$mime);

        readfile($path);
        exit;
    }
}
