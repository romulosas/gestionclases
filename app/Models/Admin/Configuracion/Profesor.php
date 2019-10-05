<?php

namespace App\Models\Admin\Configuracion;

use App\Models\Admin\AppModel;

class Profesor extends AppModel
{
    protected $softDelete = true;
    protected $table = 'profesor';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
}
