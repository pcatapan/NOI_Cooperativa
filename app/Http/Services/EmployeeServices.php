<?php

namespace App\Http\Services;

use App\Models\Employee;

class EmployeeServices
{
	static public function create($data): Employee
	{
		dd($data);
		$employee = new Employee();
		$employee->id_user = $data['id_user'];
		$employee->number_serial = $data['number_serial'];
		$employee->fiscal_code = $data['fiscal_code'];
		$employee->inps_number = $data['inps_number'];
		$employee->address = $data['address'];
		$employee->city = $data['city'];
		$employee->province = $data['province'];
		$employee->cap = $data['cap'];
		$employee->phone = $data['phone'];
		$employee->notes = $data['notes'];
		$employee->date_of_hiring = $data['date_of_hiring'];
		$employee->job = $data['job'];
		$employee->active = $data['active'];

		$employee->save();

		return $employee;
	}
}