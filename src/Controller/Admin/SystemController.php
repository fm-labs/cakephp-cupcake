<?php
namespace Banana\Controller\Admin;

/**
 * Class SystemController
 * @package Banana\Controller\Admin
 * @deprecated Unused controller class
 */
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
