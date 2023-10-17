<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Services\EmployeeServices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Enums\UserRoleEnum;
use App\Http\Requests\UserStoreRequest;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
		$user->date_birth = Carbon::parse($request->input('date_birth'));
		$user->id_company = $request->input('company');

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

			return redirect()->route('employees.index');
		}

		return redirect()->route('users.index');
	}

	public function create()
	{
		$companySelect = Company::all()->pluck('name', 'id');

		return view('livewire.employee.create', compact('companySelect'));
	}

	public function __indexResponsible(Request $request)
	{
		return User::join('employees', 'users.id', 'employees.id_user')
			->select('users.*', 'employees.id as id_employee')
			->where('role', UserRoleEnum::RESPONSIBLE->value)
			->when(
				$request->search,
				fn (Builder $query) => $query
					->where('name', 'like', "%{$request->search}%")
					->orWhere('surname', 'like', "%{$request->search}%")
			)
			->when(
				$request->exists('selected'),
				fn (Builder $query) => $query->whereIn('employees.id', $request->input('selected', [])),
				fn (Builder $query) => $query->limit(10)
			)
			->get()
			->map(function ($user) {
				return [
					'id' => $user->id_employee,
					'name' => $user->name . ' ' . $user->surname,
				];
			})
			->filter()
		;
	}
	
	public function __indexEmployee(Request $request) {
		return User::join('employees', 'users.id', 'employees.id_user')
			->select('users.*', 'employees.id as id_employee')
			->where('role', UserRoleEnum::EMPLOYEE->value)
			->when(
				$request->search,
				fn (Builder $query) => $query
					->where('name', 'like', "%{$request->search}%")
					->orWhere('surname', 'like', "%{$request->search}%")
			)
			->when(
				$request->exists('selected'),
				fn (Builder $query) => $query->whereIn('employees.id', $request->input('selected', [])),
				fn (Builder $query) => $query->limit(10)
			)
			->get()
			->map(function ($user) {
				return [
					'id' => $user->id_employee,
					'name' => $user->name . ' ' . $user->surname,
				];
			})
			->filter()
		;
	}
	
	public function __indexEmployeeByResponsable(Request $request, User $responsable)
	{
		return Employee::whereHas('worksites', function (Builder $query) use ($responsable) {
			$query->where('id_responsable', $responsable->employee->id);
			})
			->select('employees.*', 'users.name', 'users.surname')
			->join('users', 'employees.id_user', 'users.id')
			->where('role', UserRoleEnum::EMPLOYEE->value)
			->when(
				$request->search,
				fn (Builder $query) => $query
					->where('name', 'like', "%{$request->search}%")
					->orWhere('surname', 'like', "%{$request->search}%")
			)
			->when(
				$request->exists('selected'),
				fn (Builder $query) => $query->whereIn('employees.id', $request->input('selected', [])),
				fn (Builder $query) => $query->limit(10)
			)
			->get()
			->map(function ($user) {
				return [
					'id' => $user->id,
					'name' => $user->name . ' ' . $user->surname,
				];
			})
			->filter()
		;
	}
}
