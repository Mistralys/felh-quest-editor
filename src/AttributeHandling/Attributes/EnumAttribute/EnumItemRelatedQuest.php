<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling\Attributes\EnumAttribute;

class EnumItemRelatedQuest
{
    private string $questID;
    private string $label;

    public function __construct(string $questID, string $label='')
    {
        $this->questID = $questID;
        $this->label = $label;
    }

    public function getQuestID() : string
    {
        return $this->questID;
    }

    public function getLabel() : string
    {
        return $this->label;
    }
}
