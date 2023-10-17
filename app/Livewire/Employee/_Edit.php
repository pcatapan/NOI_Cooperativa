<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Employee;
use App\Enums\UserRoleEnum;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Edit extends Component
{
	public string $name = '';
	public string $surname = '';
	public string $email = '';
	public $phone = '';
	public $job = '';
	public $hiring_date = '';
	public $number_serial = '';
	public $fiscal_code = '';
	public $inps_number = '';
	public $address = '';
	public $city = '';
	public $province = '';
	public $zip_code = '';
	public $birth_date = '';
	public $role = '';

	public Employee $employee;

	protected function rules()
	{
		return [
			'name' => 'required|string|max:255',
			'surname' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users,email,' . $this->employee->user->id,
			'role' => 'required|string|in:' . implode(',', UserRoleEnum::getValues()),
			'phone' => 'nullable|string|max:12',
			'job' => 'nullable|string|max:255',
			'hiring_date' => 'nullable|date',
			'birth_date' => 'nullable|date',
			'number_serial' => 'nullable|string|max:100',
			'fiscal_code' => 'nullable|string|max:20|unique:employees,fiscal_code,' . $this->employee->id,
			'inps_number' => 'nullable|string|max:50',
			'address' => 'nullable|string|max:255',
			'city' => 'nullable|string|max:255',
			'province' => 'nullable|string|max:3',
			'zip_code' => 'nullable|string|max:6',
		];
	}

	public function mount(Employee $employee)
	{
		$this->employee = $employee;
		$this->name = $employee->user->name;
		$this->surname = $employee->user->surname;
		$this->email = $employee->user->email;
		$this->role = $employee->user->role;
		$this->phone = $employee->phone;
		$this->job = $employee->job;
		$this->hiring_date = Carbon::parse($employee->date_of_hiring)->format('d/m/Y');
		$this->birth_date = Carbon::parse($employee->user->date_birth)->format('d/m/Y');
		$this->number_serial = $employee->number_serial;
		$this->fiscal_code = $employee->fiscal_code;
		$this->inps_number = $employee->inps_number;
		$this->address = $employee->address;
		$this->city = $employee->city;
		$this->province = $employee->province;
		$this->zip_code = $employee->zip_code;
	}

	public function render()
	{
		return view('livewire.employee.edit');
	}

	public function save()
	{
		if (UserRoleEnum::ADMIN->value !== Auth::user()->role) {
            abort(403);
        }

		$this->validate();

		$this->employee->user->name = $this->name;
		$this->employee->user->surname = $this->surname;
		$this->employee->user->email = $this->email;
		$this->employee->user->role = $this->role;
		$this->employee->user->date_birth = Carbon::parse($this->birth_date);
		$this->employee->user->save();

		$this->employee->phone = $this->phone;
		$this->employee->job = $this->job;
		$this->employee->date_of_hiring = Carbon::parse($this->hiring_date);
		$this->employee->number_serial = $this->number_serial;
		$this->employee->fiscal_code = $this->fiscal_code;
		$this->employee->inps_number = $this->inps_number;
		$this->employee->address = $this->address;
		$this->employee->city = $this->city;
		$this->employee->province = $this->province;
		$this->employee->zip_code = $this->zip_code;
		$this->employee->save();

		return redirect()->route('employees.index');
	}
}
