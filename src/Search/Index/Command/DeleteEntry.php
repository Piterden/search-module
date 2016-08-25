<?php namespace Anomaly\SearchModule\Search\Index\Command;

use Anomaly\SearchModule\Search\Index\IndexHandler;
use Anomaly\SearchModule\Search\Index\IndexManager;
use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;


/**
 * Class DeleteEntry
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class DeleteEntry
{

    /**
     * The entry instance.
     *
     * @var EntryInterface
     */
    protected $entry;

    /**
     * The search config.
     *
     * @var mixed
     */
    protected $config;

    /**
     * Create a new DeleteEntry instance.
     *
     * @param EntryInterface $entry
     * @param mixed          $config
     */
    public function __construct(EntryInterface $entry, $config)
    {
        $this->entry  = $entry;
        $this->config = $config;
    }

    /**
     * Handle the command.
     *
     * @param IndexManager $manager
     */
    public function handle(IndexManager $manager)
    {
        $manager->delete($this->entry, $this->config);
    }

}
