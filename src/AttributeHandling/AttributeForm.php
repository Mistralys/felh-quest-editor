<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling;

use AppUtils\JSHelper;
use AppUtils\OutputBuffering;
use Mistralys\FELHQuestEditor\UI;
use function AppUtils\sb;

class AttributeForm
{
    private AttributeManager $manager;

    public function __construct(AttributeManager $manager)
    {
        $this->manager = $manager;
    }

    public function display() : void
    {
        ?>
        <div class="accordion">
        <?php
        $groups = $this->manager->getGroups();
        $solo = count($groups) === 1;

        foreach($groups as $group)
        {
            $this->displayGroup($group, $solo);
        }

        ?>
        </div>
        <?php
    }

    public function render() : string
    {
        OutputBuffering::start();
        $this->display();
        return OutputBuffering::get();
    }

    private function displayGroup(BaseGroup $group, bool $isSingle) : void
    {
        if($group instanceof RecordGroup && $group->countRecords() === 0 && !$group->canAddRecords()) {
            return;
        }

        if($isSingle) {
            $this->displayGroupElements($group);
            return;
        }

        $jsID = JSHelper::nextElementID();

        ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="<?php echo $jsID ?>">
                    <button class="accordion-button collapsed"  data-bs-toggle="collapse" data-bs-target="#<?php echo $jsID ?>-toggle">
                        <?php echo $group->getIconLabel() ?>
                        <?php
                        $amount = $group->countRecords();
                        if($amount > 0) {
                            ?>
                            <span class="record-count">(<?php echo $amount ?>)</span>
                            <?php
                        }
                        ?>
                    </button>
                </h2>
                <div id="<?php echo $jsID ?>-toggle" class="accordion-collapse collapse">
                    <div class="accordion-body">
                    <?php

                    $description = $group->getDescription();
                    if(!empty($description)) {
                        ?>
                        <p class="group-description">
                            <?php echo $description ?>
                        </p>
                        <?php
                    }

                    $this->displayGroupElements($group);

                    ?>
                    </div>
                </div>
            </div>
        <?php
    }

    private function displayGroupElements(BaseGroup $group) : void
    {
        if($group instanceof AttributeGroup)
        {
            $attribs = $group->getAttributes();
            foreach($attribs as $attrib)
            {
                $attrib->display();
            }
        }

        if($group instanceof RecordGroup)
        {
            $records = $group->getRecords();
            $amount = count($records);
            $hasTitle = $group->getMaxRecords() !== 1;

            foreach($records as $idx => $record)
            {
                if($hasTitle)
                {
                    ?>
                    <h3 class="record-title">
                        <?php
                        echo sb()
                            ->add($record->getIcon())
                            ->add($record->getLabel());

                        $abstract = $record->getSubLabel();
                        if(!empty($abstract)) {
                            ?>
                            <span class="record-subtitle">
                                <?php echo $abstract?>
                            </span>
                            <?php
                        }
                        ?>
                    </h3>
                    <?php
                }
                ?>
                <div class="record-body <?php if(($idx+1) === $amount) {echo 'last';} ?>">
                    <?php
                    $record->getForm()->display();
                    ?>
                </div>
                <?php
            }

            if($group->canAddRecords())
            {
                ?>
                <div class="record-controls">
                    <button type="button" class="btn btn-secondary">
                        <?php UI::icon()->add()->display() ?>
                        Add new <?php echo $group->getRecordTypeLabel() ?>
                    </button>
                </div>
                <?php
            }
        }
    }
}
