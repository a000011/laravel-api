<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $errors = [];
        foreach ($this->messages() as $key => $error) {
             $errors[$key] = $error;
        }

        return [
            'code' => 422,
            'message' => 'Validation error',
            'errors' => $errors
        ];
    }

    public static $wrap = 'error';
}
