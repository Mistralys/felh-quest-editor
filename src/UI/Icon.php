<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI;

use AppUtils\Interface_Classable;
use AppUtils\Interface_Stringable;
use AppUtils\Traits_Classable;

class Icon implements Interface_Stringable , Interface_Classable
{
    use Traits_Classable;

    public const FA_STYLE_SOLID = 'solid';
    public const FA_STYLE_REGULAR = 'regular';

    private string $type;
    private string $style = self::FA_STYLE_SOLID;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function setType(string $type) : self
    {
        $this->type = $type;
        return $this;
    }

    public function objective() : self
    {
        return $this->setType('bullseye');
    }

    public function settings() : self
    {
        return $this->setType('gear');
    }

    public function choices() : self
    {
        return $this->setType('rectangle-list');
    }

    public function trigger() : self
    {
        return $this->setType('bolt');
    }

    public function flags() : self
    {
        return $this->setType('circle-check');
    }

    public function add() : self
    {
        return $this->setType('circle-plus');
    }

    public function texts() : self
    {
        return $this->setType('quote-left');
    }

    public function treasure() : self
    {
        return $this->makeRegular()->setType('gem');
    }

    public function graphics() : self
    {
        return $this
            ->setStyle(self::FA_STYLE_REGULAR)
            ->setType('images');
    }

    public function makeRegular() : self
    {
        return $this->setStyle(self::FA_STYLE_REGULAR);
    }

    public function makeSolid() : self
    {
        return $this->setStyle(self::FA_STYLE_SOLID);
    }

    public function setStyle(string $style) : self
    {
        $this->style = $style;
        return $this;
    }

    public function render() : string
    {
        return sprintf(
            '<i class="fa-%s fa-%s %s"></i>',
            $this->style,
            $this->type,
            $this->classesToString()
        );
    }

    public function display() : void
    {
        echo $this->render();
    }

    public function __toString() : string
    {
        return $this->render();
    }

    public function modifiers() : self
    {
        return $this->setType('sliders');
    }

    public function actions() : self
    {
        return $this->setType('code-branch');
    }

    public function conditions() : self
    {
        return $this->setType('circle-question');
    }

    public function nextObjective() : self
    {
        return $this->setType('play');
    }

    public function lastObjective() : self
    {
        return $this->setType('stop');
    }

    public function units() : self
    {
        return $this->setType('spider');
    }

    public function encounter() : self
    {
        return $this->setType('skull');
    }

    public function quest() : self
    {
        return $this->setType('tower-observation');
    }

    public function values() : self
    {
        return $this->setType('flask-vial');
    }

    public function refresh() : self
    {
        return $this->setType('recycle');
    }

    public function success() : self
    {
        return $this->setType('check');
    }

    public function warning() : self
    {
        return $this->setType('triangle-exclamation');
    }

    public function danger() : self
    {
        return $this->setType('skull-crossbones');
    }

    public function info() : self
    {
        return $this->setType('circle-info');
    }
}
