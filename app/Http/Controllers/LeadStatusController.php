<?php

namespace App\Http\Controllers;

use App\Exports\LeadStatusesExport;
use Maatwebsite\Excel\Facades\Excel;

class LeadStatusController extends Controller
{
    public function exportCsv()
    {
        return Excel::download(
            new LeadStatusesExport,
            'lead-statuses.xlsx',
        );
    }
}
