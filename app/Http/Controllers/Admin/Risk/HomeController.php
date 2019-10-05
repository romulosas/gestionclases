<?php

namespace App\Http\Controllers\Admin\Risk;

use App\Libraries\Utils;
use App\Models\Admin\Configuracion\Department;
use App\Models\Admin\Configuracion\Management;
use App\Models\Admin\Panel\ManagementByStadar;
use App\Models\Admin\Risk\Risk;
use App\Models\Admin\Risk\RiskAutomatization;
use App\Models\Admin\Risk\RiskClassification;
use App\Models\Admin\Risk\RiskExposureLevel;
use App\Models\Admin\Risk\RiskHistory;
use App\Models\Admin\Risk\RiskInpact;
use App\Models\Admin\Risk\RiskOpportunity;
use App\Models\Admin\Risk\RiskPeriodicity;
use App\Models\Admin\Risk\RiskProbability;
use App\Models\Admin\Risk\RiskReminder;
use App\Models\Admin\Risk\RiskTracing;
use App\Models\Admin\Risk\RiskTreatment;
use App\Models\Admin\Risk\RiskTreatmentClassification;
use App\Models\Admin\Security\User;
use App\Models\Admin\Security\UserByStandard;
use Auth;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Admin\Risk\RiskClassificationColor;
use App\Models\Admin\Risk\RiskMeasurementPeriodicity;
use Illuminate\Http\Request;
use Validator;

class HomeController extends AdminController
{
    public $restful = true;
    protected $permission_app = 'ISORISK';

    /**
     *    Constructor
     * */
    public function __construct()
    {
        /* if (!$this->checkPermissions()) {
          return Redirect::to_route('risk_home');
          } */
        // TODO
        /*$this->filter('before', 'auth');
        $this->filter('before', 'risks');*/
        $this->messages = [
            'required' => 'Debe completar todos los Campos.',
            'risk_source_max' => 'Fuente del Riesgo debe contener menos de :max caracteres.',
            'process_max' => 'Proceso debe contener menos de :max caracteres.',
            'detected_risk_max' => 'Riesgo Detectado debe contener menos de :max caracteres.',
            'preventive_action_max' => 'Acción Preventiva debe contener menos de :max caracteres.',
            'eficacy_max' => 'Verificación de eficacia de la acción preventiva debe contener menos de :max caracteres.',
            'corrective_action_max' => 'Acción Correctiva debe contener menos de :max caracteres.'
        ];

        $this->rules = [
            'risk_source' => 'required|max:1000',
            'process' => 'required|max:1000',
            'detected_risk' => 'required|max:1000',
            'probability_level' => 'required',
            'inpact_level' => 'required',
            'classification_result' => 'required',
            'treatment_id' => 'required',
            'preventive_action' => 'required|max:1000',
            'opportunity_id' => 'required',
            'periocidity_id' => 'required',
            'automatization_id' => 'required',
            'treatment_classification_id' => 'required',
            'exposure_indicator_input' => 'required',
            'exposure_level_id' => 'required',
            'eficacy' => 'max:1000',
            'corrective_action' => 'max:1000'
        ];

        $this->rules_severidad = [
            'severity_result_div' => 'max:1000',
            'classificacion' => 'required',
            'measurement_periodicity_id' => 'required',
            'risk_inpact_id' => 'required',
            'risk_probability_id' => 'required'
        ];

        $this->rules_classification = [
            'name' => 'required|max:100',
            'color' => 'required|max:7'
        ];

        $this->rules_exposure = [
            'severity' => 'required|max:1000',
            'control' => 'required|max:1000',
            'name' => 'required|max:100',
            'color' => 'required|max:7'
        ];

        $this->rules_treatment = [
            'risk_opportunity_id' => 'required',
            'risk_periodicity_id' => 'required',
            'risk_automatization_id' => 'required',
            'control_classification_name' => 'required',
            'control_classification_color' => 'required',
            'control_valorization_name' => 'required',
            'control_valorization_color' => 'required'
        ];

    }

    public function getHome()
    {
        Asset::add('javascript', 'js/main.js')->bundle('risk');
        Asset::add('css', 'css/main.css')->bundle('risk');
        return view('risk::home');
    }

    /**
     * Muestra listado de Gestiones del Riesgo Creadas
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function getIndex(Request $request)
    {
        // TODO
        /*if (!$this->check_permission_access('LISTADO_RIESGO')) {
            return Redirect::to_route('risk_home');
        }*/
        $admin = Auth::user()->has_role('RISKADM');
        $academy = Auth::user()->has_role('RISKACAD');
        $manager = Auth::user()->has_role('RISKMNG');
        $view = Auth::user()->has_role('RISKVIEW');

        $management = Auth::user()->management_id;
        $management_id = intval($request->get('management', 0));
        $department_id = intval($request->get('department', 0));
        $year = $request->get('year', date('Y'));

        $q_risks = Risk::whereNull('deleted_at')
            ->where('created_at', '>=', "$year-01-01")
            ->where('created_at', '<=', "$year-12-31")
            ->where('id_iso_actual', '=', Auth::user()->id_iso_actual);

        if ($admin || $view) {
            if ($management_id) {
                $q_risks = $q_risks->whereManagementId($management_id);
                $departments = ['' => '-- Todos --'] + Department::whereManagementId($management_id)->pluck('name', 'id')->toArray();
            } else {
                $departments = ['' => '-- Todos --'];
            }
            if ($department_id) {
                $q_risks = $q_risks->whereDepartmentId($department_id);
            }
        } else {
            $q_risks = $q_risks->whereManagementId(Auth::user()->management_id);
            if ($department_id) {
                $q_risks = $q_risks->whereDepartmentId($department_id);
            }
            $departments = ['' => '-- Todos --'] + Department::whereManagementId($management)->pluck('name', 'id')->toArray();
        }

        $risks = $q_risks->get();
        $current_year = 2018;

        $managements_ids = ManagementByStadar::where('standard_id', '=', Auth::user()->id_iso_actual)->where('status', '=', 1)->whereNull('deleted_at')->pluck('management_id')->toArray();
        if ($managements_ids) {
            $managements = ['' => '-- Todos --'] + Management::whereIn('id', $managements_ids)->whereStatus(1)->pluck('name', 'id')->toArray();
        } else {
            $managements = ['' => '-- Sin Datos --'];
        }

        $cmb_years = Array('' => '--Seleccione--') + Utils::get_cmb_year_until($current_year, 4);
        $cmb_years_duplicate = Array('' => '--Seleccione--') + Utils::get_cmb_year_until($current_year + 1, 3);

        return view('risk.index')
            ->with('risks', $risks)
            ->with('cmb_years', $cmb_years)
            ->with('cmb_years_duplicate', $cmb_years_duplicate)
            ->with('year', $year)
            ->with('managements', $managements)
            ->with('management_id', $management_id)
            ->with('admin', $admin)
            ->with('academy', $academy)
            ->with('manager', $manager)
            ->with('departments', $departments)
            ->with('department_id', $department_id)
            ->with('view', $view)
            ->with('currentApp', 'ISORISK');
    }

    /**
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return mixed
     */
    public function getCreate()
    {
        // TODO
        /*if (!$this->check_permission_access_action('LISTADO_RIESGO', 'crear')) {
            return Redirect::to_route('risk_home');
        }*/
        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');
        $management = Auth::user()->management_id;

        $probabilities = ['' => '-- Seleccione --'] + RiskProbability::where('status', '=', 1)->pluck('name', 'id')->toArray();
        $inpacts = ['' => '-- Seleccione --'] + RiskInpact::where('status', '=', 1)->pluck('name', 'id')->toArray();
        $treatments = ['' => '-- Seleccione --'] + RiskTreatment::where('status', '=', 1)->pluck('name', 'id')->toArray();
        $opportunities = ['' => '-- Seleccione --'] + RiskOpportunity::where('status', '=', 1)->pluck('name', 'id')->toArray();
        $periodicities = ['' => '-- Seleccione --'] + RiskPeriodicity::where('status', '=', 1)->pluck('name', 'id')->toArray();
        $automatizations = ['' => '-- Seleccione --'] + RiskAutomatization::where('status', '=', 1)->pluck('name', 'id')->toArray();
        $measurement_periodicities = ['' => '-- Seleccione --'] + RiskMeasurementPeriodicity::where('status', '=', 1)->pluck('name', 'id')->toArray();
        $managements_ids = ManagementByStadar::where('standard_id', '=', Auth::user()->id_iso_actual)->pluck('management_id')->toArray();

        if ($managements_ids) {
            $managements = ['' => '-- Seleccione --'] + Management::whereIn('id', $managements_ids)->whereStatus(1)->pluck('name', 'id')->toArray();
        } else {
            $managements = ['' => '-- Seleccione --'];
        }
        if (!$admin) {
            $departments = ['' => '-- Seleccione --'] + Department::whereManagementId($management)->pluck('name', 'id')->toArray();
        } else {
            $departments = ['' => '-- Seleccione --'];
        }

        return view('risk.create')
            ->with('measurement_periodicities', $measurement_periodicities)
            ->with('probabilities', $probabilities)
            ->with('inpacts', $inpacts)
            ->with('treatments', $treatments)
            ->with('opportunities', $opportunities)
            ->with('periodicities', $periodicities)
            ->with('automatizations', $automatizations)
            ->with('managements', $managements)
            ->with('management_id', $management)
            ->with('departments', $departments)
            ->with('admin', $admin)
            ->with('view', $view)
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return mixed
     */
    public function postStore(Request $request)
    {
        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();

        if ($admin) {
            $this->rules += ['management' => 'required', 'department' => 'required'];
        }

        $validation = Validator::make($input, $this->rules, $this->messages);
        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput()
                ->with('currentApp', 'ISORISK');
        }

        $risk = new Risk;
        /*
         * * Datos Pestaña Identificacion del Riesgo
         */
        $risk->source = $input['risk_source'];
        $risk->process = $input['process'];
        $risk->detected_risk = $input['detected_risk'];
        $risk->probability_id = intval($input['probability_level']);
        $risk->inpact_id = intval($input['inpact_level']);

        /*
         * * Datos Pestaña Tratamiento del Riesgo
         */
        $risk->treatment_id = intval($input['treatment_id']);
        $risk->preventive_action = $input['preventive_action'];
        $risk->treatment_classification_id = intval($input['treatment_classification_id']);

        /*
         * * Datos Pestaña Exposicion al Riesgo
         */
        $risk->exposure_indicator = $input['exposure_indicator_input'];
        $risk->exposure_level_id = intval($input['exposure_level_id']);

        /*
         * * Datos Pestaña Eficacia
         */
//        $risk->preventive_action_validate = $input['eficacy'];
//        $risk->corrective_action = $input['corrective_action'];
//        $risk->measurement_periodicity_id = intval($input['measurement_periodicity_id']);

        if ($admin) {
            $risk->management_id = $input['management'];
        } else {
            $risk->management_id = Auth::user()->management_id;
        }

        $risk->department_id = $input['department'];
        $risk->id_iso_actual = Auth::user()->id_iso_actual;
        $risk->created_at = new \DateTime();
        if ($risk->save()) {
            $risk->create_history('ha creado Gestión de Riesgo');
            return redirect()->to('application/risk/edit/' . $risk->id)
                ->with('success', 'Gestión del Riesgo creada correctamente!')
                ->with('currentApp', 'ISORISK');
        }

        return redirect()->back()
            ->with('error', 'No se pudo crear Gestión del Riesgo!')
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @param $id
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return mixed
     */
    public function getEdit($id)

    {
        // TODO
        /*if (!$this->check_permission_access_action('LISTADO_RIESGO', 'editar')) {
            return Redirect::to_route('risk_home');
        }*/
        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');
        $management = Auth::user()->management_id;

        $risk = Risk::whereNull('deleted_at')->find($id);
        $access = UserByStandard::where('user_id', '=', Auth::user()->id)->where('status', '=', 1)->whereNull('deleted_at')->pluck('standards_id')->toArray();
        if (in_array($risk->id_iso_actual, $access)) {
            if (Auth::user()->id_iso_actual != $risk->id_iso_actual) {
                $usr = User::find(Auth::user()->id);
                $usr->id_iso_actual = $risk->id_iso_actual;
                $usr->save();
                return redirect('application/risk/home/edit/' . $id);
            }
            if (is_null($risk)) {
                return redirect('application/risk/home')->with('error', 'No se pudo encontrar el item de Gestión de Riesgo.');
            }
            /*
             * Carga datos para los selects
             */

            $managements_ids = ManagementByStadar::where('standard_id', '=', Auth::user()->id_iso_actual)->pluck('management_id')->toArray();
            if ($managements_ids) {
                $managements = ['' => '-- Seleccione --'] + Management::whereIn('id', $managements_ids)->whereStatus(1)->pluck('name', 'id')->toArray();
            } else {
                $managements = ['' => '-- Seleccione --'];
            }
            $probabilities = ['' => '-- Seleccione --'] + RiskProbability::where('status', '=', 1)->pluck('name', 'id')->toArray();
            $inpacts = ['' => '-- Seleccione --'] + RiskInpact::where('status', '=', 1)->pluck('name', 'id')->toArray();
            $treatments = ['' => '-- Seleccione --'] + RiskTreatment::where('status', '=', 1)->pluck('name', 'id')->toArray();
            $opportunities = ['' => '-- Seleccione --'] + RiskOpportunity::where('status', '=', 1)->pluck('name', 'id')->toArray();
            $periodicities = ['' => '-- Seleccione --'] + RiskPeriodicity::where('status', '=', 1)->pluck('name', 'id')->toArray();
            $automatizations = ['' => '-- Seleccione --'] + RiskAutomatization::where('status', '=', 1)->pluck('name', 'id')->toArray();

            /*
             * Datos de la vista
             */


            $classification = RiskClassification::get_instance($risk->probability_id, $risk->inpact_id);
            if ($classification) {
                $severity = $classification->severity;
                $classification_id = $classification->id;
                $classification_name = $classification->color->name;
                $classification_color = $classification->color->color;
            } else {
                $severity = 0;
                $classification_id = 0;
                $classification_name = "";
                $classification_color = "";
            }

            $histories = RiskHistory::where('risk_id', '=', $risk->id)->get();

            $departments = ['' => '-- Seleccione --'] + Department::whereManagementId($risk->management_id)->pluck('name', 'id')->toArray();
            $tracings = RiskTracing::whereNull('deleted_at')->get();
            $readonly = $view ? "readonly" : "";
            $reminders = RiskReminder::where('risk_id', '=', $id)->get();


            $risk_classification = null;
            $measurement_periodicity = "";
            $measurement_periodicity_id = "";

            if ((!is_null($risk->inpact) && $risk->inpact->value != "") && (!is_null($risk->probability->value) && $risk->probability->value != "")) {
                $risk_classification = RiskClassification::get_instance($risk->probability->value, $risk->inpact->value);
                if (!is_null($risk_classification->measurement_periodicity)) {
                    $measurement_periodicity = $risk_classification->measurement_periodicity->name;
                    $measurement_periodicity_id = $risk_classification->measurement_periodicity->id;
                }
            }

            return view('risk.edit')
                ->with('measurement_periodicity', $measurement_periodicity)
                ->with('measurement_periodicity_id', $measurement_periodicity_id)
                ->with('tracings', $tracings)
                ->with('probabilities', $probabilities)
                ->with('inpacts', $inpacts)
                ->with('treatments', $treatments)
                ->with('opportunities', $opportunities)
                ->with('periodicities', $periodicities)
                ->with('automatizations', $automatizations)
                ->with('severity', $severity)
                ->with('classification_id', $classification_id)
                ->with('classification_name', $classification_name)
                ->with('probability_id', $risk->probability_id)
                ->with('inpact_id', $risk->inpact_id)
                ->with('classification_result_color', $classification_color)
                ->with('treatment_id', $risk->treatment_id)
                ->with('preventive_action', $risk->preventive_action)
                ->with('opportunity_id', ($risk->treatment_classification) ? $risk->treatment_classification->risk_opportunity_id : "")
                ->with('periodicity_id', ($risk->treatment_classification) ? $risk->treatment_classification->risk_periodicity_id : "")
                ->with('automatization_id', ($risk->treatment_classification) ? $risk->treatment_classification->risk_automatization_id : "")
                ->with('treatment_classification_id', ($risk->treatment_classification) ? $risk->treatment_classification->id : "")
                ->with('efficiency_control_level', ($risk->exposure_level) ? $risk->exposure_level->control : "")
                ->with('efectivity_control_classification', ($risk->treatment_classification) ? $risk->treatment_classification->control_classification_name : "")
                ->with('efectivity_control_classification_color', ($risk->treatment_classification) ? $risk->treatment_classification->control_classification_color : "")
                ->with('exposure_level', $risk->exposure_level)
                ->with('exposure_indicator', $risk->exposure_indicator)
                ->with('exposure_indicator_color', ($risk->exposure_level) ? $risk->exposure_level->color : "")
                ->with('probability_legend', ($risk->probability) ? $risk->probability->description : "")
                ->with('inpact_legend', ($risk->inpact) ? $risk->inpact->description : "")
                ->with('histories', $histories)
                ->with('risk', $risk)
                ->with('admin', $admin)
                ->with('managements', $managements)
                ->with('management_id', $management)
                ->with('departments', $departments)
                ->with('reminders', $reminders)
                ->with('department_id', $risk->department_id)
                ->with('view', $view)
                ->with('readonly', $readonly)
                ->with('currentApp', 'ISORISK');
        } else {
            return redirect('application/risk/home/');
        }
    }

    /**
     * @param                          $id
     * @param \Illuminate\Http\Request $request
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return mixed
     */
    public function postUpdate($id, Request $request)
    {
        /**
         * @todo Agregar validación de Formulario
         * @todo Agregar Redireccion hacia el Listado de Riesgos
         * @todo Agregar Mensaje de Error o Success al Editar Riesgo
         */
        $admin = Auth::user()->has_role('RISKADM');

        $input = $request->all();

        $validation = Validator::make($input, $this->rules, $this->messages);

        if ($validation->fails()) {
            return Redirect::back()
                ->withErrors($validation)
                ->withInput()
                ->with('currentApp', 'ISORISK');
        }

        $risk = Risk::find($id);

        $inputs_changed = $this->changesValidate($input, $risk);

        $risk->source = $input['risk_source'];
        $risk->process = $input['process'];
        $risk->detected_risk = $input['detected_risk'];
        $risk->probability_id = intval($input['probability_level']);
        $risk->inpact_id = intval($input['inpact_level']);

        /*
         * * Datos Pestaña Tratamiento del Riesgo
         */
        $risk->treatment_id = intval($input['treatment_id']);
        $risk->preventive_action = $input['preventive_action'];
        $risk->treatment_classification_id = intval($input['treatment_classification_id']);

        /*
         * * Datos Pestaña Exposicion al Riesgo
         */
        $risk->exposure_indicator = $input['exposure_indicator_input'];
        $risk->exposure_level_id = $input['exposure_level_id'];


        if ($admin) {
            $risk->management_id = $input['management'];
        }
        $risk->department_id = $input['department'];
        $msg = "";

        $risk->updated_at = new \DateTime();

        if ($risk->save()) {

            $this->saveHistory($inputs_changed, $risk);

            return redirect()->to('/application/risk')
                ->with('success', 'Gestión del Riesgo editada correctamente!')
                ->with('currentApp', 'ISORISK');
        }

        return redirect()->back()
            ->with('error', 'No se pudo editar la Gestión del Riesgo seleccionada!')
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function getDestroy(Request $request)
    {
        // TODO
        /*if (!$this->check_permission_access_action('LISTADO_RIESGO', 'eliminar')) {
            return redirect()->to_route('risk_home');
        }*/
        $risk = Risk::find($request->get('riskId'));
        if ($risk->delete()) {
            return redirect()->back()
                ->with('success', 'Se eliminó Gestión de Riesgo!')
                ->with('currentApp', 'ISORISK');
        }
        return redirect()->back()
            ->with('error', 'No se pudo eliminar Gestión de Riesgo!');
    }

    /**
     * @param $inputs
     * @param $risk
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return array
     */
    public function changesValidate($inputs, $risk)
    {
        $changes = [];

        if ($inputs['management'] != $risk->management_id) {
            array_push($changes, 'Gerencia');
        }

        if ($inputs['department'] != $risk->department_id) {
            array_push($changes, 'Departamento');
        }

        if ($inputs['risk_source'] != $risk->source) {
            array_push($changes, 'Fuente de Riesgo');
        }

        if ($inputs['process'] != $risk->process) {
            array_push($changes, 'Proceso');
        }

        if ($inputs['detected_risk'] != $risk->detected_risk) {
            array_push($changes, 'Riesgo Detectado');
        }

        if ($inputs['probability_level'] != $risk->probability_id) {
            array_push($changes, 'Nivel de Probabilidad');
        }


        if ($inputs['inpact_level'] != $risk->inpact_id) {
            array_push($changes, 'Nivel de Impacto');
        }

        if ($inputs['treatment_id'] != $risk->treatment_id) {
            array_push($changes, 'Tratamiento');
        }

        if ($inputs['preventive_action'] != $risk->preventive_action) {
            array_push($changes, 'Acción Preventiva');
        }

        if ($inputs['opportunity_id'] != $risk->treatment_classification->risk_opportunity_id) {
            array_push($changes, 'Oportunidad');
        }

        if ($inputs['periocidity_id'] != $risk->treatment_classification->risk_periodicity_id) {
            array_push($changes, 'Periocidad');
        }

        if ($inputs['automatization_id'] != $risk->treatment_classification->risk_automatization_id) {
            array_push($changes, 'Automatización');
        }

        return $changes;
    }

    /**
     * @param $inputs_changed
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param $risk
     */
    public function saveHistory($inputs_changed, $risk)
    {
        $msg = "";
        if (count($inputs_changed) > 0) {
            foreach ($inputs_changed as $key => $value) {
                $msg .= $value;
                if ($key == (count($inputs_changed) - 1)) {
                    $msg .= ".";
                } else {
                    $msg .= ", ";
                }
            }
            $risk->create_history("ha editado {$msg}");
        } else {
            $risk->create_history('ha editado Gestión de Riesgo sin realizar cambios');
        }
    }

    /**
     * Muestra listado de Severidad del Riesgo
     */

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return mixed
     */
    public function getSeveridadIndex()
    {
        /*if (!$this->check_permission_access('LISTADO_SEVERIDAD_RIESGO')) {
            return Redirect::to_route('risk_home');
        }*/

        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');

        $risk_classifications = RiskClassification::where('id_iso_actual', '=', Auth::user()->id_iso_actual)->whereNull('deleted_at')->get();

        return view('risk.severidad_index')
            ->with('risk_classifications', $risk_classifications)
            ->with('admin', $admin)
            ->with('view', $view)
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getSeveridadCreate()
    {
        /*if (!$this->check_permission_access_action('LISTADO_SEVERIDAD_RIESGO', 'crear')) {
            return Redirect::to_route('risk_home');
        }*/

        $risk_classification_colors = RiskClassificationColor::whereNull('deleted_at')->get();

        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');

        $measurement_periodicities = RiskMeasurementPeriodicity::where('status', '=', 1)->pluck('name', 'id')->prepend('-- Seleccione --', '')->toArray();
        $risk_probabilities = RiskProbability::where('status', '=', 1)->get();
        $risk_inpacts = RiskInpact::where('status', '=', 1)->get();

        if (!$admin) {
            return redirect()->route('risk_severidad_index')->with('error', 'Usted no tiene permiso para acceder a la página.');
        }

        return view('risk.severidad_create')
            ->with('risk_classification_colors', $risk_classification_colors)
            ->with('measurement_periodicities', $measurement_periodicities)
            ->with('risk_probabilities', $risk_probabilities)
            ->with('risk_inpacts', $risk_inpacts)
            ->with('admin', $admin)
            ->with('view', $view)
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function postSeveridadStore(Request $request)
    {
        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();


        $this->rules = $this->rules_severidad;

        $validation = Validator::make($input, $this->rules, $this->messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput()
                ->with('currentApp', 'ISORISK');
        }

        $risk_classifications = new RiskClassification;
        $risk_classifications->severity = (int)$input['severity_result_div'];
        $risk_classifications->risk_classification_color_id = (int)$input['classificacion'];
        $risk_classifications->measurement_periodicity_id = (int)$input['measurement_periodicity_id'];
        $risk_classifications->risk_inpact_id = (int)$input['risk_inpact_id'];
        $risk_classifications->risk_probability_id = (int)$input['risk_probability_id'];
        $risk_classifications->created_at = new \DateTime();
        $risk_classifications->id_iso_actual = Auth::user()->id_iso_actual;

        if ($risk_classifications->save()) {
            return redirect()->route('risk_severidad_index')
                ->with('success', 'Severidad del Riesgo creada correctamente!')
                ->with('currentApp', 'ISORISK');
        }

        return redirect()->back()
            ->with('error', 'No se pudo crear Severidad del Riesgo!')
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param                          $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function postSeveridadUpdate($id, Request $request)
    {

        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();

        $this->rules = $this->rules_severidad;

        $validation = Validator::make($input, $this->rules, $this->messages);

        if ($validation->fails())
        {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput()
                ->with('currentApp', 'ISORISK');
        }

        $risk_classification = RiskClassification::find($id);

        $inputs_changed = $this->severidadChangesValidate ($input, $risk_classification);

        $risk_classification->severity = $input['severity_result_div'];
        $risk_classification->risk_classification_color_id = $input['classificacion'];
        $risk_classification->measurement_periodicity_id = (int)$input['measurement_periodicity_id'];
        $risk_classification->risk_inpact_id = (int)$input['risk_inpact_id'];
        $risk_classification->risk_probability_id = (int)$input['risk_probability_id'];

        $msg = "";

        $risk_classification->updated_at = new \DateTime();

        if ($risk_classification->save()) {

            return redirect()->route('risk_severidad_index')
                ->with('success', 'Clasificación del Riesgo editada correctamente!')
                ->with('currentApp', 'ISORISK');
        }

        return redirect()->back()
            ->with('error', 'No se pudo actualizar la Clasificación del Riesgo!')
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getSeveridadEdit($id)
    {
        /*if (!$this->check_permission_access_action('LISTADO_SEVERIDAD_RIESGO', 'editar')) {
            return redirect()->route('risk_home');
        }*/

        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');


        $risk_classification = RiskClassification::whereNull('deleted_at')->find($id);

        $access = UserByStandard::where('user_id', '=', Auth::user()->id)->where('status', '=', 1)->whereNull('deleted_at')->pluck('standards_id')->toArray();

        if (in_array($risk_classification->id_iso_actual, $access)) {
            if (Auth::user()->id_iso_actual != $risk_classification->id_iso_actual) {
                $usr = User::find(Auth::user()->id);
                $usr->id_iso_actual = $risk_classification->id_iso_actual;
                $usr->save();
                return redirect()->to('/risk/home/severidad_edit/' . $id);
            }

            $measurement_periodicities = RiskMeasurementPeriodicity::where('status', '=', 1)->pluck('name', 'id')->prepend('-- Seleccione --', '')->toArray();
            $risk_probabilities = RiskProbability::where('status', '=', 1)->get();
            $risk_inpacts = RiskInpact::where('status', '=', 1)->get();

            if (!$admin) {
                return redirect()->route('risk_severidad_index')->with('error', 'Usted no tiene permiso para acceder a la página.');
            }

            if (is_null($risk_classification)) {
                return redirect()->route('risk_severidad_index')->with('error', 'No se pudo encontrar la Severidad de Riesgo.');
            }

            $risk_classification_colors = RiskClassificationColor::where('id_iso_actual', '=', Auth::user()->id_iso_actual)->whereNull('deleted_at')->get();

            $readonly = $view ? "readonly" : "";

            return view('risk.severidad_edit')
                ->with('risk_classification', $risk_classification)
                ->with('measurement_periodicities', $measurement_periodicities)
                ->with('risk_classification_colors', $risk_classification_colors)
                ->with('risk_probabilities', $risk_probabilities)
                ->with('risk_inpacts', $risk_inpacts)
                ->with('admin', $admin)
                ->with('view', $view)
                ->with('readonly', $readonly)
                ->with('currentApp', 'ISORISK');
        } else {
            return redirect()->action("HomeController@severidad_index");
        }
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getSeveridadDestroy(Request $request)
    {
        /*if (!$this->check_permission_access_action('LISTADO_SEVERIDAD_RIESGO', 'eliminar')) {
            return redirect()->route('risk_home');
        }*/

        $risk_classifications = RiskClassification::find($request->get('riskClassificationId'));

        if ($risk_classifications->delete()) {
            return redirect()->back()
                ->with('success', 'Se eliminó la Severidad del Riesgo!')
                ->with('currentApp', 'ISORISK');
        }
        return redirect()->back()
            ->with('error', 'No se pudo eliminar la Severidad del Riesgo!');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param $inputs
     * @param $risk_classification
     *
     * @return array
     */
    public function severidadChangesValidate($inputs, $risk_classification)
    {
        $changes = [];

        if ($inputs['severity_result_div'] != $risk_classification->severity) {
            array_push($changes, 'Severidad del Riesgo');
        }

        if ($inputs['classificacion'] != $risk_classification->risk_classification_color_id) {
            array_push($changes, 'Clasificación');
        }

        if ($inputs['measurement_periodicity_id'] != $risk_classification->measurement_periodicity_id) {
            array_push($changes, 'Periodicidad de Medición');
        }

        if ($inputs['risk_inpact_id'] != $risk_classification->risk_inpact_id) {
            array_push($changes, 'Nivel de Impacto');
        }

        if ($inputs['risk_probability_id'] != $risk_classification->risk_probability_id) {
            array_push($changes, 'Nivel de Probabilidad');
        }

        return $changes;
    }

    /**
     * Muestra listado de Clasificación del Riesgo
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return mixed
     */
    public function getClassificationIndex()
    {
        /*if (!$this->check_permission_access('LISTADO_CLASIFICACION_RIESGO')) {
            return redirect()->route('risk_home');
        }*/

        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');

        $risk_classifications_color = RiskClassificationColor::where('id_iso_actual', '=', Auth::user()->id_iso_actual)->whereNull('deleted_at')->get();

        return view('risk.classification_index')
            ->with('risk_classifications_color', $risk_classifications_color)
            ->with('admin', $admin)
            ->with('view', $view)
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getClassificationCreate()
    {
        /*if (!$this->check_permission_access_action('LISTADO_CLASIFICACION_RIESGO', 'crear')) {
            return redirect()->route('risk_home');
        }*/

        $risk_classification_colors = RiskClassificationColor::where('id_iso_actual', '=', Auth::user()->id_iso_actual)->whereNull('deleted_at')->get();

        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');

        if (!$admin) {
            return redirect()->route('risk_classification_index')->with('error', 'Usted no tiene permiso para acceder a la página.');
        }

        return view('risk.classification_create')
            ->with('risk_classification_colors', $risk_classification_colors)
            ->with('admin', $admin)
            ->with('view', $view)
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postClassificationStore(Request $request)
    {
        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();

        $this->rules = $this->rules_classification;

        $validation = Validator::make($input, $this->rules, $this->messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput()
                ->with('currentApp', 'ISORISK');
        }

        $risk_classifications_color = new RiskClassificationColor;
        $risk_classifications_color->name = $input['name'];
        $risk_classifications_color->color = $input['color'];
        $risk_classifications_color->id_iso_actual = Auth::user()->id_iso_actual;

        if ($risk_classifications_color->save()) {
            return redirect()->route('risk_classification_index')
                ->with('success', 'Clasificación del Riesgo creada correctamente!')
                ->with('currentApp', 'ISORISK');
        }

        return redirect()->back()
            ->with('error', 'No se pudo crear Severidad del Riesgo!')
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param                          $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function postClassificationUpdate($id, Request $request)
    {
        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();

        $this->rules = $this->rules_classification;

        $validation = Validator::make($input, $this->rules, $this->messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput()
                ->with('currentApp', 'ISORISK');
        }

        $risk_classification_color = RiskClassificationColor::find($id);

        $inputs_changed = $this->classificationChangesValidate($input, $risk_classification_color);

        $risk_classification_color->name = $input['name'];
        $risk_classification_color->color = $input['color'];

        $msg = "";

        $risk_classification_color->updated_at = new \DateTime();

        if ($risk_classification_color->save()) {

            return redirect()->route('risk_classification_index')
                ->with('success', 'Clasificación del Riesgo editada correctamente!')
                ->with('currentApp', 'ISORISK');
        }

        return redirect()->back()
            ->with('error', 'No se pudo actualizar la Clasificación del Riesgo!')
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getClassificationEdit($id)
    {
        /*if (!$this->check_permission_access_action('LISTADO_CLASIFICACION_RIESGO', 'editar')) {
            return redirect()->route('risk_home');
        }*/

        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');

        if (!$admin) {
            return redirect()->route('risk_classification_index')->with('error', 'Usted no tiene permiso para acceder a la página.');
        }

        $risk_classification_colors = RiskClassificationColor::find($id);

        $access = UserByStandard::where('user_id', '=', Auth::user()->id)->where('status', '=', 1)->whereNull('deleted_at')->pluck('standards_id')->toArray();

        if (in_array($risk_classification_colors->id_iso_actual, $access)) {
            if (Auth::user()->id_iso_actual != $risk_classification_colors->id_iso_actual) {
                $usr = User::find(Auth::user()->id);
                $usr->id_iso_actual = $risk_classification_colors->id_iso_actual;
                $usr->save();
                return redirect()->to('/risk/home/classification_edit/' . $id);
            }

            if (is_null($risk_classification_colors)) {
                return redirect()->route('risk_classification_index')->with('error', 'No se pudo encontrar la Clasificación del Riesgo.');
            }

            $readonly = $view ? "readonly" : "";

            return view('risk.classification_edit')
                ->with('risk_classification_color', $risk_classification_colors)
                ->with('admin', $admin)
                ->with('view', $view)
                ->with('readonly', $readonly)
                ->with('currentApp', 'ISORISK');
        } else {
            return redirect()->to('/risk/home/classification_index/');
        }
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getClassificationDestroy(Request $request)
    {
        /*if (!$this->check_permission_access_action('LISTADO_CLASIFICACION_RIESGO', 'eliminar')) {
            return redirect()->route('risk_home');
        }*/

        $risk_classifications_colors = RiskClassificationColor::find($request->get('riskClassificationId'));

        if (RiskClassificationColor::is_used_by_risk_classification($risk_classifications_colors->id)) {
            return redirect()->back()
                ->with('error', 'No eliminó la Clasificación, hay Severidad de Riesgo asociada al registro!')
                ->with('currentApp', 'ISORISK');
        }

        if ($risk_classifications_colors->delete()) {
            return redirect()->back()
                ->with('success', 'Se eliminó la Clasificación del Riesgo!')
                ->with('currentApp', 'ISORISK');
        }
        return redirect()->back()
            ->with('error', 'No se pudo eliminar la Severidad del Riesgo!');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param $inputs
     * @param $risk_classification
     *
     * @return array
     */
    public function classificationChangesValidate($inputs, $risk_classification)
    {
        $changes = [];

        if ($inputs['name'] != $risk_classification->name) {
            array_push($changes, 'Nombre');
        }

        if ($inputs['color'] != $risk_classification->color) {
            array_push($changes, 'Color');
        }

        return $changes;
    }

    /**
     *
     * Muestra listado de Exposición del Riesgo
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getExposureIndex()
    {
        /*if (!$this->check_permission_access('LISTADO_EXPOSICION_RIESGO')) {
            return redirect()->route('risk_home');
        }*/

        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');

        $risk_exposures = RiskExposureLevel::where('id_iso_actual', '=', Auth::user()->id_iso_actual)->whereNull('deleted_at')->get();

        return view('risk.exposure_index')
            ->with('risk_exposures', $risk_exposures)
            ->with('admin', $admin)
            ->with('view', $view)
            ->with('currentApp', 'ISORISK');
    }

    /**
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function getExposureCreate()
    {
        /*if (!$this->check_permission_access_action('LISTADO_EXPOSICION_RIESGO', 'crear')) {
            return redirect()->route('risk_home');
        }*/
        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');

        if (!$admin) {
            return redirect()->route('/application/risk/exposure')->with('error', 'Usted no tiene permiso para acceder a la página.');
        }

        return view('risk.exposure_create')
            ->with('admin', $admin)
            ->with('view', $view)
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postExposureStore(Request $request)
    {
        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();

        $this->rules = $this->rules_exposure;

        $validation = Validator::make($input, $this->rules, $this->messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput()
                ->with('currentApp', 'ISORISK');
        }

        $risk_exposure = new RiskExposureLevel();
        $risk_exposure->severity = $input['severity'];
        $risk_exposure->control = $input['control'];
        $risk_exposure->name = $input['name'];
        $risk_exposure->color = $input['color'];
        $risk_exposure->id_iso_actual = Auth::user()->id_iso_actual;

        if ($risk_exposure->save()) {
            return redirect()->route('/application/risk/exposure')
                ->with('success', 'Exposición al Riesgo creada correctamente!')
                ->with('currentApp', 'ISORISK');
        }

        return redirect()->back()
            ->with('error', 'No se pudo crear Exposición al Riesgo!')
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @param                          $id
     * @param \Illuminate\Http\Request $request
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postExposureUpdate($id, Request $request)
    {

        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();

        $this->rules = $this->rules_exposure;

        $validation = Validator::make($input, $this->rules, $this->messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput()
                ->with('currentApp', 'ISORISK');
        }

        $risk_exposure = RiskExposureLevel::find($id);

        $inputs_changed = $this->exposureChangesValidate($input, $risk_exposure);

        $risk_exposure->severity = $input['severity'];
        $risk_exposure->control = $input['control'];
        $risk_exposure->name = $input['name'];
        $risk_exposure->color = $input['color'];

        $msg = "";

        $risk_exposure->updated_at = new \DateTime();

        if ($risk_exposure->save()) {

            return redirect()->route('/application/risk/exposure')
                ->with('success', 'Exposición al Riesgo editada correctamente!')
                ->with('currentApp', 'ISORISK');
        }

        return redirect()->back()
            ->with('error', 'No se pudo actualizar la Exposición al Riesgo!')
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @param $id
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function getExposureEdit($id)
    {
        /*if (!$this->check_permission_access_action('LISTADO_EXPOSICION_RIESGO', 'editar')) {
            return redirect()->route('risk_home');
        }*/
        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');

        if (!$admin) {
            return redirect()->route('/application/risk/exposure')->with('error', 'Usted no tiene permiso para acceder a la página.');
        }

        $risk_exposure = RiskExposureLevel::find($id);
        $access = UserByStandard::where('user_id', '=', Auth::user()->id)->where('status', '=', 1)->whereNull('deleted_at')->pluck('standards_id')->toArray();
        if (in_array($risk_exposure->id_iso_actual, $access)) {
            if (Auth::user()->id_iso_actual != $risk_exposure->id_iso_actual) {
                $usr = User::find(Auth::user()->id);
                $usr->id_iso_actual = $risk_exposure->id_iso_actual;
                $usr->save();
                return redirect()->to('/risk/home/exposure_edit/' . $id);
            }

            if (is_null($risk_exposure)) {
                return redirect()->route('risk_exposure_index')->with('error', 'No se pudo encontrar la Exposición al Riesgo.');
            }

            $readonly = $view ? "readonly" : "";

            return view('risk.exposure_edit')
                ->with('risk_exposure', $risk_exposure)
                ->with('admin', $admin)
                ->with('view', $view)
                ->with('readonly', $readonly)
                ->with('currentApp', 'ISORISK');
        } else {
            return redirect('/risk/home/exposure_index/' . $id);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function getExposureDestroy(Request $request)
    {
        /*if (!$this->check_permission_access_action('LISTADO_EXPOSICION_RIESGO', 'eliminar')) {
            return redirect()->route('risk_home');
        }*/
        $risk_exposure = RiskExposureLevel::find($request->get('riskExposureId'));

        if (RiskExposureLevel::is_used_by_risk($risk_exposure->id)) {
            return redirect()->back()
                ->with('error', 'No se eliminó la Exposición al Riesgo, hay registros asociados!')
                ->with('currentApp', 'ISORISK');
        }

        if ($risk_exposure->delete()) {
            return redirect()->back()
                ->with('success', 'Se eliminó la Exposición al Riesgo!')
                ->with('currentApp', 'ISORISK');
        }
        return redirect()->back()
            ->with('error', 'No se pudo eliminar la Exposición al Riesgo!');
    }

    public function exposureChangesValidate($inputs, $risk_exposure)
    {
        $changes = [];

        if ($inputs['severity'] != $risk_exposure->severity) {
            array_push($changes, 'Severidad del Riesgo');
        }

        if ($inputs['control'] != $risk_exposure->control) {
            array_push($changes, 'Nivel de Eficiencia del Control');
        }

        if ($inputs['name'] != $risk_exposure->name) {
            array_push($changes, 'Clasificación de Efectividad del Control');
        }

        if ($inputs['color'] != $risk_exposure->color) {
            array_push($changes, 'Color');
        }

        return $changes;
    }

    /**
     * Muestra listado de Tratamiento del Riesgo
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return mixed
     */
    public function getTreatmentIndex()
    {
        /*if (!$this->check_permission_access('LISTADO_TRATAMIENTO_RIESGO')) {
            return redirect()->route('risk_home');
        }*/

        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');

        $risk_treatments = RiskTreatmentClassification::where('id_iso_actual', '=', Auth::user()->id_iso_actual)->whereNull('deleted_at')->get();

        return view('risk.treatment_index')
            ->with('risk_treatments', $risk_treatments)
            ->with('admin', $admin)
            ->with('view', $view)
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getTreatmentCreate()
    {
        /*if (!$this->check_permission_access_action('LISTADO_TRATAMIENTO_RIESGO', 'crear')) {
            return redirect()->route('risk_home');
        }*/

        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');

        $opportunities = RiskOpportunity::where('status', '=', 1)->get();
        $periodicities = RiskPeriodicity::where('status', '=', 1)->get();
        $automatizations = RiskAutomatization::where('status', '=', 1)->get();

        if (!$admin) {
            return redirect()->route('risk_treatment_index')->with('error', 'Usted no tiene permiso para acceder a la página.');
        }

        return view('risk.treatment_create')
            ->with('risk_opportunities', $opportunities)
            ->with('risk_periodicities', $periodicities)
            ->with('risk_automatizations', $automatizations)
            ->with('admin', $admin)
            ->with('view', $view)
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postTreatmentStore(Request $request)
    {
        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();

        $this->rules = $this->rules_treatment;

        $validation = Validator::make($input, $this->rules, $this->messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput()
                ->with('currentApp', 'ISORISK');
        }

        $risk_treatment = new RiskTreatmentClassification();
        $risk_treatment->risk_opportunity_id = $input['risk_opportunity_id'];
        $risk_treatment->risk_periodicity_id = $input['risk_periodicity_id'];
        $risk_treatment->risk_automatization_id = $input['risk_automatization_id'];
        $risk_treatment->control_classification_name = $input['control_classification_name'];
        $risk_treatment->control_classification_color = $input['control_classification_color'];
        $risk_treatment->control_valorization_name = $input['control_valorization_name'];
        $risk_treatment->control_valorization_color = $input['control_valorization_color'];
        $risk_treatment->id_iso_actual = Auth::user()->id_iso_actual;

        if ($risk_treatment->save()) {
            return redirect()->route('risk_treatment_index')
                ->with('success', 'Tratamiento del Riesgo creado correctamente!')
                ->with('currentApp', 'ISORISK');
        }

        return redirect()->back()
            ->with('error', 'No se pudo crear el Tratamiento del Riesgo!')
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param                          $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function postTreatmentUpdate($id, Request $request)
    {

        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();

        $this->rules = $this->rules_treatment;

        $validation = Validator::make($input, $this->rules, $this->messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput()
                ->with('currentApp', 'ISORISK');
        }

        $risk_treatment = RiskTreatmentClassification::find($id);

        $inputs_changed = $this->treatmentChangesValidate($input, $risk_treatment);

        $risk_treatment->risk_opportunity_id = $input['risk_opportunity_id'];
        $risk_treatment->risk_periodicity_id = $input['risk_periodicity_id'];
        $risk_treatment->risk_automatization_id = $input['risk_automatization_id'];
        $risk_treatment->control_classification_name = $input['control_classification_name'];
        $risk_treatment->control_classification_color = $input['control_classification_color'];
        $risk_treatment->control_valorization_name = $input['control_valorization_name'];
        $risk_treatment->control_valorization_color = $input['control_valorization_color'];

        $msg = "";

        $risk_treatment->updated_at = new \DateTime();

        if ($risk_treatment->save()) {

            return redirect()->route('risk_treatment_index')
                ->with('success', 'Tratamiento del Riesgo editado correctamente!')
                ->with('currentApp', 'ISORISK');
        }

        return redirect()->back()
            ->with('error', 'No se pudo actualizar el Tratamiento del Riesgo!')
            ->with('currentApp', 'ISORISK');
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getTreatmentEdit($id)
    {
        /*if (!$this->check_permission_access_action('LISTADO_TRATAMIENTO_RIESGO', 'editar')) {
            return redirect('risk/home/treatment_index');
        }*/

        $admin = Auth::user()->has_role('RISKADM');
        $view = Auth::user()->has_role('RISKVIEW');

        if (!$admin) {
            return redirect()->route('risk_treatment_index')->with('error', 'Usted no tiene permiso para acceder a la página.');
        }

        $opportunities = RiskOpportunity::where('status', '=', 1)->get();
        $periodicities = RiskPeriodicity::where('status', '=', 1)->get();
        $automatizations = RiskAutomatization::where('status', '=', 1)->get();

        $risk_treatment = RiskTreatmentClassification::find($id);
        $access = UserByStandard::where('user_id', '=', Auth::user()->id)->where('status', '=', 1)->whereNull('deleted_at')->pluck('standards_id')->toArray();
        if (in_array($risk_treatment->id_iso_actual, $access)) {
            if (Auth::user()->id_iso_actual != $risk_treatment->id_iso_actual) {
                $usr = User::find(Auth::user()->id);
                $usr->id_iso_actual = $risk_treatment->id_iso_actual;
                $usr->save();
                return redirect()->to('risk/home/treatment_edit/' . $id);
            }

            if (is_null($risk_treatment)) {
                return redirect()->route('risk_treatment_index')->with('error', 'No se pudo encontrar el Tratamiento del Riesgo.');
            }

            $readonly = $view ? "readonly" : "";

            return view('risk.treatment_edit')
                ->with('risk_treatment', $risk_treatment)
                ->with('risk_opportunities', $opportunities)
                ->with('risk_periodicities', $periodicities)
                ->with('risk_automatizations', $automatizations)
                ->with('admin', $admin)
                ->with('view', $view)
                ->with('readonly', $readonly)
                ->with('currentApp', 'ISORISK');
        } else {
            return redirect()->to('risk/home/treatment_index/');
        }
    }

    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getTreatmentDestroy(Request $request)
    {
        /*if (!$this->check_permission_access_action('LISTADO_TRATAMIENTO_RIESGO', 'eliminar')) {
            return redirect()->route('risk_home');
        }*/
        $risk_treatment = RiskTreatmentClassification::find($request->get('riskTreatmentId'));

        if (RiskTreatmentClassification::is_used_by_risk($risk_treatment->id)) {
            return redirect()->back()
                ->with('error', 'No se eliminó el Tratamiento del Riesgo, hay registros asociados!')
                ->with('currentApp', 'ISORISK');
        }

        if ($risk_treatment->delete()) {
            return redirect()->back()
                ->with('success', 'Se eliminó el Tratamiento del Riesgo!')
                ->with('currentApp', 'ISORISK');
        }
        return redirect()->back()
            ->with('error', 'No se pudo eliminar el Tratamiento del Riesgo!');
    }

    public function treatmentChangesValidate($inputs, $risk_treatment)
    {
        $changes = [];

        if ($inputs['risk_opportunity_id'] != $risk_treatment->risk_opportunity_id) {
            array_push($changes, 'Oportunidad');
        }

        if ($inputs['risk_periodicity_id'] != $risk_treatment->risk_periodicity_id) {
            array_push($changes, 'Periocidad');
        }

        if ($inputs['risk_automatization_id'] != $risk_treatment->risk_automatization_id) {
            array_push($changes, 'Automatización');
        }

        if ($inputs['control_classification_name'] != $risk_treatment->control_classification_name) {
            array_push($changes, 'Nivel de Eficiencia del Control');
        }

        if ($inputs['control_classification_color'] != $risk_treatment->control_classification_color) {
            array_push($changes, 'Color - Eficiencia del Control');
        }

        if ($inputs['control_valorization_name'] != $risk_treatment->control_valorization_name) {
            array_push($changes, 'Clasificación de Efectividad del Control');
        }

        if ($inputs['control_valorization_color'] != $risk_treatment->control_valorization_color) {
            array_push($changes, 'Color - Efectividad del Control');
        }

        return $changes;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return string
     */
    public function getAddTracing(Request $request)
    {
        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();
        $response = [];
        $risk_trancing = new RiskTracing();

        $risk_trancing->risk_id = $input['risk_id'];
        $risk_trancing->preventive_action_validate = $input['preventive_action_validate'];
        $risk_trancing->corrective_action = $input['corrective_action'];
        $risk_trancing->date_starts = Utils::convert_date_es_to_en($input['date_starts']);
        $risk_trancing->date_ends = Utils::convert_date_es_to_en($input['date_ends']);
        $date_to_reminder = $risk_trancing->date_starts;

        if ($risk_trancing->save()) {

            $risk = Risk::find($risk_trancing->risk_id);
            $risk->create_history('ha creado un Seguimiento');

//Genera notificación
            /* $message = "Tienes hay un seguimiento de gestión de riesgo con fecha de inicio hoy";
              $url = URL::to_route('risk_edit', $input['risk_id']);
              Risk::notify_admin_risk($message, $url, $risk_trancing->risk_id, $date_to_reminder); */

            $response['status'] = 'success';
            $response['msg'] = 'El seguimiento ha sido guardado!';
        } else {
            $response['status'] = 'error';
            $response['msg'] = 'No se pudo crear el Seguimiento del Riesgo!';
        }

        return json_encode($response);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @return string
     */
    public function getEditTracing(Request $request)
    {
        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();
        $response = [];

        $risk_trancing = RiskTracing::find($input['risk_tracing_id']);

        $input_changed = $this->changesValidateTracing($input, $risk_trancing);

        $risk_trancing->id = $input['risk_tracing_id'];
        $risk_trancing->risk_id = $input['risk_id'];
        $risk_trancing->preventive_action_validate = $input['preventive_action_validate'];
        $risk_trancing->corrective_action = $input['corrective_action'];
        $risk_trancing->date_starts = Utils::convert_date_es_to_en($input['date_starts']);
        $risk_trancing->date_ends = Utils::convert_date_es_to_en($input['date_ends']);

        $risk_trancing->updated_at = new \DateTime();

        if ($risk_trancing->save()) {

            $risk = Risk::find($risk_trancing->risk_id);

            $this->saveHistoryTracing($input_changed, $risk);

            $response['status'] = 'success';
            $response['msg'] = 'El seguimiento ha sido actualizado!';
        } else {
            $response['status'] = 'error';
            $response['msg'] = 'No se pudo actualizar el Seguimiento del Riesgo!';
        }

        return json_encode($response);
    }

    public function saveHistoryTracing($inputs_changed, $risk)
    {
        $msg = "";
        if (count($inputs_changed) > 0) {
            foreach ($inputs_changed as $key => $value) {
                $msg .= $value;
                if ($key == (count($inputs_changed) - 1)) {
                    $msg .= ".";
                } else {
                    $msg .= ", ";
                }
            }
            $risk->create_history("ha editado {$msg}");
        } else {
            $risk->create_history('ha editado un Seguimiento sin realizar cambios');
        }
    }

    public function changesValidateTracing($inputs, $risk_tracing)
    {

        $changes = [];

        if ($inputs['preventive_action_validate'] != $risk_tracing->preventive_action_validate) {
            array_push($changes, 'Verificación de eficacia de la acción preventiva');
        }

        if ($inputs['corrective_action'] != $risk_tracing->corrective_action) {
            array_push($changes, 'Acción Correctiva en Caso de Ocurrencia');
        }

        if ($inputs['date_starts'] != $risk_tracing->date_starts) {
            array_push($changes, 'Fecha Inicio');
        }

        if ($inputs['date_ends'] != $risk_tracing->date_ends) {
            array_push($changes, 'Fecha Término');
        }

        return $changes;
    }

    public function postCreateReminder(Request $request)
    {

        $admin = Auth::user()->has_role('RISKADM');
        $input = $request->all();
        $response = [];
        $legend = $input['legend'];


        $measurement = RiskMeasurementPeriodicities::find($input['measurement_periodicities_id']);
        $en_from_date = Utils::convert_date_es_to_en($input['original_reference_date_from']);
        $en_to_date = Utils::convert_date_es_to_en($input['original_reference_date_until']);

//Get days between two dates.
        $start = date_create($en_from_date);
        $end = date_create($en_to_date);
        $end_measurement = date_create(date('Y-m-d', strtotime($en_from_date . ' ' . $measurement->code)));
        $diff = date_diff($start, $end);
        $diff_selected = date_diff($start, $end_measurement);

        $valide_interval = ($diff->days >= $diff_selected->days);
        $ids = [];

        if ($valide_interval) {
            $period = new DatePeriod(new DateTime($en_from_date), DateInterval::createFromDateString($measurement->code), new DateTime($en_to_date));

            foreach ($period as $key => $value) {
                $risk_reminders = new RiskReminder();

                $risk_reminders->risk_id = $input['risk_id'];
                $risk_reminders->measurement_periodicities_id = $input['measurement_periodicities_id'];
                $risk_reminders->original_reference_date_from = $en_from_date;
                $risk_reminders->original_reference_date_until = $en_to_date;
                $risk_reminders->send_reminders_at = $value->format('Y-m-d');
                $date_to_reminder = $value->format('Y-m-d');

                if ($risk_reminders->save()) {
                    array_push($ids, $risk_reminders->id);

                    //Genera notificación
                    $message = "Tienes un recordatorio en gestión de riesgo con fecha de inicio hoy";
                    $url = URL::to_route('risk_edit', $input['risk_id']);
                    Risk::notify_admin_risk($message, $url, $input['risk_id'], $date_to_reminder);

                    /*
                      $risk = Risk::find($risk_reminders->risk_id);
                      $risk->create_history('ha creado un Recordatorio con fecha '.Utils::convert_date_en_to_es($risk_reminders->send_reminders_at));
                     */
                } else {
                    $response['status'] = 'error';
                    $response['msg'] = 'No se pudo crear el Recordatorio! (' . $e . ')';

                    return json_encode($response);
                }
            }
        } else {
            $response['status'] = 'error';
            $response['msg'] = 'El período debe ser ' . $legend;

            return json_encode($response);
        }

        if (count($ids) > 0) {
            RiskReminder::set_inactivo_by_risk_id($input['risk_id'], $ids);
        }

        $response['status'] = 'success';
        $response['msg'] = 'El Recordatorio ha sido guardado!';
        $response['$period'] = $period;
        $response['$valide_interval'] = $valide_interval;

        return json_encode($response);
    }

}
