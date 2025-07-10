<?php

namespace App\Http\Controllers\Authorized\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authorized\Managers\BulkAssignRequest;
use App\Models\LeadStatus;
use App\Services\Manager\LeadService;
use App\Services\Manager\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function __construct(
        public LeadService $leadService = new LeadService(),
        public UserService $userService = new UserService(),
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $leads = $this->leadService->getPagination(request('page'), request('length'));
        $leadStatuses = LeadStatus::latest()->get();
        $supervisor = $this->userService->getDownlines(auth()->user());

        if (request()->header('X-Request-Format') == 'json') return response()->json([...$leads->toArray()]);

        return Inertia::render('authorized/manager/Leads', [
            'leads' => $leads,
            'leadStatuses' => $leadStatuses,
            'supervisor' => $supervisor,
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

    public function updateStatus(Request $request, string $id)
    {
        try {
            $request->validate([
                'lead_status_id' => 'required|exists:lead_statuses,id'
            ]);

            $this->leadService->updateLeadStatuses([$id], $request->input('lead_status_id'));

            return redirect()->back()->with([
                'success' => 'Lead status updated successfully.'
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([
                'error' => $th->getMessage()
            ]);
        }
    }

    public function bulkAssignLeads(BulkAssignRequest $request)
    {
        try {
            DB::beginTransaction();

            $this->leadService->updateMassLeadAssignee(
                $request->input('lead_ids'),
                $request->user()->id,
                $request->input('supervisor_id'),
            );

            $this->leadService->updateLeadStatuses(
                $request->input('lead_ids'),
                $request->input('lead_status_id'),
            );

            DB::commit();
            return redirect()->back()->with([
                'success' => 'Leads have been successfully assigned.'
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([
                'error' => $th->getMessage()
            ]);
        }
    }
}
