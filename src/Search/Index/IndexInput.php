<?php namespace Anomaly\SearchModule\Search\Index;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Support\Evaluator;
use Anomaly\Streams\Platform\Support\Value;

/**
 * Class IndexInput
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class IndexInput
{

    /**
     * The value utility.
     *
     * @var Value
     */
    protected $value;

    /**
     * The evaluator utility.
     *
     * @var Evaluator
     */
    protected $evaluator;

    /**
     * Create a new IndexInput instance.
     *
     * @param Value     $value
     * @param Evaluator $evaluator
     */
    public function __construct(Value $value, Evaluator $evaluator)
    {
        $this->value     = $value;
        $this->evaluator = $evaluator;
    }

    /**
     * Handle the index process.
     *
     * @param  EntryInterface $entry
     * @param                 $config
     * @return array
     */
    public function read(EntryInterface $entry, $config)
    {
        $config = $this->evaluator->evaluate($config, compact('entry'));

        $this->setId($entry, $config);
        $this->setEnabled($entry, $config);
        $this->setStream($entry, $config);
        $this->setFields($entry, $config);
        $this->setPaths($entry, $config);
        $this->setEntry($entry, $config);
        $this->setMandatory($entry, $config);
        $this->appendExtra($entry, $config);

        return $config;
    }

    /**
     * Set the index ID.
     *
     * @param EntryInterface $entry
     * @param                $config
     */
    protected function setId(EntryInterface $entry, &$config)
    {
        $config['id'] = sha1(get_class($entry) . $entry->getId());
    }

    /**
     * Set the enabled flag.
     *
     * @param EntryInterface $entry
     * @param                $config
     */
    protected function setEnabled(EntryInterface $entry, &$config)
    {
        $config['enabled'] = $this->value->make(array_get($config, 'enabled', true), $entry);
    }

    /**
     * Set the stream.
     *
     * @param EntryInterface $entry
     * @param                $config
     */
    protected function setStream(EntryInterface $entry, &$config)
    {
        $config['fields']['stream']    = $entry->getStreamSlug();
        $config['fields']['namespace'] = $entry->getStreamNamespace();
    }

    /**
     * Set the fields.
     *
     * @param EntryInterface $entry
     * @param                $config
     */
    protected function setFields(EntryInterface $entry, &$config)
    {
        foreach (array_get($config, 'fields', []) as $field => $value) {
            $config['fields'][$field] = $this->value->make($value, $entry);
        }
    }

    /**
     * Set the meta paths.
     *
     * @param EntryInterface $entry
     * @param                $config
     */
    protected function setPaths(EntryInterface $entry, &$config)
    {
        $config['extra']['edit_path'] = $this->value->make(array_pull($config, 'edit_path'), $entry);
        $config['extra']['view_path'] = $this->value->make(array_pull($config, 'view_path'), $entry);
    }

    /**
     * Set the entry.
     *
     * @param EntryInterface $entry
     * @param                $config
     */
    protected function setEntry(EntryInterface $entry, &$config)
    {
        $config['extra']['entry_id']   = $entry->getId();
        $config['extra']['entry_type'] = get_class($entry);
    }

    /**
     * Set the default values.
     *
     * @param EntryInterface $entry
     * @param                $config
     */
    protected function setMandatory(EntryInterface $entry, &$config)
    {
        $config['fields']['title']       = $this->value->make(array_pull($config, 'title'), $entry);
        $config['fields']['description'] = $this->value->make(array_pull($config, 'description'), $entry);
        $config['fields']['keywords']    = implode(
            ',',
            (array)$this->value->make(array_pull($config, 'keywords'), $entry)
        );
    }

    /**
     * Append extra data.
     *
     * @param EntryInterface $entry
     * @param                $config
     */
    protected function appendExtra(EntryInterface $entry, &$config)
    {
        foreach ($config['fields'] as $field => $value) {
            $config['extra'][$field] = $value;
        }

        foreach (array_get($config, 'extra', []) as $key => $value) {
            if (!isset($config['extra'][$key])) {
                $config['extra'][$key] = $this->value->make($value, $entry);
            }
        }
    }
}
