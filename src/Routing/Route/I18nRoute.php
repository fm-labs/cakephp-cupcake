<?php
namespace Banana\Routing\Route;

use Cake\I18n\I18n;
use Cake\Routing\Route\Route;

class I18nRoute extends Route
{
    public function match(array $url, array $context = [])
    {
        //debug($url);
        if (!isset($url['locale'])) {
            $url['locale'] = I18n::locale();
        }
        $result = parent::match($url, $context);
        //debug($result);
        return $result;
    }
}
