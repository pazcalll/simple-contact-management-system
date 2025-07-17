<?php

namespace App\Services;

use App\Models\Lead;

class CustomerService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getPagination($page = 1, $length = 15, bool $isQueryOnly = false)
    {
        // Implementation for pagination of customers
        $query = Lead::query()
            ->isCustomer()
            ->with(['leadStatus', 'users'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        if ($isQueryOnly) return $query;

        return $query->paginate(perPage: $length, page: $page);
    }
}
