<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling;

use AppUtils\FileHelper;
use AppUtils\FileHelper\FileInfo;
use AppUtils\FileHelper\FolderInfo;
use AppUtils\FileHelper_Exception;
use AppUtils\HTMLTag;
use AppUtils\OutputBuffering;
use Mistralys\FELHQuestEditor\UI;

abstract class BaseGraphicAttribute extends BaseAttribute
{
    private FolderInfo $sourceFolder;

    /**
     * @var string[]|null
     */
    private ?array $imagePaths = null;
    private bool $extension = true;

    abstract protected function getGfxPathRelative() : string;

    abstract protected function isFileValid(FileInfo $file) : bool;

    protected function init() : void
    {
        $this->sourceFolder = FolderInfo::factory(FELHQM_GAME_FOLDER.'/'.$this->getGfxPathRelative());
    }

    public function getSourceFolder() : FolderInfo
    {
        return $this->sourceFolder;
    }

    public function enableExtension(bool $enabled=true) : self
    {
        $this->extension = $enabled;
        return $this;
    }
    
    /**
     * @return FileInfo[]
     * @throws FileHelper_Exception
     */
    public function getImageFiles() : array
    {
        if(isset($this->imagePaths)) {
            return $this->imagePaths;
        }

        $this->imagePaths = array();

        $paths = FileHelper::createFileFinder($this->sourceFolder)
            ->includeExtension('png')
            ->setPathmodeAbsolute()
            ->getAll();

        foreach($paths as $path)
        {
            $info = FileInfo::factory($path);

            if($this->isFileValid($info)) {
                $this->imagePaths[] = $info;
            }
        }

        return $this->imagePaths;
    }

    protected function displayElement() : void
    {
        $refreshStatement = sprintf("ImagePreviewer.Refresh('%s')", $this->getElementID());

        UI::addJSOnload($refreshStatement);

        $select = HTMLTag::create('select')
            ->id($this->getElementID())
            ->name($this->getElementName())
            ->attr('onchange', $refreshStatement)
            ->attr('data-folder', $this->getGfxPathRelative())
            ->addClass('form-control');

        echo $select->renderOpen();

        echo '<option value="">No image</option>';

        $elementValue = $this->getFormValue();

        $images = $this->getImageFiles();

        foreach($images as $image)
        {
            $info = FileInfo::factory($image);
            $optionValue = $this->resolveValue($info);

            $option =  HTMLTag::create('option')
                ->attr('value', $optionValue)
                ->attr('data-source-width', (string)getimagesize($image->getPath())[0])
                ->setContent($info->getBaseName());

            if($optionValue === $elementValue) {
                $option->prop('selected');
            }

            echo $option;
        }

        echo $select->renderClose();
    }

    private function resolveValue(FileInfo $file) : string
    {
        if($this->extension) {
            return $file->getName();
        }

        return $file->getBaseName();
    }

    public function getDescription() : string
    {
        $descr = parent::getDescription();

        OutputBuffering::start();

        ?>
        <div class="graphic-preview" id="<?php echo $this->getElementID() ?>-container">
            <img src="" alt="Preview image" id="<?php echo $this->getElementID() ?>-image">
        </div>
        <?php

        return $descr.OutputBuffering::get();
    }
}
