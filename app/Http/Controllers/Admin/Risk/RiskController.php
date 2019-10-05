<?php

namespace App\Http\Controllers\Admin\Risk;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;

class RiskController extends AdminController
{
    public $restful = true;

    public function __construct()
    {
        // $this->filter('before', 'auth');
        // $this->filter('before', 'role: RISKADM');
        // $this->filter('before', 'role: RISKMNG');
        // $this->filter('before', 'role: RISKACAD');

        // $this->filter('before', 'no_clients');
    }

    public function getCerate()
    {
        dd('Crear Riesgo');
    }

    public function postStore(Request $request)
    {
        $input = $request->all();


        // $step1 = $step2 = $step3 = $step4 = null;

        // if($input['step'] == 1 ) {
        //     $step2 = "active";
        // } elseif ($input['step'] == 2) {
        //     $step3 = "active";
        // } elseif ($input['step'] == 3) {
        //     $step4 = "active";
        // } else {
        //     $step1 = "active";
        // }

        $risk = new Risk;
        /*
        ** Datos Pestaña Identificacion del Riesgo
        */
        $risk->source = $input['risk_source'];
        $risk->process = $input['process'];
        $risk->detected_risk = $input['detected_risk'];
        $risk->classification_id = intval($input['classification_result']);

        /*
        ** Datos Pestaña Tratamiento del Riesgo
        */
        $risk->treatment_id = intval($input['treatment_id']);
        $risk->preventive_action = $input['preventive_action'];
        $risk->treatment_classification_id = intval($input['treatment_classification_id']);

        /*
        ** Datos Pestaña Exposicion al Riesgo
        */
        $risk->exposure_indicator = $input['exposure_indicator_input'];
        $risk->exposure_level_id = $input['exposure_level_id'];

        /*
        ** Datos Pestaña Eficacia
        */
        $risk->preventive_action_validate = $input['preventive_action_validate'];
        $risk->corrective_action = $input['corrective_action'];
        $risk->measurement_periodicity_id = $input['measurement_periodicity_id'];

        $risk->save();

        dd('Riesgo Creado');

        // return View::make('risk::edit', array('risk_id', $risk->id))
        //     ->with('risk', $risk)
        //     ->with('step1', $step1)
        //     ->with('step2', $step2)
        //     ->with('step3', $step3)
        //     ->with('step4', $step4);


    }

}