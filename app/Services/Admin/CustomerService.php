<?php

namespace App\Services\Admin;

class CustomerService extends \App\Services\CustomerService
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
        $customersQuery = parent::getPagination($page, $length, true)
            ->isPrivate(false);

        if ($isQueryOnly) return $customersQuery;

        return $customersQuery->paginate(page: $page, perPage: $length);
    }
}
