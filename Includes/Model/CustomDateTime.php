<?php

namespace Includes\Model;

class CustomDateTime extends \DateTime
{
    public function __toString(): string
    {
        return $this->format('Y-m-d H:i:s');
    }
}