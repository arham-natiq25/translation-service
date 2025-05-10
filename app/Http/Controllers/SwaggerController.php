<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class SwaggerController extends Controller
{
    public function index()
    {
        return view('swagger');
    }

    public function json()
    {
        $swagger = File::get(public_path('swagger.json'));
        return Response::make($swagger, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
