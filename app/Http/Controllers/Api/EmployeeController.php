<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Domains\Employee\EmployeeService;
use App\Http\Resources\Employee as EmployeeResource;

class EmployeeController extends Controller
{
    private $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index()
    {
        $employees = $this->employeeService->indexEmployees();
        return ResponseJson::sendResponse('success',EmployeeResource::collection($employees),200);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $employees = $this->employeeService->storeEmployees();
        
    }

    public function show($id)
    {
        $employee = $this->employeeService->showEmployees($id);
        
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
