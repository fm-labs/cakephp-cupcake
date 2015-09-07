<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 9/5/15
 * Time: 12:33 PM
 */

namespace Banana\Controller\Admin;


class SystemController extends AppController
{
    public function index()
    {
        $validationErrors = [];

        $constChecks = [
            'THEMES', 'DATA', 'SETTINGS', 'UPLOAD'
        ];

        $dirChecks = [
            TMP => 0777,
            CACHE => 0777,
            UPLOAD => 0777,
            SETTINGS => 0777,
        ];

        $pluginChecks = [
            'Attachment', 'Backend', 'SemanticUi', 'Settings', 'Tree', 'Upload', 'User'
        ];
    }
}
