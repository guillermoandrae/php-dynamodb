<?php

namespace Guillermoandrae\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Common\ArrayableInterface;

abstract class AbstractRequest implements ArrayableInterface, RequestInterface
{
    /**
     * @var Marshaler The Marshaler.
     */
    protected $marshaler;

    /**
     * Registers the Marshaler and table name with this object.
     *
     * @param Marshaler $marshaler The Marshaler.
     */
    public function __construct(Marshaler $marshaler)
    {
        $this->setMarshaler($marshaler);
    }

    /**
     * {@inheritDoc}
     */
    final public function setMarshaler(Marshaler $marshaler): RequestInterface
    {
        $this->marshaler = $marshaler;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    final public function toArray(): array
    {
        return $this->get();
    }
}
