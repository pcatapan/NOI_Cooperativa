<?php

namespace App\Enums;

enum ContractTypeEnum : string
{
	case UNDETERMINED = "undetermined";
	case DETERMINED   = "fixed_term";
	case APPRENTICE   = "apprentice";
	case PART_TIME   = "part_time";

	static public function toArray(): array
	{
		return [
			'undetermined' => 'Indeterminato',
			'fixed_term'   => 'Determinato',
			'apprentice'   => 'Apprendistato',
			'part_time'   => 'Part time',
		];
	}

	static public function getValues(): array
	{
		return [
			self::UNDETERMINED->value,
			self::DETERMINED->value,
			self::APPRENTICE->value,
			self::PART_TIME->value,
		];
	}

	public function labels(): string
	{
		return match ($this) {
			self::UNDETERMINED => 'Indeterminato',
			self::DETERMINED => 'Determinato',
			self::APPRENTICE => 'Apprendistato',
			self::PART_TIME => 'Part time',
		};
	}
}