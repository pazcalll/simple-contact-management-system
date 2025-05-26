<?php

namespace App\Http\Controllers\Authorized\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreLeadRequest;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\Role;
use App\Models\User;
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
        return Inertia::render('authorized/admin/Leads', [
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
        $managers = User::role(Role::ROLE_MANAGER)->get();

        return Inertia::render('authorized/admin/AddLead', [
            'managers' => $managers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeadRequest $request)
    {
        //
        $validatedData = $request->validated();
        $leadStatus = LeadStatus::where('name', 'New')->first();
        Lead::create([
            ...$validatedData,
            'utm_source' => @$validatedData['source'],
            'utm_medium' => @$validatedData['medium'],
            'utm_campaign' => @$validatedData['campaign'],
            'lead_status_id' => $leadStatus->id,
        ]);

        return redirect()->route('admins.leads.index')->with('success','Lead data has been created');
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
