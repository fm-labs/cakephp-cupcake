<?php

namespace Cupcake\Health\Check;

use Cupcake\Health\HealthCheckGeneratorInterface;
use Cupcake\Health\HealthStatus;

class PhpExtensionCheck implements HealthCheckGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function getHealthStatus(): \Generator
    {
        // intl
        if (!extension_loaded('intl')) {
            yield HealthStatus::crit('You must enable the intl extension to use CakePHP.');
        } elseif (version_compare(INTL_ICU_VERSION, '50.1', '<')) {
            yield HealthStatus::crit('ICU >= 50.1 is needed to use CakePHP. Please update the `libicu` package of your system.');
        } else {
            yield HealthStatus::ok(__d('admin', 'You are using supported ICU version {0}', INTL_ICU_VERSION));
        }

        // required ext
        $requiredExt = ['mbstring', 'pdo'];
        foreach($requiredExt as $ext) {
            if (!extension_loaded($ext)) {
                yield HealthStatus::crit(__d('admin', 'You must enable the {0} PHP extension to use CakePHP.', $ext));
            } else {
                yield HealthStatus::ok(__d('admin', 'The required PHP extension {0} is loaded', $ext));
            }
        }

        // optional ext
        $optionalExt = ['json', 'soap', 'curl', 'gd', 'xdebug'];
        foreach($optionalExt as $ext) {
            if (!extension_loaded($ext)) {
                yield HealthStatus::warn(__d('admin', 'The PHP extension {0} is not loaded.', $ext));
            } else {
                yield HealthStatus::ok(__d('admin', 'The optional PHP extension {0} is loaded', $ext));
            }
        }
    }
}