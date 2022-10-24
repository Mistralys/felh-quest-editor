<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

use AppUtils\Interface_Stringable;
use AppUtils\JSHelper;
use Mistralys\FELHQuestEditor\AttributeHandling\AttributeException;
use Mistralys\FELHQuestEditor\AttributeHandling\AttributeManager;
use Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;
use Mistralys\FELHQuestEditor\AttributeHandling\BaseAttribute;

class EnumItem implements EnumDependencyContainerInterface
{
    use EnumDependencyContainerTrait;

    protected string $id;
    protected string $label;
    protected EnumAttribute $attribute;
    protected string $description = '';
    private string $jsID;

    /**
     * @var array<string,EnumItemRelatedQuest>
     */
    protected array $relatedQuests = array();

    public function __construct(EnumAttribute $attribute, string $id, string $label)
    {
        $this->jsID = JSHelper::nextElementID();
        $this->attribute = $attribute;
        $this->id = $id;
        $this->label = $label;
    }

    public function getJSID() : string
    {
        return $this->jsID;
    }

    public function getEnumItem() : EnumItem
    {
        return $this;
    }

    /**
     * @return EnumAttribute
     */
    public function getAttribute() : EnumAttribute
    {
        return $this->attribute;
    }

    public function getDependentAttributeName() : string
    {
        return '';
    }

    public function isDependencyOptional() : bool
    {
        return true;
    }

    public function getDependentAttribute() : BaseAttribute
    {
        throw new AttributeException(
            'Enum items have no dependent attributes.'
        );
    }

    /**
     * Method chaining utility method: goes back to
     * the parent enum attribute after adding items
     * to the group.
     *
     * @return EnumAttribute
     */
    public function done() : EnumAttribute
    {
        return $this->attribute;
    }

    public function setDescription($description) : self
    {
        $this->description = (string)$description;
        return $this;
    }

    /**
     * @param string $questID
     * @param string|number|Interface_Stringable $label
     * @return $this
     */
    public function addRelatedQuest(string $questID, $label='') : self
    {
        if(!isset($this->relatedQuests[$questID])) {
            $this->relatedQuests[$questID] = new EnumItemRelatedQuest($questID, $label);
        }

        return $this;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getID() : string
    {
        return $this->id;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @return EnumItemRelatedQuest[]
     */
    public function getRelatedQuests() : array
    {
        return array_values($this->relatedQuests);
    }

    public function getAttributeManager() : AttributeManager
    {
        return $this->attribute->getAttributeManager();
    }
}
