<?php

namespace App\Livewire\User;

use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;
use App\Models\User;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Hash;

class Create extends ModalComponent
{
	use Actions;

    public string $title = '';
	public array $roles = [];

	public static function modalMaxWidth(): string
    {
        return 'xl';
    }

	public static function closeModalOnEscape(): bool
    {
        return true;
    }

	public static function closeModalOnClickAway(): bool
    {
        return true;
    }

	public function mount()
	{
		$this->roles = UserRoleEnum::toArray();
	}

	public function cancel()
    {
        $this->closeModal();
    }

	public function confirm()
	{
		//$this->validate([
		//	'name' => 'required|string|max:255',
		//	'surname' => 'required|string|max:255',
		//	'email' => 'required|string|email|max:255|unique:users',
		//	'password' => 'required|string|confirmed|min:8',
		//	'role' => 'required|string|in:' . implode(',', UserRoleEnum::getValues()),
		//]);

		User::create([
			'name' => $this->name,
			'surname' => 'test',
			'email' => 'test',
			'password' => Hash::make('password'),
			'role' => 'admin'
		]);

		$this->notification([
			'title' => 'User created successfully!',
			'icon' => 'success',
			'timeout' => 1300,
		]);

		$this->closeModalWithEvents([
			'pg:eventRefresh-default',
		]);
	}

	public function render()
    {
        return view('livewire.user.create');
    }
}