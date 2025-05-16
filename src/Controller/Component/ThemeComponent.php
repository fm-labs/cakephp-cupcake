<?php
declare(strict_types=1);

namespace Cupcake\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Core\Plugin;

/**
 * Class FrontendComponent
 *
 * @package Content\Controller\Component
 * @property \Cake\Controller\Component\FlashComponent $Flash
 */
class ThemeComponent extends Component
{
    public array $components = ['Flash'];

    /**
     * @var array
     */
    protected array $_defaultConfig = [
        'viewClass' => null,
        'theme' => null,
        'layout' => null,
    ];

    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        $theme = $this->_config['theme'] ?: Configure::read('Theme.name');
        $layout = $this->_config['layout'] ?: Configure::read('Theme.layout');
        $viewClass = $this->_config['viewClass'] ?: null;

        // check if theme plugin is loaded
        if ($theme && !Plugin::isLoaded($theme)) {
            $this->Flash->warning("Warning: Configured site theme '$theme' is not enabled.");
            $theme = null;
        }

        debug("Theme: $theme");

        $this->getController()->viewBuilder()->setClassName($viewClass);
        $this->getController()->viewBuilder()->setLayout($layout);
        $this->getController()->viewBuilder()->setTheme($theme);
        $this->getController()->viewBuilder()
            ->setVar('_frontend', compact('layout', 'theme', 'viewClass'));
    }
}
