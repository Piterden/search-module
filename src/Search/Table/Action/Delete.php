<?php namespace Anomaly\SearchModule\Search\Table\Action;

use Anomaly\Streams\Platform\Ui\Table\Component\Action\ActionHandler;
use Mmanos\Search\Index;
use Mmanos\Search\Search;

/**
 * Class Delete
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class Delete extends ActionHandler
{

    /**
     * Delete the selected entries.
     *
     * @param Search|Search $search
     * @param array         $selected
     */
    public function handle(Search $search, array $selected)
    {
        foreach ($selected as $id) {
            $search->delete($id);
        }

        if ($selected) {
            $this->messages->success(trans('streams::message.delete_success', ['count' => count($selected)]));
        }
    }
}
