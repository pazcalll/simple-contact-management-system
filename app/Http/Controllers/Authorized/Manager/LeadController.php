<?php

namespace App\Http\Controllers\Authorized\Manager;

use App\Http\Controllers\Controller;
use App\Models\LeadStatus;
use App\Services\Manager\LeadService;
use App\Services\Manager\UserService;
use Illuminate\Http\Request;
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
        $downlines = $this->userService->getDownlines(auth()->user());

        if (request()->header('X-Request-Format') == 'json') return response()->json([...$leads->toArray()]);

        return Inertia::render('authorized/manager/Leads', [
            'leads' => $leads,
            'leadStatuses' => $leadStatuses,
            'downlines' => $downlines,
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
}
