<?php

declare(strict_types=1);

namespace Pustato\Presenter\Contracts;

interface PresentableContract
{
    /**
     * Get presentable attribute value by name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getPresentableAttribute(string $name);
}
