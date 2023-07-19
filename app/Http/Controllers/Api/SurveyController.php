<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurveyResource;
use App\Models\Answer;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pcsaini\ResponseBuilder\ResponseBuilder;

class SurveyController extends Controller
{
    public function index(): Response
    {
        $questions = Survey::query()->where('is_active', true)->first();

        $this->response->questions = new SurveyResource($questions);
        return ResponseBuilder::success($this->response);
    }

    public function submit(Request $request): Response
    {
        $data = $request->selectedAnswer;

        $email = $request->email;

        User::query()->create(['email' => $email]);
        return $this->extracted($data);
    }

    public function bulkSubmit(Request $request): Response
    {
        $data = $request->answers;
        return $this->extracted($data);
    }

    public function checkEmail(Request $request): Response
    {
        $result = User::query()->where('email', $request->email)->first();

        if ($result) {
            return ResponseBuilder::error("Désolé! vous avez déjà fait l'enquête.");
        }

        return ResponseBuilder::success(null, "Success");
    }

    /**
     * @param mixed $data
     * @return Response
     */
    public function extracted(mixed $data): Response
    {
        $user = User::query()->latest()->first();


        $answer = Answer::query();

        $survey = Survey::query()->where("is_active", true)->first();

        foreach ($data as $d) {
            $answer = $answer->create(['survey_id' => $survey->id, 'user_id' => $user->id, 'question_id' => $d['question'], 'selected_options' => json_encode($d['options']), "answer_text" => $d['options'][3] ?? null]);
        }
        return ResponseBuilder::success(null, "Success");
    }
}
