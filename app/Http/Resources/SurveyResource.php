<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
            "id" => $this->id,
            "company_name" => $this->company->name,
            "survey_title" => $this->title,
            "company_logo" => $this->company->logo ? Storage::url($this->company->logo) : null,
            "questions" => QuestionResource::collection($this->questions)
        ];
    }
}
