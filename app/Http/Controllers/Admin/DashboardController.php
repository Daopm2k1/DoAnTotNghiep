<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;
use App\User;
use App\Models\ProductDetail;
use App\Models\Payment;
use Carbon\Carbon;
use App\Models\Color;
use App\Models\Size;
use App\Exports\RevenueExport;
use App\Exports\ReportExcel;
use Excel;
use Illuminate\Support\Facades\Input;
use Redirect;

class DashboardController extends Controller
{
    private $limit_product_most_over = 11;
    
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    //
    public function index(Request $request)
    {
        // dd(Payment::select('order_id','method','status','fee','amount')->get()->toArray());
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        if(!empty($request->sort_month))
        {
            $month = $request->sort_month;
        }

        if(!empty($request->sort_year))
        {
            $year = $request->sort_year;
        }

        //Doanh thu
        $revenue = Order::whereNull('deleted_at')
                        ->whereMonth('created_at', '=',$month)
                        ->whereYear('created_at', '=',$year)
                        ->whereHas('payment',function($query){
                            $query->where('status',1);
                        })
                        ->get()->pluck('total')->sum();

        //Hoá đơn
        $bill = Order::whereNull('deleted_at')
                    ->whereMonth('created_at', '=', $month)
                    ->whereYear('created_at', '=',$year)
                    ->whereHas('payment',function($query){
                        $query->where('status',1);
                    })
                    ->with('payment')
                    ->get()
                    ->count();    
        // dd($bill);
      
        //Đơn hàng mới
        $new_order = Order::whereNull('deleted_at')
                ->where('created_at', '>=', date("Y-m-d"))
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=',$year)
                ->with('payment')
                ->get()
                ->count();
        // dd($new_order);

        //Hàng sắp hết
        $count_product_detail = ProductDetail::whereNull('deleted_at')
                                ->where('quantity','<',$this->limit_product_most_over)
                                ->get()
                                ->count();
        // dd($count_product_detail);

        //Chart
        $year_sort = Carbon::now()->year;

        if(!empty($request->sort))
        {
            $year_sort = $request->sort;
        }
        // dd($year_sort);

        $sum_bill = Order::whereNull('deleted_at')
                    ->whereHas('payment',function($query){
                        $query->where('status',1);
                    })
                    ->with('payment')
                    ->select(DB::raw('sum(total) as total_month'),DB::raw('MONTH(created_at) as month'))
                    ->whereYear('created_at', $year_sort)
                    ->groupby('month')
                    ->get()->pluck('total_month','month')->toArray();
        // dd($sum_bill);
        $data_total = array_fill( 0, 12, 0);

        foreach($data_total as $key=>$value)
        {
            if(isset($sum_bill[$key]))
            {
                $data_total[$key-1] = $sum_bill[$key];
            }
        }
        // dd($data_total);

        //Sản phẩm bán chạy
        $product = DB::table('order_details')
                    ->join('product_details', 'order_details.product_detail_id', '=', 'product_details.id')
                    ->join('products','products.id', '=', 'product_details.product_id')
                    ->select(DB::raw('count(*) as count_product'),DB::raw('sum(order_details.price) as total_price'),'products.id','products.name')
                    ->groupBy('products.id','products.name')
                    ->orderBy('count_product','desc')
                    ->orderBy('total_price','desc')
                    ->limit(10)
                    ->get();
        // dd($product);

        $data = [
            'rows' => $product,
            'revenue'=>$revenue,
            'bill'=> $bill,
            'new_order'=>$new_order,
            'product'=>$count_product_detail,
            'month'=>$month,
            'year'=>$year,
            'breadcrumbs'        => [
                [
                    'name' => 'Bảng điều khiển',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isDashboard' => true,
            'data_total' => json_encode($data_total),
        ];
        return view('admin.dashboard.index',$data);
    }
    public function export(Request $request) 
    {
        $from_date = Carbon::now()->format('Y-m-d');
        $to_date = Carbon::now()->format('Y-m-d');
        if(!empty($request->from_date))
        {
            $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        }
        if(!empty($request->to_date))
        {
            $to_date =  Carbon::parse($request->to_date)->format('Y-m-d');
        }

        return Excel::download(new ReportExcel($from_date,$to_date), 'ThongKeDoanhThu.xlsx');
    }

    public function revenue(Request $request)
    {
        // dd(1);
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        if(!empty($request->month))
        {
            $month = $request->month;
        }
        if(!empty($request->year))
        {
            $year = $request->year;
        }
        // for ($month = 1; $month <= 12; $month++) {
        $query = DB::table('orders')->join('payments', 'orders.id', '=', 'payments.order_id')
                    // ->join('order-details', 'orders.payment_id', '=', 'payments.id')
                    // ->select('orders.id','orders.fullname','orders.email','orders.address','orders.total','orders.note',DB::raw('DATE_FORMAT(orders.created_at,"%d/%m/%Y") as date'),'payments.method','payments.fee','payments.amount')
                    ->select('orders.id','orders.fullname','orders.email','orders.address','orders.total','payments.method',DB::raw('DATE_FORMAT(orders.created_at,"%d/%m/%Y") as date'),'orders.note','payments.payment_gateway')
                    ->whereNull('orders.deleted_at')
                    ->whereMonth('orders.created_at', $month)
                    ->whereYear('orders.created_at', $year);

        if(!empty($request->search))
        {
            $query = $query->where('orders.fullname','LIKE','%'.$request->search.'%')->orwhere('orders.id','LIKE','%'.$request->search.'%');
        }
        $result = $query->orderBy('orders.id','asc')->paginate(10);
        //     array_push ($sheets,$result);
        // }

        // dd($result[0]);
        $data = [
            'rows' => $result,
            'month' => $month,
            'year' => $year,
            'breadcrumbs'        => [
                [
                    'name' => 'Bảng điều khiển',
                    'url'  => 'admin/dashboard',
                ],
                [
                    'name' => 'Chi tiết doanh thu '.$month.'/'.$year,
                ],
            ],
            'isDashboard' => true,
        ];
        return view('admin.dashboard.revenue',$data);
    }

    public function orderMonth(Request $request)
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        if(!empty($request->month))
        {
            $month = $request->month;
        }
        if(!empty($request->year))
        {
            $year = $request->year;
        }
        $query = Order::whereNull('deleted_at')
            ->whereMonth('created_at',$month)
            ->whereYear('created_at',$year)
            ->with('payment')
            ->whereHas('payment',function($query){
                $query->where('status',1);
            });
            
        if(!empty($request->search))
        {
            $query->where('id',$request->search);
        }

        $order = $query->orderBy('created_at','desc')
            ->paginate(10);
        $data = [
            'rows' => $order,
            'month' => $month,
            'year' => $year,
            'breadcrumbs'        => [
                [
                    'name' => 'Bảng điều khiển',
                    'url'  => 'admin/dashboard',
                ],
                [
                    'name' => 'Hoá đơn tháng '.$month.'/'.$year,
                ],
            ],
            'isDashboard' => true,
            'title_page' => 'Hoá đơn tháng '.$month.'/'.$year,
            'title_delete' =>'Xoá hoá đơn',
            'url'=>route('admin.dashboard.order-month'),
        ];
        return view('admin.order.index',$data);
    }
    
    public function newOrder(Request $request)
    {
        // dd(Carbon::now());
        $order = Order::whereNull('deleted_at')
            ->where('created_at', '>=', date("Y-m-d"))
            ->with('payment')
            // ->whereHas('payment',function($query){
            //     $query->where('status',0);
            // })
            ->orderBy('created_at','desc')
            ->paginate(10);
        $data = [
            'rows' => $order,
            'breadcrumbs'        => [
                [
                    'name' => 'Bảng điều khiển',
                    'url'  => 'admin/dashboard',
                ],
                [
                    'name' => 'Đơn hàng mới',
                ],
            ],
            'isDashboard' => true,
            'title_page' =>'Đơn hàng mới',
            'title_delete' =>'Xoá đơn hàng mới',
            'url'=>route('admin.dashboard.new_order'),
        ];
        return view('admin.order.index',$data);
    }

    public function productAlmostOver(Request $request)
    {
        $count_product_detail = ProductDetail::whereNull('deleted_at')
                                ->where('quantity','<',$this->limit_product_most_over)
                                ->with('product')
                                ->paginate(10);
        $size = Size::whereNull('deleted_at')->get();
        $color = Color::whereNull('deleted_at')->get();
        
        $data = [
            'rows' => $count_product_detail,
            'sizes' => $size,
            'colors' => $color,
            'breadcrumbs'        => [
                [
                    'name' => 'Bảng điều khiển',
                    'url'  => 'admin/dashboard',
                ],
                [
                    'name' => 'Hàng sắp hết',
                ],
            ],
            'isDashboard' => true,
        ];
        return view('admin.dashboard.product',$data);
    }

    public function productAlmostOverStore(Request $request)
    {
        // dd($request->all());
        if(empty($request->id_product_detail))
        {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm !');
        }
        $product_detail = ProductDetail::whereNull('deleted_at')
                            ->with('product')
                            ->where('id',$request->id_product_detail)
                            ->first();
        
        $product_detail_check = ProductDetail::whereNull('deleted_at')
                        ->where('id','<>',$request->id_product_detail)
                        ->where('product_id',$product_detail->product_id)
                        ->where('size_id',$request->size)
                        ->where('color_id',$request->color)
                        ->first();
        if(!empty($product_detail_check))
        {
            return redirect()->back()->with('error', 'Chi tiết sản phẩm đã tồn tại');
        }

        if(empty($product_detail))
        {
            return redirect()->back()->with('error', 'Không tìm thấy chi tiết sản phẩm');
        }
        // dd($product_detail);
        $product_detail->size_id = $request->size;
        $product_detail->color_id = $request->color;
        $product_detail->quantity = $request->quantity;

        $size = Size::whereNull('deleted_at')->where('id',$request->size)->first();
        $color = Color::whereNull('deleted_at')->where('id',$request->color)->first();

        $product_detail_name = $product_detail->product->name." - ".$size->name." - ".$color->name;
        $product_detail->name = $product_detail_name;

        $product_detail->save();

        if(!empty($request->image))
        {
            if(File::exists(public_path().$product_detail->image)) {
                File::delete(public_path().$product_detail->image);
            }
            $imageName=$product_detail->id."_".$product_detail->size->name."_".$product_detail->color->name.".".$request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/product'), $imageName);
            $product_detail->image = '/images/product/'.$imageName;
            // dd($product_detail->image);
        }
        $product_detail->save();

        return redirect()->back()->with('success', 'Cập nhật chi tiết sản phẩm thành công');
    }

    public function revenueDetail(Request $request)
    {
        $from_date = Carbon::now()->format('Y-m-d');
        $to_date = Carbon::now()->format('Y-m-d');
        if(!empty($request->from_date))
        {
            $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        }
        if(!empty($request->to_date))
        {
            $to_date =  Carbon::parse($request->to_date)->format('Y-m-d');
        }

        $result = DB::table('orders')->join('payments', 'orders.id', '=', 'payments.order_id')
                    ->select('orders.id','orders.fullname','orders.email','orders.address','orders.total','payments.method',DB::raw('DATE_FORMAT(orders.created_at,"%d/%m/%Y") as date'),'orders.note', 'payments.payment_gateway')
                    ->whereNull('orders.deleted_at')
                    ->whereDate('orders.created_at', '<=', new \DateTime($to_date))
                    ->whereDate('orders.created_at', '>=', new \DateTime($from_date))
                    // ->whereBetween('orders.created_at', [new \DateTime($from_date), new \DateTime($to_date)])
                    ->orderBy('orders.id','asc')
                    ->paginate(10);
        // dd($result);
        $data = [
            'rows' => $result,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'breadcrumbs'        => [
                [
                    'name' => 'Bảng điều khiển',
                    'url'  => 'admin/dashboard',
                ],
                [
                    'name' => 'Thống kê hoá đơn',
                ],
            ],
            'isDashboard' => true,
        ];
        return view('admin.dashboard.revenue-detail',$data);
    }
}