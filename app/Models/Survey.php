<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\SurveyQuestion;
use App\Models\SurveyAnswer;
class Survey extends Model
{
    CONST TYPE_TEXT = "text";
    CONST TYPE_TEXTAREA = "textarea";
    CONST TYPE_SELECT = "select";
    CONST TYPE_RADIO = "radio";
    CONST TYPE_CHECKBOX = "checkbox";
     
    use HasFactory, HasSlug;
    
    protected $fillable = [
        'user_id', 'image', 'title' , 'slug', 'status', 'description', 'expire_date' 
    ];
    
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
    
    public function questions() {
        return $this->hasMany(SurveyQuestion::class);
    }
    public function answers() {
        return $this->hasMany(SurveyAnswer::class);
    }
}