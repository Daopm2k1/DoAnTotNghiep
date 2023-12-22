<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Carbon\Carbon;

class OrderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        $query = Order::whereNull('deleted_at')
                        // ->where('approve',1)
                        ->with('payment')
                        ->whereHas('payment',function($query){
                            // $query->where('status',0);
                        });
        if(!empty($request->search))
        {
            $query->where('id',$request->search);
        }
        if(!empty($request->date))
        {
            $query->where('created_at',$request->date);
        }
        $order = $query->orderBy('created_at','desc')->paginate(10);
        // dd($order);
        $data = [
            'rows' => $order,
            'breadcrumbs'        => [
                [
                    'name' => 'Đơn hàng',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isOrder'=>true,
            'isIndex'=>true,
            'title_page' =>'Đơn hàng',
            'title_delete' =>'Xoá đơn hàng',
            'url'=>route('admin.order.index'),
        ];
        return view('admin.order.index',$data);
    }

    public function update(Request $request, $id) 
    {
        if(empty($id)) 
        {
            return response()->json([
                'error' => true,
                'message' => 'Không có đơn hàng',
                'data' => null,
            ]);
        }

        $payment = Payment::where('order_id',$id)->first();

        if(empty($payment)) 
        {
            return response()->json([
                'error' => true,
                'message' => 'Không có đơn hàng',
                'data' => null,
            ]);
        }

        if($payment->status) {
            $payment->status = false;
        }
        else {
            $payment->status = true; 
        }
        $payment->save();

        return response()->json([
            'error' => false,
            'message' => 'Lấy đơn hàng thành công',
            'data' => $payment,
        ]);
    }

    public function delete(Request $request)
    {
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $order = Order::find($id);
            if(!empty($order))
            {
                $order_detail = OrderDetail::whereNull('deleted_at')->where('order_id',$order->id)->get();
                if(!empty($order_detail))
                {
                    foreach($order_detail as $item)
                    {
                        $item->delete();
                    }
                }

                $payment = Payment::whereNull('deleted_at')->where('order_id',$order->id)->first();
                if(!empty($payment))
                {
                   $payment->delete();
                }

                $order->delete();
            }
        }
        return redirect()->back()->with('success', 'Xóa hóa đơn thành công');
    }

    public function orderOnlineUnpaid(Request $request)
    {
        // dd();
        $query = Order::whereNull('deleted_at')
                        ->with('payment')
                        ->whereHas('payment',function($query){
                            $query->where('status',0)->where('method','online');
                        });
        if(!empty($request->search))
        {
            $query->where('id',$request->search);
        }
        if(!empty($request->date))
        {
            $query->where('created_at',$request->date);
        }
        else
        {
            $query->whereDate('created_at', '<=', Carbon::now()->subDays(7));
        }
        $order = $query->orderBy('created_at','desc')->paginate(10);
        // dd($order);

        $data = [
            'rows' => $order,
            'breadcrumbs' => [
                [
                    'name' => 'Đơn hàng',
                    'url'  => 'admin/order',
                ],
                [
                    'name' => 'Đơn hàng online chưa thanh toán',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isOrder'=>true,
            'title_page' =>'Đơn hàng online chưa thanh toán',
            'title_delete' =>'Xoá đơn hàng',
            'url'=>route('admin.order.index'),
        ];
return view('admin.order.index',$data);
    }
}
