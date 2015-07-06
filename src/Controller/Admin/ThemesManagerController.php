<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/24/15
 * Time: 4:04 PM
 */

namespace Banana\Controller\Admin;


use Cake\Core\App;
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
use App\Model\Table\ThemesTable;

/**
 * Class ThemesManagerController
 * @package App\Controller\Admin
 *
 * @property ThemesTable $Themes
 */
class ThemesManagerController extends AppController
{
    public function index()
    {
        // get list of available themes in THEMES directory
        $dir = new Folder(THEMES);
        list($themeNames,) = $dir->read();

        $themes = [];
        foreach ($themeNames as $theme) {
            $themes[] = [
                'name' => $theme,
                'loaded' => Plugin::loaded($theme),
            ];
        }
        $this->set('themesAvailable', $themes);

        // get installed themes from db
        $this->loadModel("Themes");

        $themes = $this->Themes->find()->all();
        $this->set('themesInstalled', $themes);
    }

    public function details($themeName)
    {
        $themePath = THEMES . $themeName . DS;
        $folder = new Folder($themePath);

        $folder->cd($themePath . "src/Template/Layout");
        list(,$layoutTemplates) = $folder->read();

        $themeDetails = [
            'name' => $themeName,
            'path' => $themePath,
            'layout_templates' => $layoutTemplates,
        ];

        $this->set('themeDetails', $themeDetails);
    }
}
