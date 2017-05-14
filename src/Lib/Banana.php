<?php

namespace Banana\Lib;


use Cake\ORM\TableRegistry;

class Banana
{
    static $mailerClass = 'Cake\Mailer\Mailer';

    static public function getMailer()
    {
        return new self::$mailerClass();
    }

}