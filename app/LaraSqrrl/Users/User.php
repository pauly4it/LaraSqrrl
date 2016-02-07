<?php namespace App\LaraSqrrl\Users;

use Illuminate\Database\Eloquent\Model;
use Validator;

class User extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'phone', 'role', 'nuts'];

    /**
     * Find a user by their phone number.
     *
     * @param $phoneNumber
     * @return mixed
     */
    public function findByPhone($phoneNumber)
    {
        return $this->where('phone', $phoneNumber)->first();
    }

    /**
     * Get all expert users.
     *
     * @return mixed
     */
    public function getAllExperts()
    {
        return $this->where('role', 'expert')->get();
    }

    /**
     * Determine how many registration steps a user has completed.
     *
     * @return int
     */
    public function registrationStepsCompleted()
    {
        if (is_null($this->name))
        {
            return 1;
        }
        elseif (is_null($this->role))
        {
            return 2;
        }
        else
        {
            return 3;
        }
    }

    /**
     * Get the user's role.
     *
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Validate a user's name.
     *
     * @param $data
     * @return bool
     */
    public function validateName($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|regex:/^[A-Za-z\' -]*$/|max:140'
        ]);

        return ($validator->fails()) ? FALSE : TRUE;
    }

    /**
     * Validate the self-assigned role for the user.
     *
     * @param $data
     * @return bool
     */
    public function validateRole($data)
    {
        $validator = Validator::make($data, [
            'role' => 'required|in:expert,enthusiast'
        ]);

        return ($validator->fails()) ? FALSE : TRUE;
    }

    /**
     * Add nuts to the user's account.
     *
     * @param int $num
     */
    public function addNuts($num = 2)
    {
        $current = $this->nuts;
        $this->nuts = $current + $num;
        $this->save();
    }

}
