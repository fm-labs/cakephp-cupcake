<?php
declare(strict_types=1);

namespace Cupcake\Panel;

use DebugKit\DebugPanel;

/**
 * phpcs:ignorefile
 */
class SystemPanel extends DebugPanel
{
    public string $plugin = 'Cupcake';

    /**
     * @return string
     */
    public function title(): string
    {
        return "Cupcake";
    }

    /**
     * @return string
     */
    public function elementName(): string
    {
        return $this->plugin . '.debug_kit/system_panel';
    }
}
