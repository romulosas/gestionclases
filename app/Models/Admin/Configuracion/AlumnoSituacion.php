<?php

namespace App\Models\Admin\Configuracion;

use App\Models\Admin\AppModel;

class AlumnoSituacion extends AppModel
{
    protected $softDelete = true;
    protected $table = 'alumno_situacion';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
}
