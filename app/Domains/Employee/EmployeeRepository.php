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

    public function update($employee,$attributes)
    {
        $employee->update($attributes);
        return $employee->fresh();
    }

    public function destroy($employee)
    {
        $employee->delete();
        return $employee;
    }
}
