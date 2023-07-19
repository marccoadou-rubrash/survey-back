<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SurveyDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Survey;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Pcsaini\ResponseBuilder\ResponseBuilder;

class SurveyController extends Controller
{
    public function index(SurveyDataTable $dataTable)
    {
        return $dataTable->render('admin.surveys.index');
    }

    public function edit($id): Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
    {
        try {
            $survey = Survey::query()->find($id);
            if (!$survey) {
                return back()->with(['error' => __('admin.survey_not_found')]);
            }

            $surveys = Survey::all();
            $selectedSurveys = [];
            return view('admin.surveys.create', compact('surveys', 'survey', 'selectedSurveys'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    public function save(Request $request)
    {
        try {


            if ($request->get('id')) {
                $surveyModel = Survey::query()->find($request->get('id'));

                if (!$surveyModel) {
                    return back()->with(['error' => __('admin.survey_not_found')]);
                }
            }

            $validator = Validator::make($request->all(), [

            ]);

            if ($validator->fails()) {
                return back()->with(['error' => $validator->errors()->first()])->withInput($request->all());
            }

            $data = $request->all();


            $survey = Survey::query()->create(['title' => $request->survey_title, 'company_id' => $request->company]);

            if ($request->hasFile('company_logo')) {
                $extension = $request->file('company_logo')->getClientOriginalExtension();
                $name = $request->file('company_logo')->getClientOriginalName();
                $name = Str::slug(explode('.', $name)[0]) . '-' . time() . '.' . $extension;
                $path = 'uploads/surveys/company_logos';
                $data['company_logo'] = $request->file('company_logo')->storePubliclyAs($path, $name);
            }

            if (isset($surveyModel)) {
                $surveyModel->update($data);
                $message = __('admin.survey_updated');
            } else {
                foreach ($data['questions'] as $surveyData) {
                    $question = $survey->questions()->create([
                        'question_text' => $surveyData['text'],
                        'question_type' => $surveyData['type'],
                    ]);

                    if (in_array($surveyData['type'], ['MCQ', 'MAQ'])) {
                        foreach ($surveyData['options'] as $option) {
                            $question->options()->create(['option_text' => $option]);
                        }
                    }
                }

                $message = __('admin.survey_created');
            }

            return to_route('admin.surveys.list')->with(['success' => $message]);
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')])->withInput($request->all());
        }

    }

    public function create(): Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
    {
        try {
            $surveys = Survey::all();
            $selectedSurveys = [];
            $selectedUniversities = [];
            $companies = Company::all();
            return view('admin.surveys.create', compact('companies', 'surveys', 'selectedSurveys', 'selectedUniversities'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    public function delete($id): Response|RedirectResponse
    {
        try {
            $survey = Survey::query()->find($id);
            if (!$survey) {
                return back()->with(['error' => __('admin.survey_not_found')]);
            }

            $survey->delete();

            return ResponseBuilder::success(null, __('admin.survey_deleted'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    public function updateStatus($id): Response|RedirectResponse
    {
        try {
            $survey = Survey::query()->find($id);
            if (!$survey) {
                return back()->with(['error' => __('admin.survey_not_found')]);
            }


            $surveys = Survey::query();
            $surveys->update(['is_active' => false]);

            $survey->update(['is_active' => !$survey->is_active]);

            return ResponseBuilder::success(null, __('admin.survey_status_updated'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }
}
