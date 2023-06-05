<?php

namespace App\Exports\Sheets;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RestsSheet implements FromArray, WithTitle, WithHeadings
{
    protected $date;
    
    public function __construct($date)
    {
        $this->date = $date;
    }

    public function array(): array
    {
        $reports = [];

        $employees = Employee::with('user')->with('team')->get();

        $date = Carbon::parse($this->date);

        foreach ($employees as $employee) {
            $isPresent = false;
            $isAbsent = false;
            $isLate = false;
            $isLeave = false;
            $isRest = false;

            $timesheet = $employee->timesheets()->where('week', ltrim(date("W", strtotime($date)), '0'))->where('day', date('N', strtotime($date)))->first();

            $attendance = Attendance::where('employee_id', $employee->id)->whereDate('created_at', $date)->first();

            if ($timesheet) {
                $time = $timesheet->time;

                if ($time) {
                    $isLeave = $time->is_leave ?? false;
                    $isRest = $time->is_rest ?? false;
                }

                if ($attendance) {
                    $isPresent = !$isLeave && !$isRest ? true : false;

                    if ($isPresent && $time->check_in < $attendance->check_in) {
                        $isLate = true;
                    }
                } else {
                    $isAbsent = true;
                }
            }

            $reports[] = [
                'employee' => $employee,
                'time' => $time ?? null,
                'check_in' => optional($attendance)->check_in,
                'check_out' => optional($attendance)->check_out,
                'break_in' => optional($attendance)->break_in,
                'break_out' => optional($attendance)->break_out,
                'is_present' => $isPresent,
                'is_absent' => $isAbsent,
                'is_late' => $isLate,
                'is_leave' => $isLeave,
                'is_rest' => $isRest,
            ];
        }

        $reports = array_values(array_filter($reports, function ($report) {
            return $report['is_rest'] == true && $report['is_leave'] == false;
        }));

        $data = [];

        foreach ($reports as $report) {
            $data[] = [
                $report['employee']->user->first_name . ' ' . $report['employee']->user->last_name,
                optional($report['employee']->team)->name ?? null,
                $report['employee']->id_number ?? null,
                $report['employee']->registration_number ?? null,
                $report['employee']->payroll_number ?? null,
                optional($report['time'])->name ?? null,
            ];
        }

        return $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Repos';
    }

    public function headings(): array
    {

        return [
            'Employé',
            'Équipe',
            'CIN',
            'Numéro d\'immatriculation',
            'Numéro de paie',
            'Horaire',
        ];
    }
}
