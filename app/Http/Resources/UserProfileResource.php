<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'id' => $this->uid,
            'first_name' => $this->fname,
            'surname' => $this->sname,
            'email' => $this->email,
            'email_notification' => $this->mail_notification,
            'birthdate' => $this->birthdate,
            'gender' => $this->gender
        ];
    }
}
