<?php

namespace App\Models\Admin\Configuracion;

use App\Models\Admin\AppModel;

class CursoTipo extends AppModel
{
    protected $softDelete = true;
    protected $table = 'curso_tipo';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
}
