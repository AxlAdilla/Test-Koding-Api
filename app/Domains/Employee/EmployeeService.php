<?php

namespace App\Domains\Employee;

use Illuminate\Support\Arr;

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

    private function uploadFile($image){
        return $image->store('','public');
    }

    private function mapStoreEmployeePayload($payload,$profile_image=''){
        return [
            'name'=>Arr::get($payload,'name'),
            'salary'=>Arr::get($payload,'salary'),
            'age'=>Arr::get($payload,'age'),
            'profile_image'=>$profile_image,
        ];
    }
}
