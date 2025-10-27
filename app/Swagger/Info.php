<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="API Reservasi Ruangan DinuSpace",
 *     version="1.0.0",
 *     description="Dokumentasi API untuk sistem reservasi ruangan Gedung H.",
 *     @OA\Contact(
 *         email="support@dinus.ac.id",
 *         name="Tim Developer"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Localhost API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class Info {}
