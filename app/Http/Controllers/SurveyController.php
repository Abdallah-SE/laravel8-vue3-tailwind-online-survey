<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestionAnswer;

use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Http\Requests\StoreSurveyAnswerRequest;

use Illuminate\Http\Request;
use App\Http\Resources\SurveyResource;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
         
        return SurveyResource::collection(Survey::where('user_id', $user->id)->paginate(3));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSurveyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSurveyRequest $request)
    {
        $data = $request->validated();
        
        if(isset($data['image'])){
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }
        
        $survey = Survey::create($data);
        
        ////ٍ  Save the questions
        if(isset($data["questions"])){
            foreach ($data["questions"] as $question) {
                $question["survey_id"] = $survey->id;
                $this->createQuestion($question);
            }
        }
        return new SurveyResource($survey);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey, Request $request)
    {
        $user = $request->user();
        if($user->id !== $survey->user_id){
            return abort(403, "Unauthorized Action.");
        }
        return new SurveyResource($survey);
    }
    /**
     * Display the specified resource for guest.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function showForGuest(Survey $survey)
    {
        return new SurveyResource($survey);
    }
    public function storeAnswer(StoreSurveyAnswerRequest $request, Survey $survey) {
        $validated = $request->validated();
        
        $survey_answer = SurveyAnswer::create([
            'survey_id' => $survey->id,
            'start_date' => date('Y-m-d H-i-s'),
            'end_date' => date('Y-m-d H-i-s'),
        ]);
        
        foreach ($validated['answers'] as $questionId => $answer){
            $question = SurveyQuestion::where(['id' => $questionId , 'survey_id' => $survey->id])->get();
            
            if(!$question){
                return response("invalid question id: \"$questionId\" ", 400);
            }
            
            $data = [
                'survey_question_id' => $questionId,
                'survey_answer_id' => $survey_answer->id,
                'answer' => is_array($answer) ? json_encode($answer): $answer
            ];
            SurveyQuestionAnswer::create($data);
        }
        return response("A Created!", 201);
        
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSurveyRequest  $request
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        $data = $request->validated();
        
        if(isset($data["image"])){
            $relativePath = $this->saveImage($data["image"]);
            $data["image"] = $relativePath;
            
            //Delete the old image if it's existing!
            if($survey->image){
                $absolutePath = public_path($survey->image);
                File::delete($absolutePath);
            }
        }
        
        $survey->update($data);
        
        ///Get the ids of the existing questions
        $existingQIDs = $survey->questions()->pluck('id')->toArray();///pulck method retrieves all of the values for a given key from an array
        $newQIDs = Arr::pluck($data['questions'],'id');
        
        ///find questions to delete
        $questionsToDelete = array_diff($existingQIDs, $newQIDs);
        ///find questions to add
        $questionsToAdd = array_diff($newQIDs, $existingQIDs);
        
        //Delete the deleted items in frontend side
        SurveyQuestion::destroy($questionsToDelete);
        
        //CReate new questions
        foreach ($data["questions"] as $question) {
            if(in_array($question["id"], $questionsToAdd)){
                $question["survey_id"] = $survey->id;
                $this->createQuestion($question);
            }
        }
        ////Update the existing questions
        
        $questionsToUpdate = collect($data["questions"])->keyBy("id");
        foreach ($survey->questions as $question) {
            if( isset($questionsToUpdate[$question->id]) ){
                $this->updateQuestion($question, $questionsToUpdate[$question->id]);
            }
        }
        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey, Request $request)
    {
        $user = $request->user();
        if($user->id !== $survey->user_id){
            return abort(403, "Not Authorized Action.");
        }
        $survey->delete();
        //Delete the old image if it's existing!
            if($survey->image){
                $absolutePath = public_path($survey->image);
                File::delete($absolutePath);
            }
        return response('', 204);
    }
    
    private function saveImage($img) {
        if (preg_match("/^data:image\/(\w+);base64,/", $img, $type)) {
            $image = substr($img, strpos($img, ',')+1);
            $type = strtolower($type[1]);
            
            if(!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])){
                throw new \Exception("Invalid image extention!");
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);
            
            if ($image === false) {
                throw \Exception("Failed to base64_decode");
            }
        }else {
            throw new \Exception("URI data Not match with data image");
        }
        $dir = "images/";
        $file = Str::random() . '.' . $type;
        
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;
        
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);
        return $relativePath;
    }
    
    
    private function createQuestion($question) {
        if(is_array($question["data"])) {
            $question["data"] = json_encode($question["data"]);
        }
        
        $validator = Validator::make($question, [
            "question" => "required|string",
            "type" => ["required", Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX
            ])],
            "description" => "nullable|string",
            "data" => "present",
            "survey_id" => "exists:surveys,id"
        ]);
        return SurveyQuestion::create($validator->validated());
    }
    private function updateQuestion(SurveyQuestion $q, $q_data) {
        if(is_array($q_data['data'])){
            $q_data["data"] = json_encode($q_data["data"]);
        }
        
        $validator = Validator::make($q_data, [
            'id' => 'exists:App\Models\SurveyQuestion,id',
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX
            ])],
            "description" => "nullable|string",
            "data" => "present",
        ]);
        
        return $q->update($validator->validated());
    }
}
