<?php

namespace Banana\Mailer;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;

/**
 * Class AdminMailer
 *
 * @package Banana\Mailer
 */
class AdminMailer extends Mailer
{

    /**
     * @param Email|null $email
     */
    public function __construct(Email $email = null)
    {
        parent::__construct($email);

        //@todo automatically setup admin email configuration, if not configured
        $profile = (Configure::read('Banana.Email.adminProfile')) ?: 'admin';
        $this->_email->setProfile($profile);
    }
}
