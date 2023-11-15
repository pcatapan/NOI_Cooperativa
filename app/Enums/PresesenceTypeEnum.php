<?php

namespace App\Enums;

enum PresesenceTypeEnum : string
{
	case ORDINARY = "ordinary";
	case EXTRAORDINARY   = "extraordinary";


	static public function toArray(): array
	{
		return [
			'ordinary' => 'Ordinaria',
			'extraordinary'   => 'Straordinaria',
		];
	}

	static public function getValues(): array
	{
		return [
			self::ORDINARY->value,
			self::EXTRAORDINARY->value,
		];
	}

	public function labels(): string
	{
		return match ($this) {
			self::ORDINARY => 'Ordinaria',
			self::EXTRAORDINARY => 'Straordinaria',
		};
	}

	static public function select(): array
	{
		return [
			'Ordinaria' => self::ORDINARY->value,
			'Straordinaria' => self::EXTRAORDINARY->value,
		];
	}
}