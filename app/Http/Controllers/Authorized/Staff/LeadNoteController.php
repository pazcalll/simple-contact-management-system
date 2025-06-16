<?php

namespace App\Http\Controllers\Authorized\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authorized\StoreLeadNoteRequest;
use App\Services\Staff\LeadNoteService;
use App\Services\Staff\LeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LeadNoteController extends Controller
{
    public function __construct(
        private LeadNoteService $leadNoteService = new LeadNoteService(),
        private LeadService $leadService = new LeadService(),
    ) {}

    public function store(StoreLeadNoteRequest $request)
    {
        $this->leadNoteService->create(
            $this->leadService->getOneById($request->get('lead_id')),
            Auth::user(),
            $request->get('note'),
        );

        return redirect()->back()->with('success', 'Note has been added');
    }
}
