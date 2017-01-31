<?php

namespace Banana\Model;


interface EntityTypeHandlerInterface
{
    /**
     * @return EntityTypeInterface
     */
    public function handler();
}