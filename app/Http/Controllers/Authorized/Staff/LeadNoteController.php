<?php

namespace App\Http\Controllers\Authorized\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authorized\StoreLeadNoteRequest;
use App\Models\Lead;
use App\Services\Staff\LeadNoteService;
use App\Services\Staff\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LeadNoteController extends Controller
{
    public function __construct(
        private LeadNoteService $leadNoteService = new LeadNoteService(),
        private LeadService $leadService = new LeadService(),
    ) {}

    public function index(Lead $lead)
    {
        if (request()->header('X-Request-Format') == 'json') {
            return new JsonResponse(
                $this->leadNoteService->get($lead)
            );
        }
        return redirect()->back();
    }

    public function store(Lead $lead, StoreLeadNoteRequest $request)
    {
        $this->leadNoteService->create(
            $lead,
            Auth::user(),
            $request->get('note'),
        );

        return redirect()->back()->with('success', 'Note has been added');
    }
}
