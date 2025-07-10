<?php

namespace App\Services\Admin;

use App\Models\AssignedLead;
use App\Models\Lead;
use App\Models\LeadStatus;
use Illuminate\Http\UploadedFile;

class LeadService extends \App\Services\LeadService
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function createLead(array $payload)
    {
        $leadStatus = LeadStatus::where('name', 'New')->first();
        return Lead::create([
            ...$payload,
            'utm_source' => @$payload['source'],
            'utm_medium' => @$payload['medium'],
            'utm_campaign' => @$payload['campaign'],
            'lead_status_id' => $leadStatus->id,
        ]);
    }

    public function updateLeadStatuses(array $leadIds, int|string $leadStatusId): static
    {
        Lead::whereIn('id', $leadIds)
            ->update([
                'lead_status_id' => $leadStatusId,
            ]);

        return $this;
    }

    public function importLeadsByExcel(mixed $file)
    {
        // Only supports CSV files natively
        $fileOriginalName = is_string($file)
            ? $file
            : (
                method_exists($file, 'getClientOriginalName')
                ? $file->getClientOriginalName()
                : null
            );
        if (!$fileOriginalName) {
            return [];
        }

        $extension = strtolower(pathinfo($fileOriginalName, PATHINFO_EXTENSION));
        if ($extension === 'csv') {
            $rows = [];
            $fileRealPath = $file->getRealPath() ?? $fileOriginalName;
            if (($handle = fopen($fileRealPath, 'r')) !== false) {
                $delimiter = ',';
                $firstLine = fgets($handle);
                if (strpos($firstLine, ';') !== false && substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
                    $delimiter = ';';
                }
                rewind($handle);

                $header = null;
                while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                    // Clean all values
                    $data = array_map(function($v) {
                        $v = preg_replace('/^\x{FEFF}/u', '', $v); // Remove BOM
                        return trim($v);
                    }, $data);
                    if ($header === null) {
                        $header = $data;
                        continue;
                    }
                    // Map header to row
                    $row = array_combine($header, $data);
                    // Clean keys as well (in case BOM is in header)
                    $cleanRow = [];
                    foreach ($row as $k => $v) {
                        $cleanKey = preg_replace('/^\x{FEFF}/u', '', trim($k));
                        $cleanRow[$cleanKey] = $v;
                    }
                    $rows[] = $cleanRow;
                }
                fclose($handle);
            }
            return $rows;
        }

        // For non-CSV files, return empty array (native PHP cannot parse Excel files)
        return [];
    }

    /**
    * @param array<int, array{
    *     lead_email: string,
    *     lead_name: string,
    *     lead_mobile_number: string,
    *     utm_source?: string,
    *     utm_medium?: string,
    *     utm_campaign?: string
    * }> $leads Array of leads with string keys.
     */
    public function bulkInsertImportedLeads(array $leads)
    {
        $insertableLeads = [];
        $now = now();
        $leadStatusId = LeadStatus::where('name', 'New')->first()->id;

        foreach ($leads as $lead) {
            $insertableLeads[] = [
                'email' => $lead['lead_email'] ?? null,
                'name' => $lead['lead_name'] ?? null,
                'mobile_number' => $lead['lead_mobile_number'] ?? null,
                'utm_source' => $lead['utm_source'] ?? null,
                'utm_medium' => $lead['utm_medium'] ?? null,
                'utm_campaign' => $lead['utm_campaign'] ?? null,
                'is_private' => false,
                'lead_status_id' => $leadStatusId ?? 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Lead::insert($insertableLeads);
    }
}
