<?php

namespace App\Http\Controllers;

/**
 * BaseController menyediakan metode standar untuk respons API.
 * Semua controller lain akan extend dari controller ini.
 * Versi ini disesuaikan dengan format respons yang diinginkan.
 */
abstract class Controller 
{
    /**
     * Mengembalikan respons error terstandardisasi untuk exceptions.
     *
     * @param \Throwable $e
     * @param string $exception
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function exceptionError($e, $exception, $status = 400, $meta = [])
    {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'errors' => 'Exception Error : ' . $exception,
            'meta' => $meta,
        ], $status);
    }

    /**
     * Mengembalikan respons sukses terstandardisasi.
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $message = 'Success', $status = 200)
    {
        if ($data instanceof \Illuminate\Http\Resources\Json\ResourceCollection) {
            return $data;
        }

        return response()->json([
            'data'  => $data,
            'meta'  => [
                'status_code' => $status,
                'success'     => true,
                'message'     => $message,
            ]
        ], $status);
    }

    /**
     * Mengembalikan respons JSON sederhana.
     *
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data)
    {
        return response()->json($data);
    }
}

