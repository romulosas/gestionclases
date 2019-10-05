<?php
namespace App\Models\Admin\Security;

use App\Models\Admin\AppModel;

class UserBranch extends AppModel {

    protected $softDelete = true;

    protected $table = 'security_users_branchs';
    public $timestamps = false;
    protected $appends = ['fullname'];
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    //relaciones
    public function user() {
		return $this->belongsTo('App\Models\App\Security\User', 'user_id');
    }
    public function getFullnameAttribute() {
		return $this->user ? $this->user->name : '';
	}

}
