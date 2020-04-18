<?php

namespace App\Domains\Employee;

class EmployeeService 
{
    private $employeeRepo;

    public function __construct(EmployeeRepository $employeeRepo){
        $this->employeeRepo = $employeeRepo;
    }

    public function indexEmployees()
    {
        return $this->employeeRepo->index();
    }

    public function showEmployee($id)
    {
        return $this->employeeRepo->show($id);
    }
}
