<?php namespace Anomaly\SearchModule\Search\Command;

use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Illuminate\Contracts\Config\Repository;

/**
 * Class GetConfig
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class GetConfig
{

    /**
     * The entry instance.
     *
     * @var EntryInterface
     */
    protected $entry;

    /**
     * Create a new GetConfig instance.
     *
     * @param EntryInterface $entry
     */
    public function __construct(EntryInterface $entry)
    {
        $this->entry = $entry;
    }

    /**
     * Handle the command.
     *
     * @param  ModuleCollection $modules
     * @param  Repository       $config
     * @return mixed|null
     */
    public function handle(ModuleCollection $modules, Repository $config)
    {
        $default = $config->get('anomaly.module.search::' . ($key = 'search.' . get_class($this->entry)));

        /* @var Module $module */
        foreach ($modules->withConfig($key) as $module) {
            return $default ?: $config->get($module->getNamespace($key));
        }

        return $default;
    }
}
