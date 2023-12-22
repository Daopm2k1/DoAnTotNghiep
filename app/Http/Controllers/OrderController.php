<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\ProductDetail;
use App\Models\PostCate;
use App\Models\InforContact;
use Auth;
use Illuminate\Support\Str;
use Session;

class OrderController extends Controller
{
    //
    public function index(Request $request)
    {
        // dd(Session::all());
        if (!Auth::check()) {
            return redirect('/login');
        }
        $categories = Category::whereNull('deleted_at')->get();
        $category_post = PostCate::where('status', 1)->get();
        $infor_contact = InforContact::all();
        $order_unpaid = Order::where('email', Auth::user()->email)
            ->whereHas('payment', function ($query) {
                $query->where('status', 0);
            })
            ->with('order_detail')
            ->with('payment')
            ->orderByDesc('created_at')
            ->get();
        // dd($order_unpaid);
        $order_paid = Order::where('email', Auth::user()->email)
            ->whereHas('payment', function ($query) {
                $query->where('status', 1);
            })
            ->with(['payment', 'order_detail'])
            ->orderByDesc('created_at')
            ->get();
        // dd(count($order_paid));
        $data = [
            'categories' => $categories,
            'order_unpaid' => $order_unpaid,
            'order_paid' => $order_paid,
            'category_post' => $category_post,
            'infor_contact' => $infor_contact,
            'title' => 'Đơn hàng',
            'breadcrumbs' => [
                [
                    'name' => 'Trang chủ',
                    'url'  => '/',
                ],
                [
                    'name' => 'Đơn hàng',

                ]
            ]
        ];
        return view('user.order.index', $data);
    }

    public function orderDetail(Request $request, $id)
    {
        if (empty($id)) {
            abort(404);
        }
        $category_post = PostCate::where('status', 1)->get();

        $order = Order::where('id', $id)
            ->whereNull('deleted_at')
            ->with(['payment' => function ($query) {
                $query->with('ship');
            }])
            ->with(['order_detail' => function ($query) {
                $query->with(['product_detail', 'discount']);
            }])
            ->first();

        // dd($order->payment);
        if (empty($order)) {
            abort(404);
        }
        $infor_contact = InforContact::all();
        $categories = Category::whereNull('deleted_at')->get();
        $data = [
            'categories' => $categories,
            'order' => $order,
            'infor_contact' => $infor_contact,
            'category_post' => $category_post,
            'breadcrumbs' => [
                [
                    'name' => 'Trang chủ',
                    'url'  => '/',
                ],
                [
                    'name' => 'Đơn hàng',
                    'url'  => '/order',
                ],
                [
                    'name' => $id,
                ]
            ]
        ];
        return view('user.checkout.success', $data);
    }

    public function destroyOrder(Request $request)
    {
        $id = $request->id;
        if (empty($id)) {
            abort(404);
        }

        $order = Order::where('id', $id)
            ->whereNull('deleted_at')
            ->with(['payment' => function ($query) {
                $query->with('ship');
            }])
            ->with(['order_detail' => function ($query) {
                $query->with(['product_detail', 'discount']);
            }])
            ->first();

        if (empty($order)) {
            abort(404);
        }

        if ($order->payment->method == 'online') {
            $payment = Payment::where('order_id', $order->id)->first();
            $payment->delete();

            $order_detail = OrderDetail::where('order_id', $order->id)->get();
            if (count($order_detail) > 0) {
                foreach ($order_detail as $item) {
                    $product_detail =  ProductDetail::where('id', $item->product_detail_id)->first();

                    $product_detail->quantity = $product_detail->quantity + $item->quantity;
                    $product_detail->save();

                    $item->delete();
                }
            }

            $order->delete();

            return redirect('/order')->with('success', 'Huỷ đơn hàng thành công');
        }
        return redirect('/order')->with('error', 'Lỗi khi huỷ đơn hàng');
    }

    public function payBack(Request $request)
    {
        $id = $request->id;
        if(empty($id))
        {
            abort(404);
        }

        $order = Order::find($id);
        
        if(empty($order))
        {
            abort(404);
        }
        Session::put('order',$order);

        $payment = Payment::where('order_id',$id)->first();

        if(empty($payment))
        {
            abort(404);
        }

        if ($payment->payment_gateway == 'delivery') {
            $rule = [
                'fullname' => 'required',
                'email' => 'required|email',
                'phone' => 'required|numeric|digits_between:10,11',
                'province' => 'required',
                'district' => 'required',
                'ward' => 'required',
                'address' => 'required',
            ];
            $messages = [
                'required' => 'Nhập :attribute',
                'email' => ':attribute không hợp lệ',
                'numeric' => ':attribute phải là số',
                'phone.digits_between' => 'Số điện thoại phải là 10 hoặc 11 số',
                'email.email' => 'Email không hợp lệ',
                'province.required' => 'Chọn tỉnh/thành phố',
                'district.required' => 'Chọn quận/huyện',
                'ward.required' => 'Chọn xã/phường/thị trấn',
            ];
            $customName = [
                'fullname' => 'họ và tên',
                'email' => 'email',
                'phone' => 'số điện thoại',
                'address' => 'địa chỉ'
            ];
            $validator = Validator::make($request->all(), $rule, $messages, $customName);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            if (Session::has('discount')) {
                $discount = Session::get('discount');
                $discount->active = 1;
                $discount->save();
            }

            Session::forget('cart');
            Session::forget('discount_cart');

            $data = Order::where('id', $order->id)
                ->whereNull('deleted_at')
                ->with('payment')
                ->with(['order_detail' => function ($query) {
                    $query->with(['product_detail', 'discount']);
                }])
                ->first();
            // dd($data);
            Mail::to($order->email)->send(new MailCheckout($data));

            return redirect()->route('user.order.detail', ['id' => $order->id])->with('success', 'Đặt hàng thành công');
        } 
        else if ( $payment->payment_gateway == 'vnpay') {
            session(['url_prev' => url()->previous()]);
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = "http://localhost:8000/return-vnpay";
            $vnp_TmnCode = "FM4Y3OC6"; //Mã website tại VNPAY
            $vnp_HashSecret = "BWXBSUZZKGQISJYRFIHDPOVJZGGPESIO"; //Chuỗi bí mật
            $vnp_TxnRef = rand(); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_Amount = (int)$order->total*100;
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                // "vnp_BankCode" => "NCB", // mã ngân hàng
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_OrderInfo" => 'Đơn hàng ' . $order->id,
                "vnp_OrderType" => 100000, //Mã danh mục hàng hóa. Mỗi hàng hóa sẽ thuộc một nhóm danh mục do VNPAY quy định. Xem thêm bảng Danh mục hàng hóa
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_Locale" => 'vn'
            );
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }
            $vnp_Url = $vnp_Url . "?" . $query;

            if (isset($vnp_HashSecret)) {
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            return redirect($vnp_Url);
        } 
        else if ($payment->payment_gateway == 'momo') {
            session(['url_prev' => url()->previous()]);
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $paymentType = "captureWallet";
            $partnerCode = "MOMOBKUN20180529";
        $accessKey = "klm05TvNBzhg7h7j";
        $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";
        $orderInfo = "thanh toán hóa đơn";
            $amount = (int)$order->total;
            $orderId = date('YmdHis');
            $redirectUrl = "http://localhost:8000/return-momo";
            $ipnUrl = "http://localhost:8000/return-momo";
            $extraData = "$order->id";

            $requestId = time() . "";
            $requestType = $paymentType;
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = array(
                'partnerCode' => $partnerCode,
                'partnerName' => "MetaMarket",
                "storeId" => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            );
            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

           return  redirect($jsonResult['payUrl']);
        }
        
    }
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}
