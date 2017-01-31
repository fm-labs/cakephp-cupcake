<?php

namespace Banana\Exception;

class ClassNotFoundException extends \Cake\Core\Exception\Exception
{
    protected $_messageTemplate = 'Class %s not found';
}