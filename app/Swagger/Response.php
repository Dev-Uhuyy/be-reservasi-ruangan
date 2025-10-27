<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     type="object",
 *     title="Respons sukses standar",
 *     @OA\Property(property="data", type="object", description="Data hasil request"),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="status_code", type="integer", example=200),
 *         @OA\Property(property="success", type="boolean", example=true),
 *         @OA\Property(property="message", type="string", example="Berhasil mengambil data.")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     title="Respons error standar",
 *     @OA\Property(property="data", type="null"),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="status_code", type="integer", example=400),
 *         @OA\Property(property="success", type="boolean", example=false),
 *         @OA\Property(property="message", type="string", example="Kredensial tidak valid."),
 *         @OA\Property(property="errors", type="string", example="Exception Error : Unauthorized")
 *     )
 * )
 */
class Response {}
