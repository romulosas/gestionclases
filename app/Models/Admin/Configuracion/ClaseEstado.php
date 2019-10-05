<?php

namespace App\Models\Admin\Configuracion;

use App\Models\Admin\AppModel;

class ClaseEstado extends AppModel
{
    protected $softDelete = true;
    protected $table = 'clase_estado';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
}
