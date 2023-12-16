<?php

namespace App\Http\Services;

use App\Models\Worksite;
use App\Models\Company;
use App\Models\Presence;
use App\Models\Employee;

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

			if ($isHolidayMatch || $date->isWeekend()) {
				return $holiday->is_national || $date->isWeekend() ? 'holiday-national' : 'holiday-local';
			}
		}

		return false;
	}

	static public function getDetailsReportCompany(Company $company, $worksite = null, $from_date = null, $to_date = null)
	{
		return Presence::where('absent', false)
			->with('worksite')
			->whereHas('worksite', function ($query) use ($company) {
				$query->where('id_company', $company->id);
			})
			->when($from_date, fn($query) => $query->whereDate('date', '>=', $from_date))
			->when($to_date, fn($query) => $query->whereDate('date', '<=', $to_date))
			->when($worksite, fn($query) => $query->where('id_worksite', $worksite))
		;
	}

	static public function getDetailsReportEmployee(Employee $employee, $worksite = null, $from_date = null, $to_date = null, $company = null)
	{
		return $employee->presences()
			->where('absent', false)
			->with('worksite')
			->when($from_date, fn($query) => $query->whereDate('date', '>=', $from_date))
			->when($to_date, fn($query) => $query->whereDate('date', '<=', $to_date))
			->when($company, fn($query) => $query->whereHas('worksite', fn($q) => $q->where('id_company', $company)))
			->when($worksite, fn($query) => $query->where('id_worksite', $worksite))
		;
	}

	static public function getDetailsReportWorksite(Worksite $worksite, $from_date = null, $to_date = null, $company = null)
	{
		return Presence::query()
			->where('absent', false)
			->where('id_worksite', $worksite->id)
			->when($company, function ($q) use ($company) {
				$q->whereHas('worksite', function ($query) use ($company) {
					$query->where('id_company', $company);
				});
			})
			->when($from_date, fn($q) => $q->whereDate('date', '>=', $from_date))
			->when($to_date, fn($q) => $q->whereDate('date', '<=', $to_date))
		;
	}
}