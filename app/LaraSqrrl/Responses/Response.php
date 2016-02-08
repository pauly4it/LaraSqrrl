<?php namespace App\LaraSqrrl\Responses;

use Illuminate\Database\Eloquent\Model;

class Response extends Model {

    protected $table = 'responses';
    protected $fillable = ['submission_id', 'user_id', 'is_squirrel'];

    /*
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('App\LaraSqrrl\Users\User');
    }
    public function submission()
    {
        return $this->belongsTo('App\LaraSqrrl\Submissions\Submission');
    }

}
