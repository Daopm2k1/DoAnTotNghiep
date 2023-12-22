<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Order;

class OrderDetailController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request,$id)
    {
        if(empty($id))
        {
            return redirect()->back()->with('error', 'Không tìm thấy hóa đơn');
        }
        $order_detail = OrderDetail::whereNull('deleted_at')
                            ->where('order_id',$id)
                            ->with(['order','discount'])
                            ->with(['product_detail'=>function($query){
                                $query->withTrashed();
                            }])
                            ->paginate(10);
        $order = Order::whereNull('deleted_at')->where('id',$id)->with('payment')->first();
        // dd($order);
        $data = [
            'order' => $order,
            'rows' => $order_detail,
            'breadcrumbs'        => [
                [
                    'name' => 'Đơn hàng',
                    'url'  => 'admin/order',
                ],
                [
                    'name' => $id,
                    // 'url'  => 'admin/order',
                ],
                [
                    'name' => 'Chi tiết',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isOrder' =>true,
        ];
        return view('admin.order-detail.index',$data);
    }
}
