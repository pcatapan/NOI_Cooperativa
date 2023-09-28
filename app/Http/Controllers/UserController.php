<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Services\EmployeeServices;
use App\Enums\UserRoleEnum;
use App\Http\Requests\UserStoreRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	public function store(UserStoreRequest $request)
	{
        $user = new User();
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role = $request->input('role');

        $user->save();
		
		if ($user->role === UserRoleEnum::EMPLOYEE->value) {
			
			$employee = new Employee();
			$employee->id_user = $user->id;
			$employee->number_serial = $request->input('number_serial');
			$employee->fiscal_code = $request->input('fiscal_code');
			$employee->inps_number = $request->input('inps_number');
			$employee->address = $request->input('address');
			$employee->city = $request->input('city');
			$employee->province = $request->input('province');
			$employee->zip_code = $request->input('zip_code');
			$employee->phone = $request->input('phone');
			$employee->notes = $request->input('notes');
			$employee->date_of_hiring = $request->input('date_of_hiring');
			$employee->job = $request->input('job');
			$employee->active = true;

			$employee->save();
		}

		return redirect()->route('users.index');
	}

	public function create()
	{
		return view('livewire.employee.create');
	}
}
