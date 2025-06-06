<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class Controller
{
    protected function responseDataSuccess($message, array $data): JsonResponse
    {
        return $this->responseSuccess($message, $data);
    }

    /**
     * Send a successful response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseDeleteSuccess($message = 'Xoá dữ liệu thành công', array $data = [], int $code = 200): JsonResponse
    {
        if (empty($message)) {
            $message = 'Xoá dữ liệu thành công';
        }
        return $this->responseSuccess($message, $data, $code);
    }


    /**
     * Send a failed response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseDeleteFail(string $message = 'Xoá dữ liệu thất bại', array $data = [], int $code = 400): JsonResponse
    {
        if (empty($message)) {
            $message = 'Xoá dữ liệu thất bại';
        }
        return $this->responseFail($message, $data, $code);
    }

    /**
     * Send a successful response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseUpdateSuccess(string $message = 'Cập nhật dữ liệu thành công', array $data = [], int $code = 200): JsonResponse
    {
        if (empty($message)) {
            $message = 'Cập nhật dữ liệu thành công';
        }
        return $this->responseSuccess($message, $data, $code);
    }


    /**
     * Send a failed response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseUpdateFail(string $message = 'Cập nhật dữ liệu thất bại', array $data = [], int $code = 400): JsonResponse
    {
        if (empty($message)) {
            $message = 'Cập nhật dữ liệu thất bại';
        }
        return $this->responseFail($message, $data, $code);
    }

    /**
     * Send a successful response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseStoreSuccess(string $message = 'Thêm dữ liệu thành công', array $data = [], int $code = 200): JsonResponse
    {
        if (empty($message)) {
            $message = 'Thêm dữ liệu thành công';
        }
        return $this->responseSuccess($message, $data, $code);
    }


    /**
     * Send a failed response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseStoreFail(string $message = 'Thêm dữ liệu thất bại', array $data = [], int $code = 400): JsonResponse
    {
        if (empty($message)) {
            $message = 'Thêm dữ liệu thất bại';
        }
        return $this->responseFail($message, $data, $code);
    }

    /**
     * Send a successful response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseSuccess(string $message, array $data = [], int $code = 200): JsonResponse
    {
        return $this->response($code, $message, $data);
    }

    /**
     * Send a failed response
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseFail(string $message, array $data = [], int $code = 400): JsonResponse
    {
        return $this->response($code, $message, $data);
    }

    /**
     * Returns a response
     * @param int $code
     * @param string $message
     * @param array $data
     * @return JsonResponse
     */
    protected function response(int $code, string $message = '', array $data = []): JsonResponse
    {
        $isError = 200 <= $code && $code < 400;
        return response()->json(array_merge(['message' => $message, 'code' => $code, 'status' => $isError], $data), 200);
    }

    /**
     * Returns a response
     * @param ResourceCollection $data
     * @return ResourceCollection
     */
    protected function responseIndex(ResourceCollection $data): ResourceCollection
    {
        return $data->additional([
            "message" => "",
            "code"    => 200,
            "status"  => true,
        ]);
    }

    protected function responsePaginate($paginated)
    {
        return response()->json([
            "message" => "",
            "code"    => 200,
            "status"  => true,
            'data'    => $paginated->items(),
            'links'   => [
                'first' => $paginated->url(1),
                'last'  => $paginated->url($paginated->lastPage()),
                'prev'  => $paginated->previousPageUrl(),
                'next'  => $paginated->nextPageUrl(),
            ],
            'meta'    => [
                'current_page' => $paginated->currentPage(),
                'from'         => $paginated->firstItem(),
                'last_page'    => $paginated->lastPage(),
                'links'        => collect(range(1, $paginated->lastPage()))->map(function ($page) use ($paginated) {
                    return [
                        'url'    => $paginated->url($page),
                        'label'  => (string)$page,
                        'active' => $paginated->currentPage() == $page,
                    ];
                })->prepend([
                    'url'    => $paginated->previousPageUrl(),
                    'label'  => "&laquo; Trang sau",
                    'active' => false
                ])->push([
                    'url'    => $paginated->nextPageUrl(),
                    'label'  => "Trang trước &raquo;",
                    'active' => false
                ]),
                'path'         => $paginated->path(),
                'per_page'     => $paginated->perPage(),
                'to'           => $paginated->lastItem(),
                'total'        => $paginated->total(),
            ],
        ]);
    }
}
