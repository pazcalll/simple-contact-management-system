<?php

namespace App\Http\Controllers\Authorized\TeamLeader;

use App\Http\Controllers\Controller;
use App\Services\TeamLeader\LeadService;
use App\Services\TeamLeader\LeadStatusService;
use App\Services\TeamLeader\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $teamLeaders = $this->userService->getDownlines(Auth::user());

        if (request()->header('X-Request-Format') == 'json') return response()->json([...$leads->toArray()]);

        return Inertia::render('authorized/teamLeader/Leads', [
            'leads' => $leads,
            'leadStatuses' => $leadStatuses,
            'teamLeaders' => $teamLeaders,
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
