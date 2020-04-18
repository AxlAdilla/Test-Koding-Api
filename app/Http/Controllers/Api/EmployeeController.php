<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Domains\Employee\EmployeeService;
use App\Http\Resources\Employee as EmployeeResource;
use App\Http\Requests\Api\EmployeeController\EmployeeStore;

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

    public function store(EmployeeStore $request)
    {
        $employee = $this->employeeService->storeEmployee($request->all(),$request->file('profile_image'));
        return ResponseJson::sendResponse('success',$employee,201);
    }

    public function show($id)
    {
        $employee = $this->employeeService->showEmployee($id);
        return ResponseJson::sendResponse('success',new EmployeeResource($employee),200);
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
