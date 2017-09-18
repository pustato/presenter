<?php

declare(strict_types=1);

namespace Pustato\Presenter;

use Pustato\Presenter\Contracts\PresentableContract;

abstract class AbstractPresenter implements \ArrayAccess
{
    /** @var PresentableContract */
    private $presentable;

    /** @var array */
    private $attributes;

    /**
     * Presenter constructor.
     *
     * @param PresentableContract $presentable
     */
    public function __construct(PresentableContract $presentable)
    {
        $this->presentable = $presentable;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return ! is_null($this->offsetGet($offset));
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Return array of presentable attributes.
     *
     * @return array
     */
    abstract protected function attributes(): array;

    /**
     * Get presentable item instance.
     *
     * @return PresentableContract
     */
    protected function getPresentable(): PresentableContract
    {
        return $this->presentable;
    }

    /**
     * @return AbstractPresenter
     */
    public function refresh()
    {
        $this->attributes = $this->attributes();

        return $this;
    }

    /**
     * Get attribute.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (! $this->attributes) {
            $this->attributes = $this->attributes();
        }

        if (isset($this->attributes[$name])) {
            if (is_callable($this->attributes[$name])) {
                $this->attributes[$name] = $this->attributes[$name]($this->getPresentable());
            }

            return $this->attributes[$name];
        }

        return $this->getPresentable()->getPresentableAttribute($name);
    }
}
