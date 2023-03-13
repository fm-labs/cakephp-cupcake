<?php

namespace Cupcake\Mailer;

use Cake\Mailer\Mailer;

class SystemMailer extends Mailer
{
    public function exception(\Exception $ex)
    {
        $this
            ->setProfile('admin')
            ->setSubject("System exception")
            ->viewBuilder()
                ->setLayout('system')
                ->setTemplate('exception')
                ->setVar('exception', $ex)
            ;
        return $this;
    }

    public function event(\Cake\Event\EventInterface $event)
    {
        $this
            ->setProfile('admin')
            ->setSubject("System event")
            ->viewBuilder()
                ->setLayout('system')
                ->setTemplate('event')
                ->setVar('event', $event)
        ;
        return $this;
    }
}
