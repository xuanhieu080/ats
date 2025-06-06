<?php

namespace App\Helpers;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PDO;

/**
 * Class AbstractModel
 */
abstract class AbstractModel
{
    protected $model;
    protected string $modelName;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Get empty model.
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getModelName(): string
    {
        return $this->modelName;
    }

    /**
     * Refresh model to be clean
     */
    /**
     * Get table name.
     *  //show all data in table
     *
     * @return string
     */
    public function getTable(): string
    {
        return $this->model->getTable();
    }

    /**
     * Make a new instance of the entity to query on.
     *
     * @param array $with
     * @return Builder
     */
    public function make(array $with = []): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model->with($with);
    }

    /**
     * Find a single entity by key value.
     *
     * @param string $key
     * @param string $value
     * @param array $with
     * @return Model|Builder|null
     */
    public function getFirstBy(string $key, string $value, array $with = []): Model|Builder|null
    {
        $query = $this->make($with);

        //$this->model->filterData($query);

        return $query->where($key, '=', $value)->first();
    }

    /**
     * Retrieve model by id
     * regardless of status.
     *
     * @param int $id model ID
     * @param array $with
     * @param string $filter
     * @param bool $filterCus
     * @return Model
     */
    public function byId(int $id, array $with = [], string $filter = '', bool $filterCus = false)
    {
        $query = $this->make($with)->where($this->model->getKeyName(), $id);
        if ($filter == 'current') {
            $this->model->filterData($query, $filterCus);
        } elseif ($filter == 'filterIn') {
            $this->model->filterDataIn($query, $filterCus);
        }
        $model = $query->firstOrFail();

        return $model;
    }

    /**
     * Create a new model.
     *
     * @param array $data
     *
     * @return mixed Model or false on error during save
     */
   

    /**
     * @param Model $model
     * @param array $data
     * @return false|Model
     */
    public function updateItem(Model $model, array $data): Model|bool
    {
        $data['updated_by'] = auth()->id();
        $model->fill($data);

        if ($model->save()) {
            return $model;
        }

        return false;
    }

    /**
     * Delete model.
     *
     * @param Model $model
     *
     * @return bool
     */
    public function deleteItem(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Create a new model.
     *
     * @param array $data
     *
     * @return mixed Model or false on error during save
     */
    public function create(array $data): mixed
    {
        // Create the model
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        $model = $this->model->fill($data);

        if ($model->save()) {

            return $model;
        }

        return false;
    }

    /**
     * Update an existing model.
     *
     * @param array $data
     *
     * @return mixed Model or false on error during save
     */
    public function update(array $data = [], array $with = []): mixed
    {
        $data['updated_by'] = auth()->id();
        $model = $this->model->with($with)->find($data[$this->model->getKeyName()]);
        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại',404);
        }
        $model->fill($data);

        if ($model->save()) {
            return $model;
        }

        return false;
    }

    /**
     * Get One collection of models by the given query conditions.
     *
     * @param array $where
     * @param array $with
     * @param array $orderBy
     * @param array $columns
     * @param bool $or
     *
     * @param string $filter
     * @param bool $filterCus
     * @return Collection|null
     */
    public function getFirstWhere(
        $where,
        array $with = [],
        array $orderBy = [],
        $columns = ['*'],
        $or = false,
        $filter = '',
        $filterCus = false
    )
    {
        $query = $this->make($with);
        $funcWhere = ($or) ? 'orWhere' : 'where';
        foreach ($where as $field => $value) {
            if ($value instanceof Closure) {
                $query = $query->{$funcWhere}($value);
            } elseif (is_array($value)) {
                if (count($value) === 3) {
                    list($field, $operator, $search) = $value;
                    $query = $query->{$funcWhere}($field, $operator, $search);
                } elseif (count($value) === 2) {
                    list($field, $search) = $value;
                    $query = $query->{$funcWhere}($field, '=', $search);
                } else {
                    $k = array_keys($value)[0];
                    $v = $value[$k];
                    switch (strtolower($k)) {
                        case "IN":
                            $query = $query->whereIn(DB::raw($field), $v);
                            break;
                        case "NOT IN":
                            $query = $query->whereNotIn(DB::raw($field), $v);
                            break;
                        case "NULL":
                            $query = $query->whereNull(DB::raw($field));
                            break;
                        case "NOT NULL":
                            $query = $query->whereNotNull(DB::raw($field));
                            break;
                        case "LIKE":
                            $query = $query->where(DB::raw($field), "like", $v);
                            break;
                        case "NOT LIKE":
                            $query = $query->where(DB::raw($field), "not like", $v);
                            break;
                        default:
                            $query = $query->where(DB::raw($field), $k, $v);
                            break;
                    }
                }
            } else {

                $query = $query->{$funcWhere}($field, '=', $value);
            }
        }
        if ($filter == 'current') {
            $this->model->filterData($query, $filterCus);
        } elseif ($filter == 'filterIn') {
            $this->model->filterDataIn($query, $filterCus);
        }

        foreach ($orderBy as $column => $sortType) {
            $query->orderBy($column, $sortType);
        }

        return $query->first($columns);
    }

    /**
     * Find a collection of models by the given query conditions.
     *
     * @param array $where
     * @param array $with
     * @param array $orderBy
     * @param array $columns
     * @param bool $or
     *
     * @param string $filter
     * @param bool $filterCus
     * @return Collection|null
     */
    public function findWhere(
        $where,
        array $with = [],
        array $orderBy = [],
        $columns = ['*'],
        $or = false,
        $filter = '',
        $filterCus = false
    )
    {
        $query = $this->make($with);
        $funcWhere = ($or) ? 'orWhere' : 'where';
        foreach ($where as $field => $value) {
            if ($value instanceof Closure) {
                $query = $query->{$funcWhere}($value);
            } elseif (is_array($value)) {
                if (count($value) === 3) {
                    list($field, $operator, $search) = $value;
                    $query = $query->{$funcWhere}($field, $operator, $search);
                } elseif (count($value) === 2) {
                    list($field, $search) = $value;
                    $query = $query->{$funcWhere}($field, '=', $search);
                }
            } else {
                $query = $query->{$funcWhere}($field, '=', $value);
            }
        }

        if ($filter == 'current') {
            $this->model->filterData($query, $filterCus);
        } elseif ($filter == 'filterIn') {
            $this->model->filterDataIn($query, $filterCus);
        }

        foreach ($orderBy as $column => $sortType) {
            $query->orderBy($column, $sortType);
        }


        return $query->get($columns);
    }

    /**
     * @param array $where
     * @param bool $or
     *
     * @return mixed
     */
    public function checkWhere($where, $or = false, $filter = '', $filterCus = false)
    {
        $query = $this->make([]);
        $funcWhere = ($or) ? 'orWhere' : 'where';
        foreach ($where as $field => $value) {
            if ($value instanceof Closure) {
                $query = $query->{$funcWhere}($value);
            } elseif (is_array($value)) {
                if (count($value) === 3) {
                    list($field, $operator, $search) = $value;
                    $query = $query->{$funcWhere}($field, $operator, $search);
                } elseif (count($value) === 2) {
                    list($field, $search) = $value;
                    $query = $query->{$funcWhere}($field, '=', $search);
                }
            } else {
                $query = $query->{$funcWhere}($field, '=', $value);
            }
        }
        if ($filter == 'current') {
            $this->model->filterData($query, $filterCus);
        } elseif ($filter == 'filterIn') {
            $this->model->filterDataIn($query, $filterCus);
        }

        return $query->count();
    }

    /**
     * Sort models.
     *
     * @param array $data updated data
     *
     * @return null
     */
    public function sort(array $data)
    {
        foreach ($data['item'] as $position => $item) {
            $page = $this->model->find($item[$this->model->getKeyName()]);
            $sortData = $this->getSortData($position + 1);
            $page->update($sortData);
        }
    }

    /**
     * Get sort data.
     *
     * @param int $position
     *
     * @return array
     */
    protected function getSortData(int $position): array
    {
        return [
            'position' => $position,
        ];
    }

    /**
     * Delete model.
     *
     * @param Model $model
     *
     * @return bool
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Delete model.
     *
     * @param Model $model
     *
     * @return bool
     */
    public function forceDelete(Model $model): bool
    {
        return $model->forceDelete();
    }

    /**
     * Delete model By Ids
     *
     * @param array|int $ids
     *
     * @return bool
     */
    public function deleteById(array|int $ids): bool
    {
        $ids = is_array($ids) ? $ids : [$ids];

        return $this->model->destroy($ids);
    }

    /**
     * @param       $query
     * @param array $attributes
     *
     * @return bool
     */
    public function sortBuilder(&$query, array $attributes = [])
    {
        $validConditions = ['asc', 'desc'];
        $validColumn = $this->model->getFillable();

        if (empty($attributes['sort'])) {
            return false;
        }
        foreach ($attributes['sort'] as $key => $value) {

            if (!$value) {
                $value = 'asc';
            }

            if (!in_array($value, $validConditions)) {
                continue;
            }

            if (!in_array($key, $validColumn)) {
                continue;
            }

            $query->orderBy($key, $value);
        }
    }

    public function getById(int $id, array $with = []): Model|Collection|Builder|array|null
    {
        return $this->model->with($with)->find($id);
    }

    public function getByIdTrashed($id, array $with = []): Model|Collection|Builder|array|null
    {
        return $this->model->withTrashed()->with($with)->find($id);
    }

    /**
     * @param array $input
     * @param array $with
     * @param null $limit
     *
     * @return mixed
     */
    public function search(array $input = [], array $with = [], $limit = null): mixed
    {
        $query = $this->make($with);
        $orWhere = Arr::get($input, 'orWhere', []);
        $this->sortBuilder($query, $input);
        $full_columns = $this->model->getFillable();

        $input = array_intersect_key($input, array_flip($full_columns));
        $orWhere = array_intersect_key($orWhere, array_flip($full_columns));

        foreach ($input as $field => $value) {
            if ($value === "") {
                continue;
            }
            if (is_array($value)) {
                $query->where(function ($q) use ($field, $value) {
                    foreach ($value as $action => $data) {
                        $action = strtoupper($action);
                        if ($data === "") {
                            continue;
                        }
                        switch ($action) {
                            case "LIKE":
                                $q->orWhere(DB::raw($field), "like", "%$data%");
                                break;
                            case "IN":
                                $q->orWhereIn(DB::raw($field), $data);
                                break;
                            case "NOT IN":
                                $q->orWhereNotIn(DB::raw($field), $data);
                                break;
                            case "NULL":
                                $q->orWhereNull(DB::raw($field));
                                break;
                            case "NOT NULL":
                                $q->orWhereNotNull(DB::raw($field));
                                break;
                            case "BETWEEN":
                                $q->orWhereBetween(DB::raw($field), $value);
                                break;
                            default:
                                $q->orWhere(DB::raw($field), $action, $data);
                                break;
                        }
                    }
                });
            } else {
                $query->where(DB::raw($field), $value);
            }
        }
        $query->where(function ($qr) use ($orWhere) {
            foreach ($orWhere as $field => $value) {
                if ($value === "") {
                    continue;
                }
                if (is_array($value)) {
                    $qr->orWhere(function ($q) use ($field, $value) {
                        foreach ($value as $action => $data) {
                            $action = strtoupper($action);
                            if ($data === "") {
                                continue;
                            }
                            switch ($action) {
                                case "LIKE":
                                    $q->orWhere(DB::raw($field), "like", "%$data%");
                                    break;
                                case "IN":
                                    $q->orWhereIn(DB::raw($field), $data);
                                    break;
                                case "NOT IN":
                                    $q->orWhereNotIn(DB::raw($field), $data);
                                    break;
                                case "NULL":
                                    $q->orWhereNull(DB::raw($field));
                                    break;
                                case "NOT NULL":
                                    $q->orWhereNotNull(DB::raw($field));
                                    break;
                                case "BETWEEN":
                                    $q->orWhereBetween(DB::raw($field), $value);
                                    break;
                                default:
                                    $q->orWhere(DB::raw($field), $action, $data);
                                    break;
                            }
                        }
                    });
                } else {
                    $qr->orwhere(DB::raw($field), $value);
                }
            }
        });

        if ($limit) {
            if ($limit === 1) {
                return $query->first();
            } else {
                return $query->paginate($limit);
            }
        } else {
            return $query->get();
        }
    }

    /**
     * @param array $input
     * @param array $with
     * @param null $limit
     *
     * @return mixed
     */
    public function searchTrashed(array $input = [], array $with = [], $limit = null): mixed
    {
        $query = $this->model
            ->withTrashed()
            ->with($with);
        $orWhere = Arr::get($input, 'orWhere', []);
        $this->sortBuilder($query, $input);
        $full_columns = $this->model->getFillable();

        $input = array_intersect_key($input, array_flip($full_columns));
        $orWhere = array_intersect_key($orWhere, array_flip($full_columns));

        foreach ($input as $field => $value) {
            if ($value === "") {
                continue;
            }
            if (is_array($value)) {
                $query->where(function ($q) use ($field, $value) {
                    foreach ($value as $action => $data) {
                        $action = strtoupper($action);
                        if ($data === "") {
                            continue;
                        }
                        switch ($action) {
                            case "LIKE":
                                $q->orWhere(DB::raw($field), "like", "%$data%");
                                break;
                            case "IN":
                                $q->orWhereIn(DB::raw($field), $data);
                                break;
                            case "NOT IN":
                                $q->orWhereNotIn(DB::raw($field), $data);
                                break;
                            case "NULL":
                                $q->orWhereNull(DB::raw($field));
                                break;
                            case "NOT NULL":
                                $q->orWhereNotNull(DB::raw($field));
                                break;
                            case "BETWEEN":
                                $q->orWhereBetween(DB::raw($field), $value);
                                break;
                            default:
                                $q->orWhere(DB::raw($field), $action, $data);
                                break;
                        }
                    }
                });
            } else {
                $query->where(DB::raw($field), $value);
            }
        }
        $query->where(function ($qr) use ($orWhere) {
            foreach ($orWhere as $field => $value) {
                if ($value === "") {
                    continue;
                }
                if (is_array($value)) {
                    $qr->orWhere(function ($q) use ($field, $value) {
                        foreach ($value as $action => $data) {
                            $action = strtoupper($action);
                            if ($data === "") {
                                continue;
                            }
                            switch ($action) {
                                case "LIKE":
                                    $q->orWhere(DB::raw($field), "like", "%$data%");
                                    break;
                                case "IN":
                                    $q->orWhereIn(DB::raw($field), $data);
                                    break;
                                case "NOT IN":
                                    $q->orWhereNotIn(DB::raw($field), $data);
                                    break;
                                case "NULL":
                                    $q->orWhereNull(DB::raw($field));
                                    break;
                                case "NOT NULL":
                                    $q->orWhereNotNull(DB::raw($field));
                                    break;
                                case "BETWEEN":
                                    $q->orWhereBetween(DB::raw($field), $value);
                                    break;
                                default:
                                    $q->orWhere(DB::raw($field), $action, $data);
                                    break;
                            }
                        }
                    });
                } else {
                    $qr->orwhere(DB::raw($field), $value);
                }
            }
        });

        if ($limit) {
            if ($limit === 1) {
                return $query->first();
            } else {
                return $query->paginate($limit);
            }
        } else {
            return $query->get();
        }
    }

}
