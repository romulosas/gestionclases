<?php

namespace App\Models\Admin\Configuracion;

use App\Models\Admin\AppModel;

class CursoEstado extends AppModel
{
    protected $softDelete = true;
    protected $table = 'curso_estado';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
}
