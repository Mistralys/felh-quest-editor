<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling;

use AppUtils\ArrayDataCollection;

class AttributeManager
{
    public const ERROR_ATTRIBUTE_NAME_NOT_FOUND = 119301;

    private ArrayDataCollection $attribs;

    /**
     * @var array<string,BaseGroup>
     */
    private array $groups = array();

    public function __construct(ArrayDataCollection $attribs)
    {
        $this->attribs = $attribs;
    }

    public function addGroup(string $name, string $label) : AttributeGroup
    {
        $group = new AttributeGroup($name, $label, $this->attribs);
        $this->registerGroup($group);
        return $group;
    }

    /**
     * @return BaseGroup[]
     */
    public function getGroups() : array
    {
        return array_values($this->groups);
    }

    public function getForm() : AttributeForm
    {
        return new AttributeForm($this);
    }

    public function addRecordGroup(string $name, string $label) : RecordGroup
    {
        $group = new RecordGroup($name, $label);
        $this->registerGroup($group);
        return $group;
    }

    private function registerGroup(BaseGroup $group) : void
    {
        $this->groups[$group->getName()] = $group;
    }

    /**
     * @param string $name
     * @return BaseAttribute
     * @throws AttributeManagerException
     */
    public function getAttributeByName(string $name) : BaseAttribute
    {
        $groups = $this->getGroups();

        foreach($groups as $group)
        {
            if($group instanceof AttributeGroup && $group->attributeNameExists($name)) {
                return $group->getAttributeByName($name);
            }
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
