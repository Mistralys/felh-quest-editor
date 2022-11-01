<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI;

use AppUtils\HTMLTag;
use AppUtils\Interface_Stringable;
use AppUtils\Interfaces\RenderableInterface;
use AppUtils\OutputBuffering;
use AppUtils\Traits\RenderableTrait;
use Mistralys\FELHQuestEditor\UI;

class Message
    implements
    RenderableInterface,
    IconizableInterface
{
    use RenderableTrait;
    use IconizableTrait;

    public const LAYOUT_SUCCESS = 'success';
    public const LAYOUT_PRIMARY = 'primary';
    public const LAYOUT_WARNING = 'warning';
    public const LAYOUT_INFO = 'info';
    public const LAYOUT_DANGER = 'danger';

    private string $message = '';
    private string $layout;
    private string $title = '';

    /**
     * @param string|number|Interface_Stringable|NULL $message
     */
    public function __construct($message)
    {
        $this->setIcon(UI::icon());
        $this->setMessage($message);
        $this->makeInfo();
    }

    /**
     * @param string|number|Interface_Stringable|NULL $message
     * @return $this
     */
    public function setMessage($message) : self
    {
        $this->message = (string)$message;
        return $this;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * @param string|number|Interface_Stringable|NULL $title
     * @return $this
     */
    public function setTitle($title) : self
    {
        $this->title = (string)$title;
        return $this;
    }

    public function makeSuccess() : self
    {
        return $this->setLayout(self::LAYOUT_SUCCESS);
    }

    public function makeWarning() : self
    {
        return $this->setLayout(self::LAYOUT_WARNING);
    }

    public function makePrimary() : self
    {
        return $this->setLayout(self::LAYOUT_PRIMARY);
    }

    public function makeInfo() : self
    {
        return $this->setLayout(self::LAYOUT_INFO);
    }

    public function makeDangerous() : self
    {
        return $this->setLayout(self::LAYOUT_DANGER);
    }

    public function setLayout(string $layout) : self
    {
        $icon = $this->getIcon();

        switch ($layout)
        {
            case self::LAYOUT_PRIMARY:
            case self::LAYOUT_INFO:
                $icon->info();
                break;

            case self::LAYOUT_DANGER:
                $icon->danger();
                break;

            case self::LAYOUT_WARNING:
                $icon->warning();
                break;

            case self::LAYOUT_SUCCESS:
                $icon->success();
                break;
        }

        $this->layout = $layout;
        return $this;
    }

    public function render() : string
    {
        return (string)HTMLTag::create('div')
            ->addClass('alert')
            ->addClass('alert-'.$this->layout)
            ->attr('role', 'alert')
            ->setContent($this->renderContent());
    }

    private function renderContent() : string
    {
        OutputBuffering::start();

        if(!empty($this->title)) {
            ?>
            <h4 class="alert-heading">
                <?php echo $this->getIcon() ?>
                <?php echo $this->title ?>
            </h4>
            <?php
        } else {
            echo $this->getIcon();
            echo ' ';
            echo $this->message;
        }

        return OutputBuffering::get();
    }
}
