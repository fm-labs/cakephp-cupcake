<?php
namespace Banana\View;

use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Event\EventManager;
use Cake\Utility\Inflector;
use Cake\View\Exception\MissingCellException;
use Cake\Core\Exception\Exception;
use Cake\View\View;

/**
 * Class FrontendView
 *
 * @package App\View
 */
class FrontendView extends AppView
{
    //use ModuleTrait;

    /**
     * @param Request $request
     * @param Response $response
     * @param EventManager $eventManager
     * @param array $viewOptions
     */
    public function __construct(
        Request $request = null,
        Response $response = null,
        EventManager $eventManager = null,
        array $viewOptions = []
    ) {
        parent::__construct($request, $response, $eventManager, $viewOptions);

        $this->helpers()->load('Banana.Frontend', []);
    }


    public function __section($name)
    {
        //@TODO
        return sprintf("[SECTION: %s]", $name);

        // legacy
        //@TODO section cell caching


    }

    public function section($name)
    {
        // a view block with contents will be preferred
        $content = $this->fetch($name);
        if ($content) {
            return $content;
        }

        // otherwise render section cell
        $cellData = [];
        $cellOptions = ['name' => $name];

        return $this->cell('Banana.Section', $cellData, $cellOptions);
    }

    public function render($view = null, $layout = null)
    {
        return parent::render($view, $layout);
    }

}
