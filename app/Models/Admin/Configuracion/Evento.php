<?php

namespace App\Models\Admin\Configuracion;

use App\Models\Admin\AppModel;

class Evento extends AppModel
{
    protected $softDelete = true;
    protected $table = 'evento';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
}
