<?php namespace Anomaly\SearchModule;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Anomaly\Streams\Platform\Application\Application;
use Illuminate\Contracts\Config\Repository;

class SearchModuleServiceProvider extends AddonServiceProvider
{

    /**
     * The addon plugins.
     *
     * @var array
     */
    protected $plugins = [
        'Anomaly\SearchModule\SearchModulePlugin',
    ];

    /**
     * The addon commands.
     *
     * @var array
     */
    protected $commands = [
        'Anomaly\SearchModule\Search\Console\Destroy',
        'Anomaly\SearchModule\Search\Console\Rebuild',
    ];

    /**
     * The addon routes.
     *
     * @var array
     */
    protected $routes = [
        'admin/search'         => 'Anomaly\SearchModule\Http\Controller\Admin\SearchController@index',
        'admin/search/rebuild' => 'Anomaly\SearchModule\Http\Controller\Admin\SearchController@rebuild',
    ];

    /**
     * Boot the service provider.
     *
     * @param Repository  $config
     * @param Application $application
     */
    public function boot(Repository $config, Application $application)
    {
        $config->set('search', $config->get('anomaly.module.search::engine'));

        $config->set(
            'search.connections.zend.path',
            str_replace(
                'storage::',
                $application->getStoragePath() . '/',
                $config->get('search.connections.zend.path')
            )
        );
    }
}
