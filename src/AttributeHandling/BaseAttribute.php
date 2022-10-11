<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling;

use AppUtils\ConvertHelper;
use AppUtils\Interface_Stringable;
use AppUtils\OutputBuffering;
use AppUtils\Request;
use Mistralys\FELHQuestEditor\UI;
use function AppUtils\sb;

abstract class BaseAttribute
{
    private bool $required = false;
    private string $name;
    private string $label;
    private string $description = '';
    private AttributeGroup $group;
    private string $elementName;
    private string $default = '';
    private ?string $value = null;

    public function __construct(AttributeGroup $group, string $name, string $label)
    {
        $this->group = $group;
        $this->name = $name;
        $this->label = $label;
        $this->elementName = strtolower($this->group->getName().'_'.$this->getName());

        $this->init();
    }

    protected function init() : void
    {

    }

    public function getGroup() : AttributeGroup
    {
        return $this->group;
    }

    public function getElementName() : string
    {
        return $this->elementName;
    }

    public function getElementID() : string
    {
        return ConvertHelper::string2shortHash(sprintf(
            'el-%s',
            $this->getName()
        ));
    }

    public function isRequired() : bool
    {
        return $this->required;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getDescription() : string
    {
        return (string)sb()
            ->ifTrue(
                UI::isShowXMLTagsEnabled(),
                sb()
                    ->add('XML tag:')
                    ->spanned(htmlspecialchars('<'.$this->getName().'>'), array('monospace'))
                    ->nl()
            )
            ->add($this->description);
    }

    public function setRequired() : self
    {
        $this->required = true;
        return $this;
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

    public function display() : void
    {
        echo $this->render();
    }

    public function render() : string
    {
        OutputBuffering::start();
        $this->displayElementScaffold();
        $html = OutputBuffering::get();

        return str_replace(
            '{ELEMENT}',
            $this->renderElement(),
            $html
        );
    }

    protected function displayElementScaffold() : void
    {
        ?>
        <div class="mb-3">
            <label for="<?php echo $this->getElementID() ?>" class="form-label">
                <?php echo $this->getLabel() ?>
            </label>
            {ELEMENT}
            <div class="element-description">
                <?php echo $this->getDescription() ?>
            </div>
        </div>
        <?php
    }

    protected function renderElement() : string
    {
        OutputBuffering::start();
        $this->displayElement();
        return OutputBuffering::get();
    }

    abstract protected function displayElement() : void;

    public function getDefault() : string
    {
        return $this->default;
    }

    public function setDefault(string $default) : self
    {
        $this->default = $default;
        return $this;
    }

    public function getRawValue() : string
    {
        return $this->value ?? $this->getDefault();
    }

    public function getFormValue() : string
    {
        $value = (string)Request::getInstance()->getParam($this->getElementName());

        if($value !== '') {
            return $value;
        }

        return $this->getRawValue();
    }

    public function setValue(string $value) : self
    {
        $this->value = $value;
        return $this;
    }
}
