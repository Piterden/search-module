<?php namespace Anomaly\SearchModule\Search\Console;

use Anomaly\SearchModule\Search\Command\GetConfig;
use Anomaly\SearchModule\Search\Index\IndexManager;
use Anomaly\Streams\Platform\Entry\Contract\EntryRepositoryInterface;
use Anomaly\Streams\Platform\Entry\EntryModel;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Mmanos\Search\Search;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class Rebuild
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class Rebuild extends Command
{

    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'search:rebuild';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild a searchable stream(s).';

    /**
     * Execute the console command.
     *
     * @param Search                    $search
     * @param IndexManager              $manager
     * @param StreamRepositoryInterface $streams
     */
    public function fire(
        Search $search,
        IndexManager $manager,
        StreamRepositoryInterface $streams,
        EntryRepositoryInterface $repository
    ) {
        $stream = $this->argument('stream');

        if (!strpos($stream, '.')) {
            $stream = $stream . '.' . $stream;
        }

        list($namespace, $slug) = explode('.', $stream);

        if (!$stream = $streams->findBySlugAndNamespace($slug, $namespace)) {

            $this->error('Stream [' . $this->argument('stream') . '] could not be found.');

            return;
        }

        /* @var EntryModel $model */
        $repository->setModel($model = $stream->getEntryModel());

        /*
         * If the stream is empty we can't
         * really index it.
         */
        if (!$entry = $repository->first()) {
            $this->error('Stream [' . $this->argument('stream') . '] is empty.');
        }

        /*
         * If the stream does not have a valid
         * search configuration then we don't
         * know how to insert it's entries.
         */
        if (!$config = $this->dispatch(new GetConfig($entry))) {
            $this->error('Stream [' . $this->argument('stream') . '] does not have a search configuration.');
        }

        $this->info('Deleting ' . $this->argument('stream'));

        $search->search('stream', $stream->getSlug())->search('namespace', $stream->getNamespace())->delete();

        $this->info('Rebuilding ' . $this->argument('stream'));

        $this->output->progressStart($repository->count());

        foreach ($repository->all() as $entry) {

            $manager->insert($entry, $config);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['stream', InputArgument::REQUIRED, 'The stream to rebuild: i.e. pages.pages'],
        ];
    }
}
