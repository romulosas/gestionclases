<?php

namespace App\Models\Admin\Configuracion;

use App\Models\Admin\AppModel;

class Perfil extends AppModel
{
    protected $softDelete = true;
    protected $table = 'perfil';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
}
