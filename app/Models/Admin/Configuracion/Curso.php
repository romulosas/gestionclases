<?php

namespace App\Models\Admin\Configuracion;

use App\Models\Admin\AppModel;

class Curso extends AppModel
{
    protected $softDelete = true;
    protected $table = 'curso';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
}
