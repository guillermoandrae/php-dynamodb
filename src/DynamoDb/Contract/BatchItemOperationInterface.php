<?php

namespace Guillermoandrae\DynamoDb\Contract;

interface BatchItemOperationInterface extends OperationInterface
{
    public function setRequestItems(array $requestItems): BatchItemOperationInterface;
}
