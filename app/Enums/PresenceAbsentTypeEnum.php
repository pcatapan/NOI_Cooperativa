<?php

namespace App\Enums;

enum PresenceAbsentTypeEnum : string
{
	case HOLIDAY = "holidays"; // Ferie
	case PERMIT = "permit"; // Permessi
	case ILLNESS = "illness"; // Malattia
	case MATERNITY = "maternity"; // Maternità
	case PATERNITY = "paternity"; // Paternità
	case INJURY = "injury"; // Infortunio
	case OTHER = "other"; // Altro


	static public function toArray(): array
	{
		return [
			'holidays' => 'Ferie',
			'permit' => 'Permessi',
			'illness' => 'Malattia',
			'maternity' => 'Maternità',
			'paternity' => 'Paternità',
			'injury' => 'Infortunio',
			'other' => 'Altro',
		];
	}

	static public function getValues(): array
	{
		return [
			self::HOLIDAY->value,
			self::PERMIT->value,
			self::ILLNESS->value,
			self::MATERNITY->value,
			self::PATERNITY->value,
			self::INJURY->value,
			self::OTHER->value,
		];
	}

	public function labels(): string
	{
		return match ($this) {
			self::HOLIDAY => 'Ferie',
			self::PERMIT => 'Permessi',
			self::ILLNESS => 'Malattia',
			self::MATERNITY => 'Maternità',
			self::PATERNITY => 'Paternità',
			self::INJURY => 'Infortunio',
			self::OTHER => 'Altro',
		};
	}

	static public function select(): array
	{
		return [
			['name' => 'Ferie', 'value' => self::HOLIDAY->value],
			['name' => 'Permessi', 'value' => self::PERMIT->value],
			['name' => 'Malattia', 'value' => self::ILLNESS->value],
			['name' => 'Maternità', 'value' => self::MATERNITY->value],
			['name' => 'Paternità', 'value' => self::PATERNITY->value],
			['name' => 'Infortunio', 'value' => self::INJURY->value],
			['name' => 'Altro', 'value' => self::OTHER->value],
		];
	}
}