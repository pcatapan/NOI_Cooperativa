<?php

namespace App\Livewire\Worksite;

use Livewire\Component;
use App\Models\Worksite;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;

class AddEdit extends Component
{
	public null|string $description = null;
	public null|string $cod = null;
	public null|string $address = null;
	public null|string $city = null;
	public null|string $province = null;
	public null|string $zip_code = null;
	public null|int $id_responsable = null;
	public null|int $id_company = null;
	public null|int $total_hours = null;
	public null|int $total_hours_extraordinary = null;
	public null|string $notes = null;

	public ?Worksite $worksite = null;
	public $employees = [];

	public function mount($worksite = null)
	{
		if ($worksite) {
			$this->worksite = $worksite;
			$this->description = $worksite->description;
			$this->cod = $worksite->cod;
			$this->address = $worksite->address;
			$this->city = $worksite->city;
			$this->province = $worksite->province;
			$this->zip_code = $worksite->zip_code;
			$this->id_responsable = $worksite->id_responsable;
			$this->id_company = $worksite->id_company;
			$this->total_hours = $worksite->total_hours;
			$this->total_hours_extraordinary = $worksite->total_hours_extraordinary;
			$this->notes = $worksite->notes;
			
			$this->employees = $worksite->employees->pluck('id')->toArray();
		}
	}

	public function render()
	{
		return view('livewire.worksite.add_edit');
	}

	protected function rules()
	{
		if (UserRoleEnum::ADMIN->value !== Auth::user()->role) {
			abort(403);
		}

		$rules = [
			'description' => 'string|max:255|nullable',
			'cod' => 'required|unique:worksites,cod',
			'address' => 'string|max:255|nullable',
			'city' => 'string|max:255|nullable',
			'province' => 'string|max:3|nullable',
			'zip_code' => 'string|max:10|nullable',
			'id_responsable' => 'required|integer|exists:employees,id',
			'id_company' => 'required|integer|exists:companies,id',
			'total_hours' => 'nullable|decimal:2|min:0',
			'total_hours_extraordinary' => 'nullable|decimal:2|min:0',
			'notes' => 'string|max:255|nullable',
		];

		if ($this->worksite) {
			$rules['cod'] = 'required|unique:worksites,cod,'.$this->worksite->id;
		}

		return $rules;
	}

	public function store()
	{
		$this->validate();

		Worksite::updateOrCreate(['id' => $this->worksite->id ?? null], [
			'cod' => $this->cod,
			'description' => $this->description,
			'address' => $this->address,
			'city' => $this->city,
			'province' => $this->province,
			'zip_code' => $this->zip_code,
			'id_responsable' => $this->id_responsable,
			'id_company' => $this->id_company,
			'total_hours' => $this->total_hours,
			'total_hours_extraordinary' => $this->total_hours_extraordinary,
			'notes' => $this->notes,
		]);

		if ($this->employees) {
			$this->worksite->employees()->sync($this->employees);
		}

		return redirect()->route('worksites.index');
	}
}