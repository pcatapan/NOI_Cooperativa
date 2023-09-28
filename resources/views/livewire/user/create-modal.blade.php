<div class="flex flex-col modal-content bg-white dark:bg-gray-800 w-full p-4 rounded shadow-lg">
	<div class="flex w-full justify-between">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ \Str::ucfirst($title)}}
		</h2>
		<button wire:click="cancel" class="modal-close cursor-pointer top-2 right-2 text-gray-700 dark:text-gray-300 text-xl" id="closeModal">
			<x-icon name="x" class="w-5 h-5"/>
		</button>
	</div>
	<form action="{{ route('user.store') }}" method="POST" x-data="{ passwordStr:'', passwordConfirmStr:'' }">
		@csrf
		<div class="flex gap-3 w-full mt-3 mb-2">
			<div class="w-1/2">
				<x-input right-icon="user" name="name" label="{{ \Str::ucfirst(__('user.name')) }}*" placeholder="{{ __('user.placeholder_name') }}" required/>
			</div>
			<div class="w-1/2">
				<x-input right-icon="user" name="surname" label="{{ \Str::ucfirst(__('user.surname')) }}*" placeholder="{{ __('user.placeholder_surname') }}" required/>
			</div>
		</div>
		<div class="flex gap-3 w-full mt-3 mb-2">
			<div class="w-full">
				<x-input class="pr-28" type="email" name="email" label="{{ \Str::ucfirst(__('user.email')) }}*" placeholder="{{ __('user.placeholder_email') }}" required/>
			</div>
		</div>
		<div class="flex gap-3 w-full mt-3 mb-2">
			<div class="w-1/2">
				<x-inputs.password right-icon="user" name="password" label="{{ \Str::ucfirst(__('user.password')) }}*" placeholder="{{ __('user.placeholder_password') }}" minlength="8" x-model="passwordStr" required/>
			</div>
			<div class="w-1/2">
				<x-inputs.password class="pr-28" name="password_confirmation" label="{{ \Str::ucfirst(__('user.password_confirmation')) }}*" minlength="8" x-model="passwordConfirmStr" placeholder="{{ __('user.placeholder_password') }}" required/>
			</div>
		</div>
		<div class="flex gap-3 w-full mt-3 mb-2">
			<x-select label="{{ \Str::ucfirst(__('user.role')) }}" placeholder="{{ __('user.placeholder_role') }}" class="w-full" name="role" required
				option-label="name"
				option-value="id"
				:options="[
					['name' => 'Admin',  'id' => 'admin'],
					['name' => 'Dipentente', 'id' => 'employee'],
					['name' => 'Responsabile',   'id' => 'responsible'],
			]"/>
		</div>
		<div x-show="passwordStr != passwordConfirmStr" class="text-red-500 text-sm">
			{{ \Str::ucfirst(__('user.password_not_match')) }}
		</div>
		<div class="mt-4 w-full">
			<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full" x-bind:disabled="passwordStr != passwordConfirmStr">
				{{ \Str::ucfirst(__('user.create')) }}
			</button>
		</div>
	</form>
</div>