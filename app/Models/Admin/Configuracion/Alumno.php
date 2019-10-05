<?php

namespace App\Models\Admin\Configuracion;

use App\Models\Admin\AppModel;

class Alumno extends AppModel
{
    protected $softDelete = true;
    protected $table = 'alumno';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
}
