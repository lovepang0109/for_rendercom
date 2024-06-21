<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class HomeController extends Controller
{
    //
    public function index(){
        return view('app');
    }

     public function kkk(){

        $result = $this->visitGoogle();
        return view('kkk', ['path'=>$result]);
    }


    // In your controller or route
    public function visitGoogle()
    {
        $response = Http::get('https://for-rendercom.onrender.com');

        // You can now work with the response
        $statusCode = $response->status();
        $body = $response->body();

        // For example, you can return the response body
        return $body;
    }


}
