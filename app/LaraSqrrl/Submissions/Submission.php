<?php namespace App\LaraSqrrl\Submissions;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model {

    protected $table = 'submissions';
    protected $fillable = ['user_id', 'photo_url'];

    /*
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('App\LaraSqrrl\Users\User');
    }
    public function response()
    {
        return $this->hasMany('App\LaraSqrrl\Responses\Response');
    }

}
