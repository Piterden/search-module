<?php namespace Anomaly\SearchModule\Search\Table;

use Anomaly\Streams\Platform\Ui\Table\TableBuilder;

/**
 * Class SearchTableBuilder
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class SearchTableBuilder extends TableBuilder
{

    /**
     * The table filters.
     *
     * @var array
     */
    protected $filters = [
        'term' => [
            'filter'      => 'input',
            'placeholder' => 'module::field.term.placeholder',
        ],
    ];

    /**
     * The table actions.
     *
     * @var array
     */
    protected $actions = [
        'delete' => [
            'handler' => 'Anomaly\SearchModule\Search\Table\Action\Delete',
        ],
    ];

    /**
     * The table buttons.
     *
     * @var array
     */
    protected $buttons = [
        'edit' => [
            'href' => '{entry.edit_path}',
        ],
        'view' => [
            'href'   => '{entry.view_path}',
            'target' => '_blank',
        ],
    ];

    /**
     * The table options.
     *
     * @var array
     */
    protected $options = [
        'table_view' => 'module::admin/search/table',
        'filters'    => [
            'filter_icon' => 'search',
            'filter_text' => 'module::button.search',
        ],
    ];

}
