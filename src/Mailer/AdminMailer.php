<?php
declare(strict_types=1);

namespace Banana\Mailer;

use Cake\Core\Configure;
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
     * @param \Cake\Mailer\Email|null $email
     */
    public function __construct(?Email $email = null)
    {
        parent::__construct($email);

        //@todo automatically setup admin email configuration, if not configured
        $profile = Configure::read('Banana.Email.adminProfile') ?: 'admin';
        $this->getMessage()
            ->setProfile($profile);
    }
}
