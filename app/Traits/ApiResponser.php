<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
    private function successRepsonse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()) {
            return $this->successRepsonse(['data' => $collection], $code);
        }

        $transformer = $collection->first()->transformer;

        $collection = $this->filterData($collection,  $transformer);
        $collection = $this->sortData($collection,  $transformer);
        $collection = $this->paginateData($collection);

        $collection = $this->transformData($collection, $transformer);
        $collection = $this->cacheResponse($collection);

        return $this->successRepsonse($collection, $code);
    }

    protected function showOne(Model $model, $code = 200)
    {

        $transformer = $model->transformer;

        $model = $this->transformData($model, $transformer);

        return $this->successRepsonse($model, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successRepsonse(['data' => $message], $code);
    }

    protected function transformData($data, $transformer)
    {
        $transformer = fractal($data, new $transformer);

        return $transformer->toArray();
    }

    protected function sortData(Collection $collection, $transformer)
    {
        if (request()->has('sort_by')) {
            $attribute =  $transformer::originalAttribute(request()->sort_by);

            $collection = $collection->sortBy($attribute);
        }

        return $collection;
    }

    protected function filterData(Collection $collection, $transformer)
    {
        foreach (request()->query() as $query => $value) {
            $attribute = $transformer::originalAttribute($query);

            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }

    public function paginateData(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50',
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 15;
        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);


        $paginated->appends(request()->all());

        return $paginated;
    }

    public function cacheResponse($data)
    {

        $url = request()->url();
        $queryParams = request()->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 90 / 120, function () use ($data) {
            return $data;
        });
    }
}
