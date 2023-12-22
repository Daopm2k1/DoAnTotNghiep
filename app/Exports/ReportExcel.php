<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use App\Models\User;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportExcel implements FromView,WithHeadings,WithColumnWidths,WithStyles,WithTitle,WithEvents
{
    private $from_date;
    private $to_date;

    public function __construct($from_date,$to_date)
    {
        
        $this->from_date =  Carbon::parse($from_date)->format('Y-m-d');
     
        $this->to_date =  Carbon::parse($to_date)->format('Y-m-d');
        // dd($this->from_date,$this->to_date);
        
    }

    public function view(): View
    {
        $result = DB::table('orders')->join('payments', 'orders.id', '=', 'payments.order_id')
                    ->select('orders.id','orders.fullname','orders.email','orders.address','orders.total','payments.method',DB::raw('DATE_FORMAT(orders.created_at,"%d/%m/%Y") as date'),'orders.note','payments.payment_gateway')
                    ->whereNull('orders.deleted_at')
                    ->whereDate('orders.created_at', '<=', new \DateTime($this->to_date))
                    ->whereDate('orders.created_at', '>=', new \DateTime($this->from_date))
                    // ->whereBetween('orders.created_at', [new \DateTime($this->from_date), new \DateTime($this->to_date)])
                    ->orderBy('orders.id','asc')
                    ->get();
// dd($result);
        // $user = User::get();
        return view('admin.dashboard.reportexcel', [
            'orders' =>$result,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date
        ]);
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 10,  
            'C' => 15,
            'D' => 15,            
            'E' => 20,
            'F' => 13,  
            'G' => 12,
            'H' => 15, 
            'I' => 15,
            // 'J' => 15,  
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // 2  => ['font' => ['size' => 16,'bold' => true]],
            // 4    => ['font' => ['bold' => true]],
        ];
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
    public function title(): string
    {
        return (Carbon::parse($this->from_date)->format('d.m.Y')).' - '.(Carbon::parse($this->to_date)->format('d.m.Y'));
    }

    public function registerEvents(): array
    {

        $border = array(
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        );

        return [

            AfterSheet::class => function(AfterSheet $event) use ($border){
                $highestnRow = $event->sheet->getHighestRow();

                $range = 'A8:I'.($highestnRow - 2);

                $event->sheet->getStyle($range)->applyFromArray($border)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
            },
        ];
    }
}
