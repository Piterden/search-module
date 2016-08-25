<?php namespace Anomaly\SearchModule\Search\Listener;

use Anomaly\SearchModule\Search\Command\GetConfig;
use Anomaly\SearchModule\Search\Index\Command\DeleteEntry;
use Anomaly\SearchModule\Search\SearchManager;
use Anomaly\Streams\Platform\Entry\Event\EntryWasDeleted;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class DeleteItem
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class DeleteItem
{

    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param EntryWasDeleted $event
     */
    public function handle(EntryWasDeleted $event)
    {
        $entry = $event->getEntry();

        if ($config = $this->dispatch(new GetConfig($entry))) {
            $this->dispatch(new DeleteEntry($entry, $config));
        }
    }
}
