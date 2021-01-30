<?php

namespace Uccello\EloquentTree\Support;

use Illuminate\Support\Facades\DB;
use Uccello\EloquentTree\Contracts\Tree;

/**
 * Class Observer
 *
 * @author  Adrian Skierniewski <adrian.skierniewski@gmail.com>
 * @package Gzero\EloquentTree\Model
 */
class Observer
{
    /**
     * When saving node we must set path and level
     *
     * @param Tree $model
     */
    public function saving(Tree $model)
    {
        if (!$model->exists and !in_array('path', array_keys($model->attributesToArray()), true)) {
            $model->{$model->getTreeColumn('path')}   = '';
            $model->{$model->getTreeColumn('parent')} = null;
            $model->{$model->getTreeColumn('level')}  = 0;
        }
    }

    /**
     * After mode was saved we're building node path
     *
     * @param Tree $model
     */
    public function saved(Tree $model)
    {
        if ($model->{$model->getTreeColumn('path')} === '') { // If we just save() new node
            $model->{$model->getTreeColumn('path')} = $model->getKey() . '/';
            DB::connection($model->getConnectionName())->table($model->getTable())
                ->where($model->getKeyName(), '=', $model->getKey())
                ->update(
                    array(
                        $model->getTreeColumn('path') => $model->{$model->getTreeColumn('path')}
                    )
                );
        }
    }
}
