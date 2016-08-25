<?php namespace Anomaly\SearchModule\Search\Index;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Mmanos\Search\Index;
use Mmanos\Search\Search;

/**
 * Class IndexManager
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class IndexManager
{

    use DispatchesJobs;

    /**
     * The index input reader.
     *
     * @var IndexInput
     */
    protected $input;

    /**
     * The search utility.
     *
     * @var Search
     */
    protected $search;

    /**
     * Create a new IndexManager instance.
     *
     * @param IndexInput    $input
     * @param Search|Search $search
     */
    public function __construct(IndexInput $input, Search $search)
    {
        $this->input  = $input;
        $this->search = $search;
    }

    /**
     * Insert a search index item.
     *
     * @param EntryInterface $entry
     * @param                $config
     */
    public function insert(EntryInterface $entry, $config)
    {
        $config = $this->input->read($entry, $config);

        $this->search->delete(array_get($config, 'id'));

        if (array_get($config, 'enabled', true) === false) {
            return;
        }

        $this->search->insert(
            array_get($config, 'id'),
            array_get($config, 'fields'),
            array_get($config, 'extra')
        );
    }

    /**
     * Delete the item from the search index.
     *
     * @param EntryInterface $entry
     * @param                $config
     */
    public function delete(EntryInterface $entry, $config)
    {
        $config = $this->input->read($entry, $config);

        $this->search->delete(array_get($config, 'id'));
    }

    /**
     * Destroy the index.
     */
    public function destroy()
    {
        $this->search->deleteIndex();
    }
}
