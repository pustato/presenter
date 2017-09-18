<?php

declare(strict_types=1);

namespace Pustato\Presenter;

use Pustato\Presenter\Contracts\PresentableContract;

class ArrayWrapper implements PresentableContract
{
    /** @var array */
    private $source = [];

    /**
     * ArrayWrapper constructor.
     *
     * @param array $source
     */
    public function __construct(array $source)
    {
        $this->source = $source;
    }

    /**
     * {@inheritdoc}
     */
    public function getPresentableAttribute(string $name)
    {
        return $this->source[$name] ?? null;
    }
}
