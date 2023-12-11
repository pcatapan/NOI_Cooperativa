<?php

namespace App\Livewire\Holiday;

use App\Enums\PresesenceTypeEnum;
use WireUi\Traits\Actions;
use App\Enums\UserRoleEnum;
use App\Models\Holiday;
use App\Models\Worksite;
use Illuminate\Database\Eloquent\Collection;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AddModal extends ModalComponent
{
    use Actions;

	public Collection $worksites;
	public array $worksitesSelected = [];
	public $date;
	public string $name;
    public bool $isRecurring = false;
    public bool $isNational = false;

    public function mount()
    {
		$this->worksites = Worksite::all();
    }

    public function render()
    {
        return view('livewire.holiday.add');
    }

    public function cancel()
    {
        $this->closeModal();
    }

    public function confirm()
    {
        if ($this->shouldNotAllowDeletion()) {
            abort(403, __('general.403'));
        }

        $this->validate();

		$holiday = Holiday::create([
            'date' => $this->date,
            'name' => $this->name,
            'is_recurring' => $this->isRecurring,
            'is_national' => $this->isNational,
        ]);

		$holiday->worksites()->sync($this->worksitesSelected);

        $this->closeModalWithEvents(['pg:eventRefresh-default']);
        $this->showSuccessNotification();
    }

    protected function rules()
	{
        return [
			'date' => 'required',
			'name' => 'required|string',
            'isRecurring' => 'required|boolean',
            'isNational' => 'required|boolean',
			'worksitesSelected' => 'nullable|array|exists:worksites,id',
        ];
    }

    protected function shouldNotAllowDeletion()
    {
        return Auth::user()->role === UserRoleEnum::EMPLOYEE->value;
    }

    protected function showSuccessNotification()
	{
		$this->notification([
            'title'       => Str::ucfirst(__('general.save_success_title')),
            'icon'        => 'success',
			'timeout'     => 2000,
			'closeButton' => false,
        ]);
	}

	protected function showErrorNotification()
	{
		$this->notification([
			'title'       => Str::ucfirst(__('general.save_error_title')),
			'icon'        => 'error',
			'timeout'     => 2000,
			'closeButton' => false,
		]);
	}
}
