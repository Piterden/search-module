<?php namespace Anomaly\SearchModule;

use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Application\Application;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;

/**
 * Class SearchModule
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class SearchModule extends Module
{

    /**
     * The navigation icon.
     *
     * @var string
     */
    protected $icon = 'search';

    /**
     * The module sections.
     *
     * @var array
     */
    protected $sections = [
        'index' => [
            /*'buttons' => [
                [
                    'icon' => 'refresh',
                    'href' => 'admin/search/rebuild',
                    'text' => 'module::button.rebuild'
                ]
            ]*/
        ],
    ];

    /**
     * Fired just before module is installed.
     *
     * @param Application $application
     * @param Filesystem  $files
     */
    public function onInstalling(Application $application, Filesystem $files)
    {
        if (is_dir($path = $application->getStoragePath('search/zend'))) {
            $files->deleteDirectory($application->getStoragePath('search/zend'));
        }
    }

    /**
     * Fired after module is installed.
     *
     * @param Application $application
     */
    public function onInstalled(Application $application)
    {
        if (!is_dir($path = $application->getStoragePath('search/zend'))) {
            mkdir($application->getStoragePath('search/zend'), 0777, true);
        }
    }

    /**
     * Fired after module is uninstalled.
     *
     * @param Application $application
     * @param Filesystem  $files
     */
    public function onUninstalled(Application $application, Filesystem $files)
    {
        $files->deleteDirectory($application->getStoragePath('search/zend'));
    }
}
