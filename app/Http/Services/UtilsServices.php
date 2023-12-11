<?php

namespace App\Http\Services;

use App\Models\Worksite;

class UtilsServices
{
	static public function isHoliday(Worksite $worksite, $date) : string|bool
	{
		$holidays = $worksite->holidays;

		$shiftDateFormatMD = $date->format('m-d');
		$shiftDateFormatYMD = $date->format('Y-m-d');

		foreach ($holidays as $holiday) {
			$holidayDateFormatMD = $holiday->date->format('m-d');
			$holidayDateFormatYMD = $holiday->date->format('Y-m-d');

			$isHolidayMatch = $holiday->is_recurring ? $shiftDateFormatMD == $holidayDateFormatMD : $shiftDateFormatYMD == $holidayDateFormatYMD;

			if ($isHolidayMatch) {
				return $holiday->is_national ? 'holiday-national' : 'holiday-local';
			}
		}

		return false;
	}
}