<?php namespace Anomaly\SearchModule;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Anomaly\Streams\Platform\Application\Application;
use Illuminate\Contracts\Config\Repository;

/**
 * Class SearchModuleServiceProvider
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class SearchModuleServiceProvider extends AddonServiceProvider
{

    /**
     * Additional addon providers.
     *
     * @var array
     */
    protected $providers = [
        'Mmanos\Search\SearchServiceProvider',
    ];

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
     * The addon listeners.
     *
     * @var array
     */
    protected $listeners = [
        'Anomaly\Streams\Platform\Entry\Event\EntryWasSaved'   => [
            'Anomaly\SearchModule\Search\Listener\InsertItem',
        ],
        'Anomaly\Streams\Platform\Entry\Event\EntryWasDeleted' => [
            'Anomaly\SearchModule\Search\Listener\DeleteItem',
        ],
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
