<?php
declare(strict_types=1);

namespace Cupcake\Panel;

use DebugKit\DebugPanel;

/**
 * phpcs:ignorefile
 */
class SystemPanel extends DebugPanel
{
    public $plugin = 'Cupcake';

    /**
     * @return string
     */
    public function title()
    {
        return "Cupcake";
    }

    /**
     * @return string
     */
    public function elementName()
    {
        return $this->plugin . '.debug_kit/system_panel';
    }
}
