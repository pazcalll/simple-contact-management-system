<?php

namespace App\Http\Controllers\Authorized\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authorized\MassUpdateLeadStatusIdRequest;
use App\Http\Requests\Authorized\StoreLeadRequest;
use App\Http\Requests\UpdateLeadStatusRequest;
use App\Services\CustomerService;
use App\Services\LeadNoteService;
use App\Services\Staff\LeadService;
use App\Services\Staff\LeadStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function __construct(
        private LeadService $leadService = new LeadService(),
        private LeadStatusService $leadStatusService = new LeadStatusService(),
        private LeadNoteService $leadNoteService = new LeadNoteService(),
        private CustomerService $customerService = new CustomerService(),
    ) {}

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
        return Inertia::render('authorized/staff/AddLead', [
            'lead' => session()->get('lead', null),
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
            $lead = $this->leadService->create($validatedData);

            return redirect()->back()->with([
                'lead' => $lead,
                'success' => 'Lead created successfully.',
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([
                'error' => $th->getMessage()
            ]);
        }
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
        try {
            $this->leadService->updateLeadStatuses(
                leadIds: [$id],
                leadStatusId: $request->input('lead_status_id')
            );

            $lead = $this->leadService->getOneById($id);

            $this->leadNoteService->create(
                lead: $lead,
                user: auth()->user(),
                note: 'Status updated to ' . $lead->leadStatus->name
            );

            return redirect()->back()->with([
                'success' => 'Lead status updated successfully.'
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([
                'error' => $th->getMessage()
            ]);
        }

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

    public function getCustomers()
    {
        $customers = $this->customerService
            ->getPagination(
                page: request('page'),
                length: request('length'),
            );

        if (request()->header('X-Request-Format') == 'json') {
            return new JsonResponse($customers);
        }

        return Inertia::render('authorized/staff/Customers', [
            'customers' => $customers,
        ]);
    }
}
