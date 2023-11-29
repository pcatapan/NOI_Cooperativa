<?php

use Livewire\Volt\Component;

new class extends Component
{
    public function logout(): void
    {
        auth()->guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('general.dashboard') }}
                    </x-nav-link>
                    @if (auth()->user()->role === \App\Enums\UserRoleEnum::ADMIN->value)  
                        <x-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.index')" wire:navigate>
                            {{ __('navigation.companies') }}
                        </x-nav-link>
                        <x-nav-link :href="route('worksites.index')" :active="request()->routeIs('worksites.index')" wire:navigate>
                            {{ __('navigation.worksites') }}
                        </x-nav-link>
                        <x-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.index')" wire:navigate>
                            {{ __('navigation.employees') }}
                        </x-nav-link>
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" wire:navigate>
                            {{ __('navigation.users') }}
                        </x-nav-link>
                    @endif

                    @if (auth()->user()->role === \App\Enums\UserRoleEnum::RESPONSIBLE->value)
                        <x-nav-link :href="route('shifts.not_validated')" :active="request()->routeIs('shifts.not_validated')" wire:navigate>
                           {{ __('navigation.shifts_not_validated') }}
                        </x-nav-link>
                        <x-nav-link :href="route('shifts.future')" :active="request()->routeIs('shifts.future')" wire:navigate>
                           {{ __('navigation.shifts_future') }}
                        </x-nav-link>
                        <x-nav-link :href="route('presences.index')" :active="request()->routeIs('presences.index')" wire:navigate>
                           {{ __('navigation.presence') }}
                        </x-nav-link>
                        <x-nav-link :href="route('absence.index')" :active="request()->routeIs('absence.index')" wire:navigate>
                           {{ __('navigation.absence') }}
                        </x-nav-link>
                    @endif

                    <x-dropdown>
                        <x-slot name="trigger" class="flex">
                            <p class="flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                {{ __('navigation.reports') }}<x-icon name="chevron-down" class="w-4 h-4 ml-1" />
                            </p>
                        </x-slot>
                    
                        @if (auth()->user()->role === \App\Enums\UserRoleEnum::ADMIN->value)  
                            <x-dropdown-link :href="route('reports.employee')" wire:navigate>
                                {{ __('navigation.report_presences') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('reports.company')" wire:navigate>
                                {{ __('navigation.report_companies') }}
                            </x-dropdown-link>
                        @endif
                        @if (auth()->user()->role !== \App\Enums\UserRoleEnum::EMPLOYEE->value)
                            <x-dropdown-link :href="route('reports.worksite')" wire:navigate>
                            {{ __('navigation.report_worksites') }}
                            </x-dropdown-link>
                        @endif
                    </x-dropdown>
                </div>
            </div>

            <div class="flex gap-3">
                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-dropdown-user align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div x-data="{ name: '{{ auth()->user()->name }}' }" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-left">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown-user>
                </div>

                {{--Dark mode toggle--}}
                <div class="flex sm:items-center justify-center flex-col">
                    <button type="button" x-bind:class="darkMode ? 'bg-indigo-500' : 'bg-gray-200'"
                        x-on:click="darkMode = !darkMode"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        role="switch" aria-checked="false">
                        <span class="sr-only">Dark mode toggle</span>
                        <span x-bind:class="darkMode ? 'translate-x-5 bg-gray-700' : 'translate-x-0 bg-white'"
                            class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full shadow ring-0 transition duration-200 ease-in-out">
                            <span
                                x-bind:class="darkMode ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200'"
                                class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity"
                                aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                </svg>
                            </span>
                            <span
                                x-bind:class="darkMode ?  'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100'"
                                class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity"
                                aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    </button>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('general.dashboard') }}
            </x-responsive-nav-link>
            @if (auth()->user()->role === \App\Enums\UserRoleEnum::ADMIN->value)
                <x-responsive-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.index')" wire:navigate>
                    {{ __('navigation.companies') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('worksites.index')" :active="request()->routeIs('worksites.index')" wire:navigate>
                    {{ __('navigation.worksites') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.index')" wire:navigate>
                    {{ __('navigation.employees') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" wire:navigate>
                    {{ __('navigation.users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.employee')" :active="request()->routeIs('reports.employee')" wire:navigate>
                    {{ __('navigation.report_presences') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.company')" :active="request()->routeIs('reports.company')" wire:navigate>
                    {{ __('navigation.report_companies') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.worksite')" :active="request()->routeIs('reports.worksite')" wire:navigate>
                    {{ __('navigation.report_worksites') }}
                </x-responsive-nav-link>
            @endif

            @if (auth()->user()->role === \App\Enums\UserRoleEnum::RESPONSIBLE->value)
                {{--<x-responsive-nav-link :href="route('shifts.index')" :active="request()->routeIs('shifts.index')" wire:navigate>
                    {{ __('navigation.shifts') }}
                </x-responsive-nav-link>--}}
                <x-nav-link :href="route('presences.index')" :active="request()->routeIs('presences.index')" wire:navigate>
                    {{ __('navigation.presence') }}
                </x-nav-link>
                <x-nav-link :href="route('absence.index')" :active="request()->routeIs('absence.index')" wire:navigate>
                           {{ __('navigation.absence') }}
                        </x-nav-link>
                <x-responsive-nav-link :href="route('reports.worksite')" :active="request()->routeIs('reports.worksite')" wire:navigate>
                    {{ __('navigation.report_worksites') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200" x-data="{ name: '{{ auth()->user()->name }}' }" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-left">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
