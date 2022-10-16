<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\CommonRecords;

use Mistralys\FELHQuestEditor\AttributeHandling\BaseRecord;
use Mistralys\FELHQuestEditor\UI;
use Mistralys\FELHQuestEditor\UI\Icon;
use function AppLocalize\t;

class Objective extends BaseRecord
{
    protected function registerAttributes() : void
    {
        $settings = $this->attributeManager->addGroupSettings();

        $settings->registerIconImage('Icon', t('Icon'))
            ->selectIcon32();

        $settings->registerString('Text', t('Text'));
        $settings->registerBool('IsOptional', t('Is optional?'));
    }

    public function getLabel() : string
    {
        return t('Objective');
    }

    public function getIcon() : ?Icon
    {
        return UI::icon()->objective();
    }
}
