<?php

namespace App\Http\Controllers\Authorized\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $leads = Lead::latest()->with('leadStatus')->isPrivate(false)->paginate();
        $leadStatuses = LeadStatus::latest()->get();
        return Inertia::render('authorized/Leads', [
            'leads' => $leads,
            'leadStatuses' => $leadStatuses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
