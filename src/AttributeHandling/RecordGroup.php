<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\AttributeHandling;

class RecordGroup extends BaseGroup
{
    /**
     * @var BaseRecord[]
     */
    private array $records = array();
    private string $recordTypeLabel = '';
    private int $minRecords = 0;
    private int $maxRecords = 0;

    public function addRecord(BaseRecord $record) : self
    {
        $this->records[] = $record;
        return $this;
    }

    public function setMinRecords(int $amount) : self
    {
        $this->minRecords = $amount;
        return $this;
    }

    public function setMaxRecords(int $amount) : self
    {
        $this->maxRecords = $amount;
        return $this;
    }

    /**
     * @return BaseRecord[]
     */
    public function getRecords() : array
    {
        return $this->records;
    }

    public function countRecords() : int
    {
        return count($this->records);
    }

    public function setRecordsAddable(string $itemLabel) : self
    {
        $this->recordTypeLabel = $itemLabel;
        return $this;
    }

    public function getRecordTypeLabel() : string
    {
        return $this->recordTypeLabel;
    }

    public function canAddRecords() : bool
    {
        return !empty($this->recordTypeLabel);
    }

    public function getMaxRecords() : int
    {
        return $this->maxRecords;
    }

    public function getMinRecords() : int
    {
        return $this->minRecords;
    }
}
