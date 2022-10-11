<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling;

use AppUtils\Interface_Stringable;
use Mistralys\FELHQuestEditor\UI\IconizableInterface;
use Mistralys\FELHQuestEditor\UI\IconizableTrait;

abstract class BaseGroup implements IconizableInterface
{
    use IconizableTrait;

    private string $label;
    private string $name;
    private string $description = '';

    public function __construct(string $name, string $label)
    {
        $this->label = $label;
        $this->name = $name;
    }

    public function getID() : string
    {
        return 'g-'.$this->name;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getIconLabel() : string
    {
        return $this->renderIconLabel($this->getLabel());
    }

    /**
     * @param string|Interface_Stringable $description
     * @return $this
     */
    public function setDescription($description) : self
    {
        $this->description = (string)$description;
        return $this;
    }

    abstract public function countRecords() : int;
}
