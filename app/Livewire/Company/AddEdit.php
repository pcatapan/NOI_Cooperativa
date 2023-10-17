<?php

namespace App\Livewire\Company;

use Livewire\Component;
use App\Models\Company;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AddEdit extends Component
{
	use LivewireAlert;

	/**
	 * @var string Company
	 */
	public null|string $name = null;
	public null|string $vat_number = null;
	public null|string $address = null;
	public null|string $city = null;
	public null|string $province = null;
	public null|string $zip_code = null;
	public null|string $phone = null;
	public null|string $pec = null;

	public ?Company $company = null;

	public function mount($company = null)
	{
		if ($company) {
			$this->company = $company;
			$this->name = $company->name;
			$this->vat_number = $company->vat_number;
			$this->address = $company->address;
			$this->city = $company->city;
			$this->province = $company->province;
			$this->zip_code = $company->zip_code;
			$this->phone = $company->phone;
			$this->pec = $company->pec;
		}
	}

	public function render()
	{
		return view('livewire.company.add_edit');
	}

	protected function rules()
	{
		if (UserRoleEnum::ADMIN->value !== Auth::user()->role) {
			abort(403);
        }

		$rules = [
			'name' => 'required|string|max:255',
			'vat_number' => 'required|unique:companies,vat_number',
			'address' => 'string|max:255|nullable',
            'city' => 'string|max:255|nullable',
            'province' => 'string|max:3|nullable',
            'zip_code' => 'string|max:6|nullable',
			'phone' => 'string|max:12|nullable',
			'pec' => 'nullable|string|email|max:255|unique:companies,pec'
		];

		if ($this->company) {
			$rules['vat_number'] = 'required|unique:companies,vat_number,'.$this->company->id;
			$rules['pec'] = 'nullable|string|email|max:255|unique:companies,pec,'.$this->company->id;
		}

		return $rules;
	}

	public function store()
	{
		$this->validate();

		Company::updateOrCreate(['id' => $this->company->id ?? null], [
			'name' => $this->name,
			'vat_number' => $this->vat_number,
			'address' => $this->address,
			'city' => $this->city,
			'province' => $this->province,
			'zip_code' => $this->zip_code,
			'phone' => $this->phone,
			'pec' => $this->pec,
		]);

		return redirect()->route('companies.index');
	}
}