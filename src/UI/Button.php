<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI;

use AppUtils\HTMLTag;
use AppUtils\Interface_Classable;
use AppUtils\Interface_Stringable;
use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Traits\RenderableTrait;
use AppUtils\Traits_Classable;
use testsuites\FileHelperTests\ResolvePathTypeTest;

class Button
    implements
    RenderableInterface,
    IconizableInterface,
    Interface_Classable
{
    use Traits_Classable;
    use IconizableTrait;
    use RenderableTrait;

    public const LAYOUT_WARNING = 'warning';
    public const LAYOUT_SUCCESS = 'success';
    public const LAYOUT_DANGER = 'danger';
    public const LAYOUT_SECONDARY = 'secondary';
    public const LAYOUT_PRIMARY = 'primary';
    public const LAYOUT_LINK = 'link';

    private string $layout = self::LAYOUT_SECONDARY;
    private string $type = 'button';
    private string $outline = '';
    private string $size = '';
    private string $label;
    private string $clickStatement = '';

    /**
     * @var array{url:string,newTab:bool}|null
     */
    private ?array $link = null;

    /**
     * @param string|number|Interface_Stringable|NULL $label
     */
    public function __construct($label)
    {
        $this->setLabel($label);
    }

    public function render() : string
    {
        $tag = HTMLTag::create($this->resolveTagName())
            ->addClass('btn')
            ->addClass('btn-'.$this->outline.$this->layout) // btn-success or btn-outline-success
            ->addClass($this->size)
            ->addClasses($this->getClasses())
            ->attr('type', $this->type)
            ->attr('onclick', $this->clickStatement)
            ->setContent($this->renderIconLabel($this->getLabel()));

        if(isset($this->link))
        {
            $tag->attr('href', $this->link['url']);

            if($this->link['newTab']) {
                $tag->attr('target', '_blank');
            }
        }

        return (string)$tag;
    }

    private function resolveTagName() : string
    {
        if(isset($this->link)) {
            return 'a';
        }

        return 'button';
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    /**
     * @param string|number|Interface_Stringable|NULL $label
     * @return $this
     */
    public function setLabel($label) : self
    {
        $this->label = (string)$label;
        return $this;
    }

    public function makeWarning() : self
    {
        return $this->setLayout(self::LAYOUT_WARNING);
    }

    public function makeSuccess() : self
    {
        return $this->setLayout(self::LAYOUT_SUCCESS);
    }

    public function makePrimary() : self
    {
        return $this->setLayout(self::LAYOUT_PRIMARY);
    }

    public function makeDangerous() : self
    {
        return $this->setLayout(self::LAYOUT_DANGER);
    }

    public function makeLink() : self
    {
        return $this->setLayout(self::LAYOUT_LINK);
    }

    public function makeOutline() : self
    {
        $this->outline = 'outline-';
        return $this;
    }

    public function makeLarge() : self
    {
        $this->size = 'btn-lg';
        return $this;
    }

    public function makeSmall() : self
    {
        $this->size = 'btn-sm';
        return $this;
    }

    public function setTypeSubmit() : self
    {
        $this->type = 'button';
        return $this;
    }

    public function setTypeButton() : self
    {
        $this->type = 'button';
        return $this;
    }

    public function link(string $url, bool $newTab=false) : self
    {
        $this->clickStatement = '';

        $this->link = array(
            'url' => $url,
            'newTab' => $newTab
        );

        return $this;
    }

    /**
     * @param string|Interface_Stringable $statement
     * @return $this
     */
    public function click($statement) : self
    {
        $this->link = null;
        $this->clickStatement = (string)$statement;

        return $this;
    }

    public function setLayout(string $type) : self
    {
        $this->layout = $type;
        return $this;
    }
}
