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

    public function addRecord(BaseRecord $record) : self
    {
        $this->records[] = $record;
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
}
