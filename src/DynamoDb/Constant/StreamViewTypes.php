<?php

namespace Guillermoandrae\DynamoDb\Constant;

final class StreamViewTypes
{
    /**
     * @var string
     */
    const NEW_IMAGE = 'NEW_IMAGE';

    /**
     * @var string
     */
    const OLD_IMAGE = 'OLD_IMAGE';

    /**
     * @var string
     */
    const NEW_AND_OLD_IMAGES = 'NEW_AND_OLD_IMAGES';

    /**
     * @var string
     */
    const KEYS_ONLY = 'KEYS_ONLY';
}
