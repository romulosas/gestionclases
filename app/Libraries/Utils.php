<?php
namespace App\Libraries;

use App\Models\Admin\Security\User;
use App\Models\Admin\Security\Role;
use App\Models\Admin\Security\RoleByUser;
use DB;
use Datetime;
use Hash;

class Utils {

    public static function get_user_by_token($token){
        $records =  DB::table('password_resets')->get();
        foreach ($records as $record) {
            if (Hash::check($token, $record->token) ) {
                return $record->email;
            }
        }
    }

    public static function convert_date_es_to_en($date)
    {
        if (strlen($date) < 10) {
            return "";
        }

        $date = self::left($date, 10);
        $date = str_replace("-", "/", $date);

        if ($date == '00/00/0000') {
            return "";
        }

        $parts = explode("/", $date);

        return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
    }

    public static function days_passed($fecha_i, $fecha_f) {
        $dias = (strtotime($fecha_i) - strtotime($fecha_f)) / 86400;
        $dias = abs($dias);
        $dias = floor($dias);
        return (int) $dias + 1;
    }

    public static function date_en_to_es($date) {
        $format = 'd/m/Y';
        if ($date instanceof Datetime)
            return $date->format($format);

        return date($format, strtotime($date));
    }

    public static function datetime_en_to_es($date, $showSeconds = false) {
        if ($date == '')
            return '';

        $format = 'd/m/Y H:i' . (($showSeconds) ? ':s' : '');
        if ($date instanceof Datetime)
            return $date->format($format);

        return date($format, strtotime($date));
    }

    public static function date_es_to_en($date)
    {
        $format = 'Y-m-d';

        if ($date instanceof Datetime) {
            return $date->format($format);
        }

        return date($format, strtotime(str_replace("/", "-", $date)));
    }

    public static function convert_date_en_to_es($date, $format = "d/m/Y")
    {

        if (is_null($date)) {
            return "";
        }

        // Valida si fecha ya se encuentra en el formato ES y retorna el mismo valor.
        if (strpos($date, '/') !== false) {
            return $date;
        }

        if (strlen($date) < 10)
            return "";
        if (strlen($date) == 10)
            $date .= ' 00:00:00';
        else
            $date = self::left($date, 19);

        if ($date == '00/00/0000 00:00:00' or $date == '0000-00-00 00:00:00')
            return "";

        return date($format, strtotime($date));
    }


    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param  string $date [description]
     * @param  string $type [description]
     *
     * @return string
     */
    public static function convert_datetime_es_to_en($date, $type = "Normal")
    {

        if ($type == 'strtotime') {

            $fecha_esp = str_replace("/", "-", $date);
            $timestamp = strtotime($fecha_esp);

            return date("Y-m-d H:i", $timestamp);
        }

        if (strlen($date) < 10) {
            return "";
        }

        if (strlen($date) == 10) {
            $date .= ' 00:00:00';
        }
        else {
            $date = self::left($date, 19);
        }

        $date = str_replace("-", "/", $date);

        if ($date == '00/00/0000 00:00:00') {
            return "";
        }

        $time = self::right($date, 8);
        $date = self::left($date, 10);

        $parts = explode("/", $date);

        return $parts[2] . '-' . $parts[1] . '-' . $parts[0] . ' ' . $time;
    }


    /**
     * @version 1.0 {@internal Laravel 5.8}
     *
     * @param  string $date
     * @param  string $format
     *
     * @return [type]         [description]
     */
    public static function convert_datetime_en_to_es($date, $format = "d/m/Y H:i") {

        // Valida si fecha ya se encuentra en el formato ES y retorna el mismo valor.
        if (strpos($date, '/') !== false) {
            return $date;
        }

        if (strlen($date) < 10) {
            return "";
        }

        if (strlen($date) == 10) {
            $date .= ' 00:00:00';
        }
        else {
            $date = self::left($date, 19);
        }

        if ($date == '00/00/0000 00:00:00' || $date == '0000-00-00 00:00:00') {
            return "";
        }

        return date($format, strtotime($date));
    }

    public static function datetime_es_to_en($date, $showSeconds = false) {
        $format = 'Y-m-d H:i' . (($showSeconds) ? ':s' : '');
        if ($date instanceof Datetime)
            return $date->format($format);

        return date($format, strtotime(str_replace("/", "-", $date)));
    }


    public static function getActiveUsers()
    {
        return User::whereStatus(1)->orderBy('name')->get();
    }

    public static function monthCmb() {
        $months = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];

        return $months;
    }

    public static function get_month_name($i){
        switch ($i) {
            case 1: return "Enero";
            case 2: return "Febrero";
            case 3: return "Marzo";
            case 4: return "Abril";
            case 5: return "Mayo";
            case 6: return "Junio";
            case 7: return "Julio";
            case 8: return "Agosto";
            case 9: return "Septiembre";
            case 10: return "Octubre";
            case 11: return "Noviembre";
            case 12: return "Diciembre";
            default: return "";
        }
    }

    //calendar
    public static function uuid() {
        $t = explode(" ", microtime());

        return sprintf('%04x-%08s-%08s-%04s-%04x%04x', rand(), rand(), substr("00000000" . dechex($t[1]), -8), // get 8HEX of unixtime
            substr("0000" . dechex(round($t[0] * 65536)), -4), // get 4HEX of microtime
            mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

    public static function getWeekDay($date)
    {
        $date = explode(' ', $date);
        $date = explode('-', $date[0]);

        $day = date("w", mktime(0, 0, 0, $date[1], $date[2], $date[0]));

        return $day;
    }

    public static function minutesToHours($minutes = 0) {
        $aux = $minutes;
        $hours_final = sprintf('% 02d', floor($aux / 60));
        $minutes_final = sprintf('% 02d ', floor($aux % 60));

        return $hours_final . ':' . $minutes_final;
    }

    public static function DateToLongSpanishFormat($date) {

        $year = Carbon::parse(Utils::date_es_to_en($date))->year;
        $month = date('n', strtotime($date));
        $day = date('d', strtotime($date));
        $day_of_week = date('w', strtotime($date));
        $days = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
        $months = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        return $days[$day_of_week] . ", $day de " . $months[$month] . " de $year";
    }

    public static function validateRut($rut) {
        if (!preg_match("/^[0-9.]+[-]?+[0-9kK]{1}/", $rut)) {
            return false;
        }
        $rut = preg_replace('/[\.\-]/i', '', $rut);
        $dv = substr($rut, -1);
        $numero = substr($rut, 0, strlen($rut) - 1);
        $i = 2;
        $suma = 0;
        foreach (array_reverse(str_split($numero)) as $v) {
            if ($i == 8)
                $i = 2;
            $suma += $v * $i;
            ++$i;
        }
        $dvr = 11 - ($suma % 11);

        if ($dvr == 11)
            $dvr = 0;
        if ($dvr == 10)
            $dvr = 'K';
        if ($dvr == strtoupper($dv))
            return true;
        else
            return false;
    }

    public static function usersByRol($code = null) {
        if ($code) {
            $users = G\Security\VwRolesByUser::whereCode($code)->lists('user_id');
            return $users;
        } else {
            return [];
        }
    }

    public static function currencyToInternationalFormat($currency) {
        $currency = str_replace(".", "", $currency);
        $currency = str_replace(",", ".", $currency);
        return $currency;
    }

    public static function usersByRoleCmb($roleCode)
    {
        $userCmb = \G\Security\VwRolesByUserAll::whereCode($roleCode)->orderBy('name')->lists('name', 'id');
        $userCmb = ['' => 'Seleccione un Usuario'] + $userCmb;
        return $userCmb;
    }

    public static function getYearsCombo($start_year, $current = FALSE)
    {
        $cmb_years = [];
        $end_year = $current ? date('Y') + $current : date('Y');
        // $years = [];
        for ($i = $start_year; $i <= $end_year; $i++) {
            $cmb_years[$i] = $i;
            // $years[] = $i;
        }
        return $cmb_years;
    }

    public static function get_cmb_year_until($start, $yearsToAdd = 0) {
        $maxYear = $start + $yearsToAdd;
        for ($i = $start; $i <= $maxYear; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }

    public static function limit_text($text, $max_char = 500) {
        $text = strip_tags($text);
        if (strlen($text) > $max_char) {

            $textCut = substr($text, 0, $max_char);
            $endPoint = strrpos($textCut, ' ');

            $text = $endPoint ? substr($textCut, 0, $endPoint) : substr($textCut, 0);
            $text .= '...';
        }
        return $text;
    }

    public static function get_cmb_year($text = null) {
        $maxYear = date('Y') + 2;

        $years = array('' => ($text) ? $text : '--Seleccione--');

        for ($i = 2009; $i <= $maxYear; $i++) {
            $years[$i] = $i;
        }

        return $years;
    }

    public static function get_cmb_month($text = null) {
        return array(
            '' => ($text) ? $text : '--Seleccione--',
            '01' => "Enero",
            '02' => "Febrero",
            '03' => "Marzo",
            '04' => "Abril",
            '05' => "Mayo",
            '06' => "Junio",
            '07' => "Julio",
            '08' => "Agosto",
            '09' => "Septiembre",
            10 => "Octubre",
            11 => "Noviembre",
            12 => "Diciembre"
        );
    }
}
