<?php

namespace App\Http\Controllers\Authorized\TeamLeader;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authorized\BulkAssignRequest;
use App\Services\TeamLeader\LeadService;
use App\Services\TeamLeader\LeadStatusService;
use App\Services\TeamLeader\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $leads = $this->leadService
            ->getPagination(isQueryOnly: true)
            ->with(['users.roles'])
            ->paginate(
                perPage: request()->query->get('length'),
                page: request()->query->get('page')
            );
        $leadStatuses = $this->leadStatusService->getAll();
        $staffs = $this->userService->getDownlines(Auth::user());

        if (request()->header('X-Request-Format') == 'json') return response()->json([...$leads->toArray()]);

        return Inertia::render('authorized/teamLeader/Leads', [
            'leads' => $leads,
            'leadStatuses' => $leadStatuses,
            'staffs' => $staffs,
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
                $request->input('staff_id') !== null
                || $request->input('is_unassign') == 'on'
            ) {
                $uplines = $this
                    ->userService
                    ->getAllUplines($request->user()->id);
                $assigneeIds = $uplines
                    ->pluck('id')
                    ->toArray();
                $assigneeIds[] = $request->user()->id;

                if ($request->input('is_unassign') != 'on')
                $assigneeIds[] = $request->input('staff_id');

                $this->leadService->assignLeads(
                    $request->input('lead_ids'),
                    $assigneeIds
                );
            }

            if ($request->input('lead_status_id') !== null)
                $this->leadService->updateLeadStatuses(
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
