<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling;

use AppUtils\ArrayDataCollection;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\BoolAttribute;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\IntAttribute;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\MedallionImageAttribute;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\MultiLineStringAttribute;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\QuestImageAttribute;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\StringAttribute;

class AttributeGroup extends BaseGroup
{
    public const ERROR_ATTRIBUTE_NAME_NOT_FOUND = 119401;

    /**
     * @var array<string,BaseAttribute>
     */
    private array $attribDefs = array();
    private ArrayDataCollection $values;

    public function __construct(string $name, string $label, ArrayDataCollection $attribValues)
    {
        $this->values = $attribValues;

         parent::__construct($name, $label);
    }

    private function addAttribute(BaseAttribute $attribute) : void
    {
        $name = $attribute->getName();
        $this->attribDefs[$name] = $attribute;
        $attribute->setValue($this->values->getString($name));
    }

    public function registerString(string $name, string $label) : StringAttribute
    {
        $def = new StringAttribute($this, $name, $label);
        $this->addAttribute($def);
        return $def;
    }

    public function registerMultiLineString(string $name, string $label) : MultiLineStringAttribute
    {
        $def = new MultiLineStringAttribute($this, $name, $label);
        $this->addAttribute($def);
        return $def;
    }

    /**
     * @param string $name
     * @param string $label
     * @param array<string,string> $items Value => Label pairs.
     * @return EnumAttribute
     */
    public function registerEnum(string $name, string $label, array $items) : EnumAttribute
    {
        $def = new EnumAttribute($this, $name, $label, $items);
        $this->addAttribute($def);
        return $def;
    }

    public function registerBool(string $name, string $label) : BoolAttribute
    {
        $def = new BoolAttribute($this, $name, $label);
        $this->addAttribute($def);
        return $def;
    }

    public function registerInt(string $name, string $label) : IntAttribute
    {
        $def = new IntAttribute($this, $name, $label);
        $this->addAttribute($def);
        return $def;
    }

    public function registerMedallion(string $name, string $label) : MedallionImageAttribute
    {
        $def = new MedallionImageAttribute($this, $name, $label);
        $this->addAttribute($def);
        return $def;
    }

    public function registerQuestImage(string $name, string $label) : QuestImageAttribute
    {
        $def = new QuestImageAttribute($this, $name, $label);
        $this->addAttribute($def);
        return $def;
    }

    /**
     * @return BaseAttribute[]
     */
    public function getAttributes() : array
    {
        return $this->attribDefs;
    }

    public function countRecords() : int
    {
        return 0;
    }

    public function attributeNameExists(string $name) : bool
    {
        return isset($this->attribDefs[$name]);
    }

    /**
     * @param string $name
     * @return BaseAttribute
     * @throws AttributeManagerException
     */
    public function getAttributeByName(string $name) : BaseAttribute
    {
        if(isset($this->attribDefs[$name])) {
            return $this->attribDefs[$name];
        }

        throw new AttributeManagerException(
            'No such attribute name found.',
            sprintf(
                'Could not find the attribute [%s] in the manager.',
                $name
            ),
            self::ERROR_ATTRIBUTE_NAME_NOT_FOUND
        );
    }
}
