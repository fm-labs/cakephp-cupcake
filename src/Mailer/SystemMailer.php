<?php
declare(strict_types=1);

namespace Cupcake\Mailer;

use Cake\Event\EventInterface;
use Cake\Mailer\Mailer;
use Exception;

class SystemMailer extends Mailer
{
    public function exception(Exception $ex)
    {
        $this
            ->setProfile('admin')
            ->setSubject('System exception')
            ->viewBuilder()
                ->setLayout('system')
                ->setTemplate('exception')
                ->setVar('exception', $ex);

        return $this;
    }

    public function event(EventInterface $event)
    {
        $this
            ->setProfile('admin')
            ->setSubject('System event')
            ->viewBuilder()
                ->setLayout('system')
                ->setTemplate('event')
                ->setVar('event', $event);

        return $this;
    }
}
