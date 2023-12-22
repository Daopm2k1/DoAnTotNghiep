<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Order;
use DB;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenuePerMonthSheetExport implements FromQuery, WithTitle,WithHeadings,WithColumnWidths,WithStyles
{
    private $month;
    private $year;

    public function __construct($year, $month)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Họ và tên',
            'Email',
            'Địa chỉ',
            'Tổng tiền',
            'Phương thức',
            'Ngày tạo',
            'Ghi chú',
            // 'Phí vận chuyển',
            // 'Tiền phải trả'
        ];
    }

    public function query()
    {
        $result = DB::table('orders')->join('payments', 'orders.id', '=', 'payments.order_id')
                        // ->join('order-details', 'orders.id', '=', 'payments.order_id')
                        // ->select('orders.id','orders.fullname','orders.email','orders.address','orders.total','orders.note',DB::raw('DATE_FORMAT(orders.created_at,"%d/%m/%Y") as date'),'payments.method','payments.fee','payments.amount')
                        ->select('orders.id','orders.fullname','orders.email','orders.address','orders.total','payments.method',DB::raw('DATE_FORMAT(orders.created_at,"%d/%m/%Y") as date'),'orders.note')
                        ->whereNull('orders.deleted_at')
                        ->whereMonth('orders.created_at', $this->month)
                        ->whereYear('orders.created_at', $this->year)
                        ->orderBy('orders.id','asc');//->get();
        return $result;
    }

    public function title(): string
    {
        return 'Tháng ' . $this->month;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,  
            'C' => 25,
            'D' => 40,            
            'E' => 10,
            'F' => 20,  
            'G' => 15,
            'H' => 15,  
            // 'I' => 15,
            // 'J' => 15,  
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            BeforeExport::class => function(BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Patrick');
            },
            
            // Array callable, refering to a static method.
            BeforeWriting::class => [self::class, 'beforeWriting'],
            
            // Using a class with an __invoke method.
            BeforeSheet::class => new BeforeSheetHandler()
        ];
    }
    
    public static function beforeWriting(BeforeWriting $event) 
    {
        //
    }
}

