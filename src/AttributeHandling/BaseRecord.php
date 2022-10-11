<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling;

use AppUtils\ArrayDataCollection;

abstract class BaseRecord
{
    protected AttributeManager $attributeManager;
    protected ArrayDataCollection $attributes;

    public function __construct(ArrayDataCollection $attribs)
    {
        $this->attributes = $attribs;
        $this->attributeManager = new AttributeManager($this->attributes);

        $this->registerAttributes();
    }

    public function getAttributeManager() : AttributeManager
    {
        return $this->attributeManager;
    }

    abstract protected function registerAttributes() : void;
    abstract public function getLabel() : string;

    public function getForm() : AttributeForm
    {
        return new AttributeForm($this->attributeManager);
    }
}
