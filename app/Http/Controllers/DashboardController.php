<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Survey;
use App\Models\SurveyAnswer;

use App\Http\Resources\SurveyResource;
use App\Http\Resources\SurveyResourceDashboard; 
use App\Http\Resources\SurveyAnswerResource;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        
        ///Total Number of Surveys on the app
        $total = Survey::where('user_id', $user->id)->count();
        
        ////Latest Surveys
        $latest = Survey::query()->where('user_id', $user->id)->latest('created_at')->first();///Desc
        
        ////Total Numbers of answers
        $totalAnswers = SurveyAnswer::query()
                ->join('surveys', 'survey_answers.survey_id' , '=', 'surveys.id')
                ->where('user_id', $user->id)
                ->count();
        ///Latest 5 answers
        $latestAnswers = SurveyAnswer::query()
                ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
                ->where('surveys.user_id', $user->id)
                ->orderBy('end_date', 'DESC')
                ->limit(5)
                ->getModels('survey_answers.*');
        
        return [
            'totalSurveys' => $total,
            'latestSurveys' => $latest ? new SurveyResourceDashboard($latest) : null,
            'totalAnswers' => $totalAnswers,
            'latestAnswers' => SurveyAnswerResource::collection($latestAnswers),
        ];
    }
}
