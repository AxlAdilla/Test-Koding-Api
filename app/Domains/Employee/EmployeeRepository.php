<?php

namespace App\Domains\Employee;

use App\Models\Employee;

class EmployeeRepository 
{
    public function index()
    {
        return Employee::all();
    }

    public function show($id)
    {
        return Employee::findOrFail($id);
    }
}
