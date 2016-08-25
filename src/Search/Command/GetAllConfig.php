<?php namespace Anomaly\SearchModule\Search\Command;

use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Illuminate\Contracts\Config\Repository;

/**
 * Class GetAllConfig
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class GetAllConfig
{

    /**
     * Handle the command.
     *
     * @param  ModuleCollection $modules
     * @param  Repository       $config
     * @return mixed|null
     */
    public function handle(ModuleCollection $modules, Repository $config)
    {
        $items = [];

        /* @var Module $module */
        foreach ($modules->withConfig('search') as $module) {
            foreach ($config->get($module->getNamespace('search')) as $model => $configuration) {
                $items[$model] = $configuration;
            }
        }

        return $items;
    }
}
