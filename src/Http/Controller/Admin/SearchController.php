<?php namespace Anomaly\SearchModule\Http\Controller\Admin;

use Anomaly\SearchModule\Search\Table\SearchTableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;

/**
 * Class SearchController
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class SearchController extends AdminController
{

    /**
     * Return the index.
     *
     * @param  SearchTableBuilder                         $table
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SearchTableBuilder $table)
    {
        return $table->render();
    }

    public function rebuild()
    {
        die("Rebuilding...");
    }
}
