<?php

namespace App\Http\Controllers\Authorized\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authorized\BulkAssignRequest;
use App\Services\Staff\LeadStatusService;
use App\Services\Supervisor\LeadService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function __construct(
        private LeadService $leadService = new LeadService(),
        private LeadStatusService $leadStatusService = new LeadStatusService(),
        private UserService $userService = new UserService(),
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $leads = $this->leadService->getPagination();
        $leadStatuses = $this->leadStatusService->getAll();

        return Inertia::render('authorized/supervisor/Leads', [
            'leads' => $leads,
            'leadStatuses' => $leadStatuses,
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

    public function bulkAssignLeads(BulkAssignRequest $request)
    {
        try {
            DB::beginTransaction();

            if (
                $request->input('team_leader_id') !== null
                || $request->input('is_unassign') == 'on'
            ) {
                $nonRemovableUserIds = $this->userService
                    ->getAllUplines($request->user()->id)
                    ->pluck('id')
                    ->toArray();
                $nonRemovableUserIds[] = $request->user()->id;

                $this->leadService->updateMassLeadAssignee(
                    leadIds: $request->input('lead_ids'),
                    managerId: $request->user()->upline()?->id,
                    supervisorId: $request->user()->id,
                    teamLeaderId: $request->input('team_leader_id'),
                    isUnassign: $request->input('is_unassign') === 'on',
                );
            }

            if ($request->input('lead_status_id') !== null) $this->leadService->updateLeadStatuses(
                $request->input('lead_ids'),
                $request->input('lead_status_id'),
            );

            DB::commit();
            return redirect()->back()->with([
                'success' => 'Leads have been successfully assigned.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with([
                'error' => $th->getMessage()
            ]);
        }
    }
}
