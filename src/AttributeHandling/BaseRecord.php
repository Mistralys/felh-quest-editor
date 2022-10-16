<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling;

use AppUtils\ArrayDataCollection;
use Mistralys\FELHQuestEditor\UI;
use Mistralys\FELHQuestEditor\UI\Icon;

abstract class BaseRecord
{
    protected AttributeManager $attributeManager;
    protected ArrayDataCollection $attributes;
    protected string $subLabel = '';

    public function __construct(ArrayDataCollection $attribs)
    {
        $this->attributes = $attribs;
        $this->attributeManager = new AttributeManager($this->attributes);

        $this->registerAttributes();
        $this->init();
    }

    protected function init() : void
    {

    }

    public function getAttributes() : ArrayDataCollection
    {
        return $this->attributes;
    }

    public function getAttributeManager() : AttributeManager
    {
        return $this->attributeManager;
    }

    abstract protected function registerAttributes() : void;
    abstract public function getLabel() : string;
    abstract public function getIcon() : ?Icon;

    public function getSubLabel() : string
    {
        return $this->subLabel;
    }

    public function getForm() : AttributeForm
    {
        return new AttributeForm($this->attributeManager);
    }
}
