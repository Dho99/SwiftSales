<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UrlHandler extends Controller
{

    protected $currentUrl;

    public function __construct(){
        $this->currentUrl = url()->current();
    }

    public function beforeCreateUrl(){
        $currentUrl = url()->current();
        $lastSlash = strrpos($currentUrl,'/');
        $result = substr($currentUrl, 0, $lastSlash);
        return $result;
        // return $beforeThisUrl;
    }

    public function storeFormUrl(){
        $storeFormUrl = Str::replace('/create', '', $this->currentUrl);
        return $storeFormUrl;
    }


}
