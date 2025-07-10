<?php

namespace App\Http\Controllers\Authorized\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authorized\StoreLeadNoteRequest;
use App\Models\Lead;
use App\Services\Manager\LeadNoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadNoteController extends Controller
{
    public function __construct(
        private LeadNoteService $leadNoteService = new LeadNoteService(),
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Lead $lead)
    {
        if (request()->header('X-Request-Format') == 'json') {
            return new JsonResponse(
                $this->leadNoteService->get($lead),
                200,
                ['Content-Type' => 'application/json']
            );
        }

        return redirect()->back();
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
    public function store(Lead $lead, StoreLeadNoteRequest $request)
    {
        //
        $this->leadNoteService->create(
            $lead,
            $request->user(),
            $request->note
        );

        return redirect()
            ->route('managers.leads.index')
            ->with('success', 'Lead note created successfully.');
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
