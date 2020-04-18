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

    public function store($attributes)
    {
        $employee = Employee::create($attributes);
        return $employee;
    }
}
