<?php

namespace App\Http\Controllers;

class LeadController extends Controller
{
    public function leadsTemplate()
    {
        return response()->download(public_path('/leads-upload-template.csv'));
    }
}
