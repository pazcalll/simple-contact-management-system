<?php

namespace App\Exports;

use App\Models\LeadStatus;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeadStatusesExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Group',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        //
        $leadStatuses = LeadStatus::select(['id', 'name', 'group'])
            ->where('is_active', true)
            ->get();

        return $leadStatuses;
    }
}
