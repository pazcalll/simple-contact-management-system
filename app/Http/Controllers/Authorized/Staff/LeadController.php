<?php

namespace App\Http\Controllers\Authorized\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authorized\MassUpdateLeadStatusIdRequest;
use App\Http\Requests\UpdateLeadStatusRequest;
use App\Services\Staff\LeadService;
use App\Services\Staff\LeadStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadController extends Controller
{
    private LeadService $leadService;
    private LeadStatusService $leadStatusService;
    public function __construct()
    {
        $this->leadStatusService = new LeadStatusService();
        $this->leadService = new LeadService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $leads = $this->leadService->getPagination(request('page'), request('length'));
        $leadStatuses = $this->leadStatusService->getAll();
        return Inertia::render('authorized/staff/Leads', [
            'leads'=> $leads,
            'leadStatuses' => $leadStatuses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('authorized/staff/AddLead');
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

    public function updateStatus(UpdateLeadStatusRequest $request, string $id)
    {
        //
        $this->leadService->updateLeadStatuses(
            leadIds: [$id],
            leadStatusId: $request->input('lead_status_id')
        );

        return redirect()->back()->with([
            'success' => 'Lead status updated successfully.'
        ]);
    }

    public function massUpdateStatus(MassUpdateLeadStatusIdRequest $request)
    {
        //
        $this->leadService->updateLeadStatuses(
            leadIds: $request->input('lead_ids'),
            leadStatusId: $request->input('lead_status_id')
        );

        return redirect()->back()->with([
            'success' => 'Lead statuses updated successfully.'
        ]);
    }
}
