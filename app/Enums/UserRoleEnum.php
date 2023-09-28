<?php

namespace App\Enums;

enum UserRoleEnum : string
{
	case ADMIN       = "admin";
	case EMPLOYEE    = "employee";
	case RESPONSIBLE = "responsible";

	static public function toArray(): array
	{
		return [
			'admin'       => 'Admin',
			'employee'    => 'Dipendente',
			'responsible' => 'Responsabile',
		];
	}

	static public function getValues(): array
	{
		return [
			self::ADMIN->value,
			self::EMPLOYEE->value,
			self::RESPONSIBLE->value,
		];
	}

	public function labels(): string
	{
		return match ($this) {
			self::ADMIN => 'Admin',
			self::EMPLOYEE => 'Dipendente',
			self::RESPONSIBLE => 'Responsabile',
		};
	}
}