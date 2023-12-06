<?php

namespace App\Http\Controllers;

class DomainBaseCtrl extends Controller
{
    protected $auth;
    protected $customer;

    public function __construct()
    {
        $this->auth = auth()?->user()?->load('profile');
        $this->customer = $this->auth;
    }
}
