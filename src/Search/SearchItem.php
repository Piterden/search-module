<?php namespace Anomaly\SearchModule\Search;

use Anomaly\SearchModule\Search\Command\GetSearchEntry;
use Anomaly\SearchModule\Search\Contract\SearchItemInterface;
use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Robbo\Presenter\PresentableInterface;

/**
 * Class SearchItem
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class SearchItem implements SearchItemInterface, PresentableInterface, Arrayable
{

    use DispatchesJobs;

    /**
     * The referenced entry.
     *
     * @var EntryInterface
     */
    protected $entry = null;

    /**
     * The stream slug.
     *
     * @var string
     */
    protected $stream;

    /**
     * The stream namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * The item title.
     *
     * @var string
     */
    protected $title;

    /**
     * The item keywords.
     *
     * @var array
     */
    protected $keywords;

    /**
     * @var string
     */
    protected $description;

    /**
     * The referenced entry ID.
     *
     * @var string
     */
    protected $entry_id;

    /**
     * Get the edit path.
     *
     * @var string
     */
    protected $edit_path;

    /**
     * Get the edit path.
     *
     * @var string
     */
    protected $view_path;

    /**
     * The referenced entry type.
     *
     * @var string
     */
    protected $entry_type;

    /**
     * Create a new SearchItem instance.
     */
    public function __construct(array $attributes = [])
    {
        $this->title       = array_get($attributes, 'title');
        $this->stream      = array_get($attributes, 'stream');
        $this->entry_id    = array_get($attributes, 'entry_id');
        $this->namespace   = array_get($attributes, 'namespace');
        $this->edit_path   = array_get($attributes, 'edit_path');
        $this->view_path   = array_get($attributes, 'view_path');
        $this->entry_type  = array_get($attributes, 'entry_type');
        $this->description = array_get($attributes, 'description');
        $this->keywords    = array_filter(explode(',', array_get($attributes, 'keywords', '')));
    }

    /**
     * Get the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the keywords.
     *
     * @return array
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Get the description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the edit path.
     *
     * @return string
     */
    public function getEditPath()
    {
        return $this->edit_path;
    }

    /**
     * Get the view path.
     *
     * @return string
     */
    public function getViewPath()
    {
        return $this->view_path;
    }

    /**
     * Get the entry ID.
     *
     * @return int
     */
    public function getEntryId()
    {
        return $this->entry_id;
    }

    /**
     * Get the entry type.
     *
     * @return string
     */
    public function getEntryType()
    {
        return $this->entry_type;
    }

    /**
     * Get the referenced entry.
     *
     * @return EntryInterface|null
     */
    public function getEntry()
    {
        if (!$this->entry) {
            return $this->entry = $this->dispatch(new GetSearchEntry($this));
        }

        return $this->entry;
    }

    /**
     * Get the stream.
     *
     * @return string
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * Get the stream name.
     *
     * @return string
     */
    public function getStreamName()
    {
        $entry = $this->getEntry();

        return $entry ? $entry->getStreamName() : null;
    }

    /**
     * Get the stream namespace.
     *
     * @return string
     */
    public function getStreamNamespace()
    {
        $entry = $this->getEntry();

        return $entry ? $entry->getStreamNamespace() : null;
    }

    /**
     * Get the namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Return a created presenter.
     *
     * @return SearchPresenter
     */
    public function getPresenter()
    {
        return new SearchPresenter($this);
    }

    /**
     * Return the object as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'title'       => $this->title,
            'stream'      => $this->stream,
            'keywords'    => $this->keywords,
            'namespace'   => $this->namespace,
            'description' => $this->description,
            'entry_id'    => $this->entry_id,
            'edit_path'   => $this->edit_path,
            'view_path'   => $this->view_path,
            'entry_type'  => $this->entry_type,
        ];
    }
}
