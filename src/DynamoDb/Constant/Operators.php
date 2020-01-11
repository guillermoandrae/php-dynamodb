<?php

namespace Guillermoandrae\DynamoDb\Constant;

/**
 * Available comparison operators.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Query.html#FilteringResults
 */
final class Operators
{
    /**
     * The equality comparison operator.
     *
     * @var string
     */
    public const EQ = 'EQ';

    /**
     * The inequality comparison operator.
     *
     * @var string
     */
    public const NE = 'NE';

    /**
     * The less-than-or-equal-to comparison operator.
     *
     * @var string
     */
    public const LTE = 'LTE';

    /**
     * The less-than comparison operator.
     *
     * @var string
     */
    public const LT = 'LT';

    /**
     * The greater-than-or-equal-to comparison operator.
     *
     * @var string
     */
    public const GTE = 'GTE';

    /**
     * The greater-than comparison operator.
     *
     * @var string
     */
    public const GT = 'GT';

    /**
     * The between comparison operator.
     *
     * @var string
     */
    public const BETWEEN = 'BETWEEN';

    /**
     * The not-null comparison operator.
     *
     * @var string
     */
    public const NOT_NULL = 'NOT_NULL';

    /**
     * The null comparison operator.
     *
     * @var string
     */
    public const IS_NULL = 'NULL'; // because NULL is reserved

    /**
     * The contains comparison operator.
     *
     * @var string
     */
    public const CONTAINS = 'CONTAINS';

    /**
     * The not-contains comparison operator.
     *
     * @var string
     */
    public const NOT_CONTAINS = 'NOT_CONTAINS';

    /**
     * The begins-with comparison operator.
     *
     * @var string
     */
    public const BEGINS_WITH = 'BEGINS_WITH';
}
