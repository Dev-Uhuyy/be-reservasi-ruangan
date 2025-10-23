<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Kode status HTTP kustom untuk disisipkan di meta.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Pesan kustom untuk disisipkan di meta.
     *
     * @var string
     */
    protected $message;

    /**
     * Buat instance resource baru.
     *
     * @param  mixed  $resource
     * @param  int  $statusCode
     * @param  string  $message
     * @return void
     */
    public function __construct($resource, $statusCode = 200, $message = 'Data Room berhasil diambil!')
    {
        parent::__construct($resource);
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->room_name,
            'floor' => $this->floor,
            'capacity' => $this->capacity,
            'facilities' => $this->facilities,
            'status' => $this->status,
            'schedules' => ScheduleResource::collection($this->when(isset($this->schedules), $this->schedules)),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'meta' => [
                'status_code' => $this->statusCode,
                // Tentukan 'success' berdasarkan apakah status code ada di rentang 2xx
                'success' => $this->statusCode >= 200 && $this->statusCode < 300,
                'message' => $this->message
            ],
        ];
    }
}
