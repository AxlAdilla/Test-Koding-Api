<?php

namespace App\Domains\Employee;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

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

    public function storeEmployee($payload,$image)
    {
        if($image){
            $profile_image = $this->uploadFile($image);
            $attributes = $this->mapStoreEmployeePayload($payload,$profile_image);
        }else{
            $attributes = $this->mapStoreEmployeePayload($payload);
        }
        return $this->employeeRepo->store($attributes);
    }

    public function updateEmployee($id,$payload,$image)
    {
        $employee = $this->employeeRepo->show($id);
        if($image){
            $this->deleteFile($employee->profile_image);
            $profile_image = $this->uploadFile($image);
            $attributes = $this->mapUpdateEmployeePayload($payload,$profile_image);
        }else{
            $attributes = $this->mapUpdateEmployeePayload($payload,$employee->profile_image);
        }
        return $this->employeeRepo->update($employee,$attributes);
    }

    private function uploadFile($image){
        return $image->store('','public');
    }

    private function deleteFile($path_image){
        Storage::disk('public')->delete($path_image);
    }

    private function mapStoreEmployeePayload($payload,$profile_image=''){
        return [
            'name'=>Arr::get($payload,'name'),
            'salary'=>Arr::get($payload,'salary'),
            'age'=>Arr::get($payload,'age'),
            'profile_image'=>$profile_image,
        ];
    }

    private function mapUpdateEmployeePayload($payload,$profile_image=''){
        return [
            'name'=>Arr::get($payload,'name'),
            'salary'=>Arr::get($payload,'salary'),
            'age'=>Arr::get($payload,'age'),
            'profile_image'=>$profile_image,
        ];
    }
}
