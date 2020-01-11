<?php

namespace Guillermoandrae\DynamoDb\Constant;

/**
 * When an item in the table is modified, StreamViewType determines what information is written to the stream for this
 * table.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#shape-streamspecification
 */
final class StreamViewTypes
{
    /**
     * The entire item, as it appears after it was modified, is written to the stream.
     *
     * @var string
     */
    public const NEW_IMAGE = 'NEW_IMAGE';

    /**
     * The entire item, as it appeared before it was modified, is written to the stream.
     *
     * @var string
     */
    public const OLD_IMAGE = 'OLD_IMAGE';

    /**
     * Both the new and the old item images of the item are written to the stream.
     *
     * @var string
     */
    public const NEW_AND_OLD_IMAGES = 'NEW_AND_OLD_IMAGES';

    /**
     * Only the key attributes of the modified item are written to the stream.
     *
     * @var string
     */
    public const KEYS_ONLY = 'KEYS_ONLY';
}
