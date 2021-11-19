<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerShowResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'full_name'  => trim($this->first_name . ' ' . $this->last_name),
            'email'      => $this->email,
            'gender'     => $this->gender->description ?? null,
            'city'       => $this->city->name ?? null,
            'state'      => $this->city->state ?? null,
            'company'    => $this->company->name ?? null,
            'title'      => $this->title->description ?? null,
            'latitude'   => $this->latitude,
            'longitude'  => $this->longitude,
        ];
    }
}
