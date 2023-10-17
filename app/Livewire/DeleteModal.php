<?php

namespace App\Livewire;

use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRoleEnum;
use WireUi\Traits\Actions;

class DeleteModal extends ModalComponent
{
    use Actions;

    public $model;
    public $ids = [];

    public $confirmationTitle;
    public $confirmationDescription;

    public function mount(string $confirmationTitle, string $confirmationDescription, $id = null, $class = null, array $ids = [])
    {
        $this->confirmationTitle = $confirmationTitle;
        $this->confirmationDescription = $confirmationDescription;

        if ($class && $id) {
            $this->model = $class::find($id);
        }

        $this->ids = $ids;
    }

    public function render()
    {
        return view('components.modal.delete');
    }

    public function cancel()
    {
        $this->closeModal();
    }

    public function confirm()
    {
        if ($this->model) {
            $this->model->delete();
        } elseif (!empty($this->ids)) {
            $this->model::destroy($this->ids);
        }

        $this->showSuccessNotification();
        $this->closeModalWithEvents(['pg:eventRefresh-default']);
    }

    protected function showSuccessNotification()
    {
        $this->notification([
            'title' => __('general.delete_success_title'),
            'icon' => 'success',
            'timeout'     => 2000,
			'closeButton' => false,
        ]);
    }
}
