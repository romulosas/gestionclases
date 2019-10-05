<?php
namespace App\Models\Admin\Security;

use \Auth, \DB, \Utils;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

// actualizado L8.5
class User extends Authenticatable //implements UserInterface, RemindableInterface
{

    // use UserTrait, RemindableTrait;

    use Notifiable;

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'security_users';

    protected $fillable = ['work_type_id', 'management_id'];
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    protected $appends = ['branch'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    protected $userRoles = [];

    public function fullname()
    {
        return ($this->profile) ? $this->profile->firstname . ' ' . $this->profile->lastname : $this->name;
    }

}
