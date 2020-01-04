<?php

namespace Guillermoandrae\Db\DynamoDb;

final class RequestOperators
{
    /**
     * @var string
     */
    const EQ = 'EQ';

    /**
     * @var string
     */
    const NE = 'NE';
    
    /**
     * @var string
     */
    const IN = 'IN';
    
    /**
     * @var string
     */
    const LTE = 'LTE';
    
    /**
     * @var string
     */
    const LT = 'LT';
    
    /**
     * @var string
     */
    const GTE = 'GTE';
    
    /**
     * @var string
     */
    const GT = 'GT';
    
    /**
     * @var string
     */
    const BETWEEN = 'BETWEEN';
    
    /**
     * @var string
     */
    const NOT_NULL = 'NOT_NULL';
    
    /**
     * @var string
     */
    const IS_NULL = 'NULL'; // because NULL is reserved
    
    /**
     * @var string
     */
    const CONTAINS = 'CONTAINS';
    
    /**
     * @var string
     */
    const NOT_CONTAINS = 'NOT_CONTAINS';
    
    /**
     * @var string
     */
    const BEGINS_WITH = 'BEGINS_WITH';
}
