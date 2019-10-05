<?php
namespace App\Models\Admin\Security;

use App\Models\Admin\AppModel;

class RoleByUser extends AppModel {

    protected $softDelete = true;


    protected $table = 'security_roles_by_user';
    public $timestamps = false;

    public function user(){return $this->belongsTo('App\Models\App\Security\User', 'user_id');}
    public function rol(){return $this->belongsTo('App\Models\App\Security\Role', 'role_id');}
}
