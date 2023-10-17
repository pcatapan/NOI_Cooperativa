<?php

namespace App\Livewire;

use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class ShowContentModal extends ModalComponent
{
    use Actions;

	public string $title;
	public string $content;

    public function mount(string $title, string $content)
	{
		$this->title = $title;
		$this->content = $content;
	}

    public function render()
    {
        return view('components.modal.content');
    }

    public function cancel()
    {
        $this->closeModal();
    }
}
