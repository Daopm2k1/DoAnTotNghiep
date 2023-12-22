<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use App\Models\User;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Test implements FromView,WithHeadings,WithColumnWidths,WithStyles
{
    public function view(): View
    {
        $result = DB::table('orders')->join('payments', 'orders.id', '=', 'payments.order_id')
                    ->select('orders.id','orders.fullname','orders.email','orders.address','orders.total','payments.method',DB::raw('DATE_FORMAT(orders.created_at,"%d/%m/%Y") as date'),'orders.note')
                    ->whereNull('orders.deleted_at')
                    ->whereMonth('orders.created_at', $this->month)
                    ->whereYear('orders.created_at', $this->year)
                    ->orderBy('orders.id','asc');
        $user = User::get();
        return view('admin.dashboard.testexcel', [
            'orders' =>$user,
        ]);
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
            2  => ['font' => ['size' => 16,'bold' => true]],
            4    => ['font' => ['bold' => true]],
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
}
