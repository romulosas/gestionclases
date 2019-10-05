<?php

namespace App\Models\Admin\Configuracion;

use App\Models\Admin\AppModel;

class Asistencia extends AppModel
{
    protected $softDelete = true;
    protected $table = 'asistencia';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
}
