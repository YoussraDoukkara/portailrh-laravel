<?php
namespace App\Exports;

use App\Exports\Sheets\PresentsSheet;
use App\Exports\Sheets\AttendancesSheet;
use App\Exports\Sheets\LatesSheet;
use App\Exports\Sheets\LeavesSheet;
use App\Exports\Sheets\RestsSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
    use Exportable;

    protected $date;
    
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new PresentsSheet($this->date),
            new AttendancesSheet($this->date),
            new LatesSheet($this->date),
            new LeavesSheet($this->date),
            new RestsSheet($this->date),
        ];
    
        return $sheets;
    }
}