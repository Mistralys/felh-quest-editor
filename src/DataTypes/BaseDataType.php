<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\DataTypes;

abstract class BaseDataType
{
    private string $id;
    private string $label;

    public function __construct(string $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getID() : string
    {
        return $this->id;
    }
}
