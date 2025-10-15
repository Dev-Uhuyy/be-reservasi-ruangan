<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Schedule;


class ScheduleRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendapatkan aturan validasi yang berlaku untuk permintaan.
     */
    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:available,booked,canceled',
        ];
    }

    /**
     * Menambahkan validasi kustom setelah validasi dasar lolos.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                // Pengecekan jadwal bentrok (conflict checking)
                $query = Schedule::where('room_id', $this->room_id)
                    ->where('date', $this->date)
                    ->where(function ($q) {
                        $q->where(function ($q2) {
                            $q2->where('start_time', '<', $this->end_time)
                               ->where('end_time', '>', $this->start_time);
                        });
                    });

                // Jika sedang update, abaikan jadwal saat ini dari pengecekan
                if ($this->route('schedule')) {
                    $query->where('id', '!=', $this->route('schedule')->id);
                }

                if ($query->exists()) {
                    $validator->errors()->add(
                        'conflict',
                        'Jadwal di ruangan ini sudah terisi pada tanggal dan waktu yang dipilih.'
                    );
                }
            }
        ];
    }

    /**
     * Menangani respons validasi yang gagal.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'data' => ['errors' => $validator->errors()],
            'meta' => [
                'status_code' => 422,
                'success' => false,
                'message' => 'Validasi gagal! Data yang diberikan tidak valid.'
            ]
        ], 422));
    }
}

