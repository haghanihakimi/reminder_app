<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RemindersNormalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        return [
            'id' => $this->pid, //hash('sha256', $this->pid),
            'title' => $this->reminder_title,
            'descriptions' => !empty($this->reminder_descriptions) ? $this->reminder_descriptions : '',
            'privacy' => $this->privacy,
            'due' => $this->due_date,
            'link' => $this->link,
        ];
    }
}
