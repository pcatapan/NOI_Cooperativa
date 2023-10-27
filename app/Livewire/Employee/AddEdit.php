<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AddEdit extends Component
{
	use LivewireAlert;

	/**
	 * @var string Employee
	 */
	public string $name = '';
	public string $surname = '';
	public string $email = '';
	public string $password = '';
	public string $password_confirmation = '';
	public string $fiscal_code = "";
	public $date_birth = "";
	public ?string $number_serial = null;
	public ?string $iban = null;
	public ?int $work_hour_week_by_contract = null;
	public ?int $permission_hour_by_contract = null;
	public ?string $phone = null;
	public ?string $inps_number = null;
	public ?string $address = null;
	public ?string $city = null;
	public ?string $province = null;
	public ?string $zip_code = null;
	public ?string $notes = null;
	public ?string $job = null;
	public $role = null;
	public $company = null;
	public ?string $date_of_hiring = null;
	public $date_of_resignation = "";
	public $active = null;

	/**
	 * @var string Company
	 */
	public ?string $company_name = null;
	public ?string $vat_number = null;
	public ?string $company_address = null;
	public ?string $company_city = null;
	public ?string $company_province = null;
	public ?string $company_zip_code = null;
	public ?string $company_phone = null;
	public ?string $pec = null;

	public $companies;
	public bool $showModalCreateCompany = false;

	public ?Employee $employee = null;

	protected function rules()
	{
		$rules = [
			'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
			'date_birth' => 'nullable',
			'fiscal_code' => 'string|max:16|required|unique:employees',
            'number_serial' => 'string|max:100|nullable',
			'iban' => 'string|max:27|nullable',
			'work_hour_week_by_contract' => 'integer|nullable',
			'permission_hour_by_contract' => 'integer|nullable',
            'phone' => 'string|max:12|nullable',
			'company' => 'nullable|integer|exists:companies,id',
            'inps_number' => 'string|max:50|nullable',
            'address' => 'string|max:255|nullable',
            'city' => 'string|max:255|nullable',
            'province' => 'string|max:3|nullable',
            'zip_code' => 'string|max:6|nullable',
            'notes' => 'string|nullable',
            'date_of_hiring' => 'nullable',
            'job' => 'string|max:255|nullable',
			'role' => 'required|string|in:' . implode(',', UserRoleEnum::getValues()),
            'password' => 'required|string|confirmed|min:6',
		];

		if ($this->employee) {
			$rules['email'] = 'required|string|email|max:255|unique:users,email,' . $this->employee->user->id;
			$rules['fiscal_code'] = 'string|max:16|required|unique:employees,fiscal_code,' . $this->employee->id;
			$rules['password'] = 'nullable|string|confirmed|min:8';
		}

		return $rules;
	}

	public function mount()
	{
		$this->role = UserRoleEnum::EMPLOYEE->value;
		if (request()->employee) {
			$employee = request()->employee;
			$this->employee = $employee;
			$this->name = $employee->user->name;
			$this->surname = $employee->user->surname;
			$this->email = $employee->user->email;
			$this->role = $employee->user->role;
			$this->company = $employee->user->id_company;
			$this->date_birth = $employee->user->date_birth ?? null;
			$this->fiscal_code = $employee->fiscal_code;
			$this->phone = $employee->phone;
			$this->number_serial = $employee->number_serial;
			$this->iban = $employee->iban;
			$this->work_hour_week_by_contract = $employee->work_hour_week_by_contract;
			$this->permission_hour_by_contract = $employee->permission_hour_by_contract;
			$this->inps_number = $employee->inps_number;
			$this->address = $employee->address;
			$this->city = $employee->city;
			$this->province = $employee->province;
			$this->zip_code = $employee->zip_code;
			$this->notes = $employee->notes;
			$this->date_of_hiring = $employee->date_of_hiring ?? null;
			$this->job = $employee->job;
		}

		$this->createArrayCompanies();
	}

	public function render()
	{
		return view('livewire.employee.add_edit');
	}

	public function store()
	{
		if (UserRoleEnum::ADMIN->value !== Auth::user()->role) {
			abort(403);
        }
		
		$this->validate();
		
		if ($this->employee) {
			$this->employee->user->name = $this->name;
			$this->employee->user->surname = $this->surname;
			$this->employee->user->email = $this->email;
			$this->employee->user->role = $this->role;
			$this->employee->user->date_birth = $this->date_birth ?? null;
			$this->employee->user->id_company = $this->company;
			
			$this->employee->user->save();
			
			$this->employee->phone = $this->phone;
			$this->employee->job = $this->job;
			$this->employee->date_of_hiring = $this->date_of_hiring ?? null;
			$this->employee->number_serial = $this->number_serial;
			$this->employee->iban = $this->iban;
			$this->employee->work_hour_week_by_contract = $this->work_hour_week_by_contract;
			$this->employee->permission_hour_by_contract = $this->permission_hour_by_contract;
			$this->employee->fiscal_code = $this->fiscal_code;
			$this->employee->inps_number = $this->inps_number;
			$this->employee->address = $this->address;
			$this->employee->city = $this->city;
			$this->employee->province = $this->province;
			$this->employee->zip_code = $this->zip_code;
			
			$this->employee->save();
		} else {
			$user = new User();
			$user->name = $this->name;
			$user->surname = $this->surname;
			$user->email = $this->email;
			$user->password = Hash::make($this->password);
			$user->role = $this->role;
			$user->date_birth = $this->date_birth ?? null;
			$user->id_company = $this->company;
			
			$user->save();

			$employee = new Employee();
			$employee->id_user = $user->id;
			$employee->phone = $this->phone;
			$employee->job = $this->job;
			$employee->date_of_hiring = $this->date_of_hiring ?? Carbon::now();
			$employee->number_serial = $this->number_serial;
			$employee->iban = $this->iban;
			$employee->work_hour_week_by_contract = $this->work_hour_week_by_contract;
			$employee->permission_hour_by_contract = $this->permission_hour_by_contract;
			$employee->fiscal_code = $this->fiscal_code;
			$employee->inps_number = $this->inps_number;
			$employee->address = $this->address;
			$employee->city = $this->city;
			$employee->province = $this->province;
			$employee->zip_code = $this->zip_code;

			$employee->save();
		}
		
		$this->showSuccessNotification();

		return redirect()->route('employees.index');
	}

	public function ModalCreateCompany()
	{
		$this->showModalCreateCompany = true;
	}

	public function createCompany()
	{
		$this->validate([
			'company_name' => 'required|string|max:255',
			'vat_number' => 'required|string|max:11|unique:companies',
			'pec' => 'nullable|string|max:255',
			'company_address' => 'nullable|string|max:255',
			'company_city' => 'nullable|string|max:255',
			'company_province' => 'nullable|string|max:3',
			'company_zip_code' => 'nullable|string|max:6',
			'company_phone' => 'nullable|string|max:12',
		]);

		$company = new Company();
		$company->name = $this->company_name;
		$company->vat_number = $this->vat_number;
		$company->address = $this->company_address;
		$company->city = $this->company_city;
		$company->province = $this->company_province;
		$company->zip_code = $this->company_zip_code;
		$company->phone = $this->company_phone;
		$company->pec = $this->pec;

		$company->save();

		$this->createArrayCompanies();

		$this->showModalCreateCompany = false;

		$this->company_name = null;
		$this->vat_number = null;
		$this->company_address = null;
		$this->company_city = null;
		$this->company_province = null;
		$this->company_zip_code = null;
		$this->company_phone = null;
		$this->pec = null;

		$this->showSuccessNotification();
	}

	protected function showSuccessNotification()
	{
		session()->flash('message', __('general.save_success_title'));
	}

	protected function createArrayCompanies() : void
	{
		$companiesPluck = Company::all()->pluck('name', 'id');

		$this->companies = collect($companiesPluck)->map(function ($value, $key) {
			return [
				'value' => $key,
				'label' => $value,
			];
		});
	}
}