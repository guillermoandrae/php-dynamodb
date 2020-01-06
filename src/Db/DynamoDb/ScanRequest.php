<?php

namespace Guillermoandrae\Db\DynamoDb;

final class ScanRequest extends AbstractSearchRequest
{
    private $scanFilter = [];

    public function setScanFilter(array $scanFilter): ScanRequest
    {
        $this->scanFilter = $scanFilter;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): array
    {
        $query = parent::get();
        if (!empty($this->scanFilter)) {
            $query['ScanFilter'] = $this->scanFilter;
        }
        return $query;
    }
}
