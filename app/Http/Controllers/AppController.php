<?php namespace App\Http\Controllers;

class AppController extends Controller
{
    public $viewTitle;
    public $signedIn;
    public $authUser;

    public function setTitle($title){
        $this->viewTitle = $title;
    }
}