<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\CommonRecords;

use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\AttributeHandling\RecordGroup;
use Mistralys\FELHQuestEditor\UI;
use Mistralys\FELHQuestEditor\UI\Icon;
use function AppLocalize\t;

class Treasure extends BaseRecord implements GameModifierContainerInterface
{
    public const TAG_NAME = 'Treasure';

    use GameModifierContainerTrait;

    private RecordGroup $modifiersGroup;

    protected function registerAttributes() : void
    {
        $this->modifiersGroup = $this->attributeManager->addRecordGroup('modifiers', 'Modifiers')
            ->setRecordsAddable(t('game modifier'))
            ->setIcon(UI::icon()->modifiers());
    }

    public function getGameModifiersGroup() : RecordGroup
    {
        return $this->modifiersGroup;
    }

    public function getLabel() : string
    {
        return t('Treasure');
    }

    public function getIcon() : ?Icon
    {
        return UI::icon()->treasure();
    }
}
