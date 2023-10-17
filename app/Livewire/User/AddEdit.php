<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Hash;

class AddEdit extends Component
{
	use LivewireAlert;

	public string $name = '';
	public string $surname = '';
	public string $email = '';
	public string $password = '';
	public string $password_confirmation = '';
	public $role = UserRoleEnum::ADMIN->value;

	public ?User $user = null;

	protected function rules()
	{
		$rules = [
			'name' => 'required|string|max:255',
			'surname' => 'required|string|max:255',
			'password' => 'required|string|confirmed|min:8',
			'email' => 'required|string|email|max:255|unique:users',
		];

		if ($this->user) {
			$rules['email'] = 'required|string|email|max:255|unique:users,email,' . $this->user->id;
			$rules['password'] = 'nullable|string|confirmed|min:8';
		}

		return $rules;
	}

	public function mount()
	{
		if (request()->user) {
			$user = request()->user;
			$this->user = $user;
			$this->name = $user->name;
			$this->surname = $user->surname;
			$this->email = $user->email;
		}
	}

	public function render()
	{
		return view('livewire.user.add_edit');
	}

	public function store()
	{
		if (UserRoleEnum::ADMIN->value !== Auth::user()->role) {
            abort(403);
        }

		$this->validate();
		
		if ($this->user) {
			$this->user->name = $this->name;
			$this->user->surname = $this->surname;
			$this->user->email = $this->email;
			$this->user->role = $this->role;

			$this->user->save();
		} else {
			$user = new User();
			$user->name = $this->name;
			$user->surname = $this->surname;
			$user->email = $this->email;
			$user->password = Hash::make($this->password);
			$user->role = $this->role;

			$user->save();
		}

		$this->showSuccessNotification();

		return redirect()->route('users.index');
	}

	protected function showSuccessNotification()
	{
		session()->flash('message', __('general.save_success_title'));
	}
}
