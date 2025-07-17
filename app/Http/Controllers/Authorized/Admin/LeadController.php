<?php

namespace App\Http\Controllers\Authorized\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\BulkAssignRequest;
use App\Http\Requests\Admins\StoreAssigneeRequest;
use App\Http\Requests\Admins\StoreLeadRequest;
use App\Http\Requests\UpdateLeadStatusRequest;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\Role;
use App\Models\User;
use App\Services\Admin\LeadService;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function __construct(
        private LeadService $leadService = new LeadService(),
        private CustomerService $customerService = new CustomerService(),
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $leads = Lead::latest()->with(['leadStatus', 'users'])->isPrivate(false)->paginate();
        $leadStatuses = LeadStatus::latest()->get();
        $managers = User::role(Role::ROLE_MANAGER)->get();

        if (request()->header('X-Request-Format') == 'json') return response()->json([...$leads->toArray()]);

        return Inertia::render('authorized/admin/Leads', [
            'leads' => $leads,
            'leadStatuses' => $leadStatuses,
            'managers' => $managers,
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
        try {
            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->leadService->createLeadAssignee(
                $this->leadService->createLead($validatedData),
                [
                    @$validatedData['manager_id'],
                    @$validatedData['supervisor_id'],
                    @$validatedData['team_leader_id'],
                    @$validatedData['staff_id'],
                ]
            );
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }

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

    public function storeMassAssignee(StoreAssigneeRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->leadService->createMassLeadAssignee(
                $request->post('lead_ids'),
                [
                    @$request->post('manager_id'),
                    @$request->post('supervisor_id'),
                    @$request->post('team_leader_id'),
                    @$request->post('staff_id'),
                ],
            );
            DB::commit();

            return redirect()->back()->with('success', 'Leads data mass assigned completely');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function bulkAssignLeads(BulkAssignRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->leadService
                ->updateLeadStatuses(
                    @$request->post('lead_ids'),
                    @$request->post('lead_status_id'),
                )
                ->updateMassLeadAssignee(
                    @$request->post('lead_ids'),
                    @$request->post('manager_id'),
                    @$request->post('supervisor_id'),
                    @$request->post('team_leader_id'),
                    @$request->post('staff_id'),
                    @$request->post('is_unassign') == 'on' ? true : false,
                );
            DB::commit();

            return redirect()->back()->with('success', 'Bulk assign success');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function import(Request $request)
    {
        try {
            DB::beginTransaction();
            $importedData = $this->leadService->importLeadsByExcel($request->all()['file']);
            $this->leadService->bulkInsertImportedLeads($importedData);

            DB::commit();
            return redirect()->back()->with('success', 'Data uploaded.');
        } catch (\Throwable $th) {
            // Handle the exception, e.g., log it or return an error response
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function getCustomers()
    {
        $customers = $this
            ->customerService
            ->getPagination(
                request()->get('page', 1),
                request()->get('length', 15)
            );

        return Inertia::render('authorized/admin/Customers', [
            'customers' => $customers,
        ]);
    }
}
