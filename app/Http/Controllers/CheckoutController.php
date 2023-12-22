<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use Cart;
use Auth;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductDetail;
use App\Models\Ship;
use App\Models\Address;
use App\Models\PostCate;
use App\Models\InforContact;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Mail;
use App\Mail\MailCheckout;
use Illuminate\Support\Str;
use Vanthao03596\HCVN\Models\Province;
use Vanthao03596\HCVN\Models\District;
use Vanthao03596\HCVN\Models\Ward;

class CheckoutController extends Controller
{
    private $vnp_TmnCode  = "UDOPNWS1";
    private $vnp_HashSecret   = "EBAHADUGCOEWYXCMYZRMTMLSHGKNRPBN ";
    private $vnp_Url   = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    private $vnp_Returnurl   = "http://localhost:8000/return-vnpay";

    public function checkout()
    {

        $categories = Category::whereNull('deleted_at')->get();
        $category_post = PostCate::where('status', 1)->get();
        $infor_contact = InforContact::all();
        $cities = Province::get();
        $discount = Discount::whereNull('deleted_at')
            ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end_date', '>=', Carbon::now()->format('Y-m-d'))
            ->where('user_apply', Auth::id())
            ->where('active', 0)
            ->get();
        // dd($discount);
        $address = Address::where('deleted_at')
            ->where('user_id', Auth::id())
            ->with(['user', 'province', 'district', 'ward'])
            ->orderBy('is_primary', 'desc')
            ->get();

        $address_primary = Address::where('deleted_at')
            ->where('user_id', Auth::id())
            ->where('is_primary', 1)
            ->with(['province', 'district', 'ward'])
            ->first();
        if (!empty($address_primary)) {
            $district_primary = Province::where('id', $address_primary->province_id)->first()->districts;
            $ward_primary = District::where('id', $address_primary->district_id)->first()->wards;
            $ship = Ship::where('city_id', $address_primary->province_id)->first();
        }

        $data = [
            'categories' => $categories,
            'category_post' => $category_post,
            'infor_contact' => $infor_contact,
            'user' => Auth::user(),
            'cities' => $cities,
            'list_discount' => $discount,
            'address' => $address,
            'address_primary' => $address_primary,
            'district_primary' => $district_primary ?? null,
            'ward_primary' => $ward_primary ?? null,
            'ship' => $ship ?? null,
            'title' => 'Thanh toán',
            'breadcrumbs' => [
                [
                    'name' => 'Trang chủ',
                    'url'  => '/',
                ],
                [
                    'name' => 'Thanh toán',
                ],
            ]
        ];
        return view('user.checkout.checkout', $data);
    }

    public function checkCategoryDiscount($product_id, $list_id_apply)
    {
        $products = Product::whereNull('deleted_at')->whereIn('category_id', $list_id_apply)->get()->pluck('id')->toArray();
        // dd($product_id,$products,in_array($product_id,$products));
        if (in_array($product_id, $products)) {
            return true;
        }
        return false;
    }

    public function payment($request)
    {
        if (empty($request->ward) || empty($request->district) ||  empty($request->province)) {
            return false;
        }
        if (Auth::check()) {
            // dd($request->all());
            $address_check = Address::where('user_id', Auth::id())
                ->where('province_id', $request->province)
                ->where('district_id', $request->district)
                ->where('ward_id', $request->ward)
                ->where('street', $request->address)
                ->first();

            if (empty($address_check)) {
                $address = new Address();
                $address->user_id = Auth::id();
                $address->province_id = $request->province;
                $address->district_id = $request->district;
                $address->ward_id = $request->ward;
                $address->street = $request->address;

                $address_check = Address::whereNull('deleted_at')->where('user_id', Auth::id())->get();
                if (count($address_check) == 0) {
                    $address->is_primary = 1;
                }
                $address->save();
            }
        }

        $order = new Order();
        $order->fullname = $request->fullname;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->address = $request->address . ', ' . getNameWard($request->ward) . ', ' . getNameDistrict($request->district) . ', ' . getNameProvince($request->province);
        $order->note = $request->note;
        $order->save();

        $cart = json_decode($request->list_product);
        $total_sale = 0;
        if (!empty($request->discount)) {
            $discount = Discount::find($request->discount);
            if (!empty($discount)) {
                $list_id_apply = json_decode($discount->apply);

                if ($discount->type == "product") {
                    foreach ($cart as $item) {
                        // dd($cart,$item->product,$list_id_apply);
                        $order_detail = new OrderDetail();
                        $order_detail->order_id = $order->id;
                        $order_detail->product_detail_id = $item->id;
                        $order_detail->quantity = $item->qty;
                        $order_detail->price = $item->price * $item->qty;

                        if (in_array($item->product, $list_id_apply)) {
                            $order_detail->discount_id = $discount->id;
                            $sale_price = $order_detail->price * $discount->sale_percent / 100;
                            $order_detail->price = $order_detail->price - $sale_price;
                            $total_sale += $sale_price;
                        }
                        if ($order_detail->save()) {
                            $product_detail = ProductDetail::find($order_detail->product_detail_id);
                            $product_detail->quantity = $product_detail->quantity - $order_detail->quantity;
                            $product_detail->save();
                        }
                    }
                } else {
                    foreach ($cart as $item) {
                        // dd($cart,$item->product,$list_id_apply);
                        $order_detail = new OrderDetail();
                        $order_detail->order_id = $order->id;
                        $order_detail->product_detail_id = $item->id;
                        $order_detail->quantity = $item->qty;
                        $order_detail->price = $item->price * $item->qty;
                        if ($this->checkCategoryDiscount($item->product, $list_id_apply)) {
                            $order_detail->discount_id = $discount->id;
                            $sale_price = $order_detail->price * $discount->sale_percent / 100;
                            $order_detail->price = $order_detail->price - $sale_price;
                            $total_sale += $sale_price;
                        }
                        if ($order_detail->save()) {
                            $product_detail = ProductDetail::find($order_detail->product_detail_id);
                            $product_detail->quantity = $product_detail->quantity - $order_detail->quantity;
                            $product_detail->save();
                        }
                    }
                }
                Session::put('discount', $discount);
            }
        } else {
            foreach ($cart as $item) {
                $order_detail = new OrderDetail();
                $order_detail->order_id = $order->id;
                $order_detail->product_detail_id = $item->id;
                $order_detail->quantity = $item->qty;
                $order_detail->price = $item->price * $item->qty;
                if ($order_detail->save()) {
                    $product_detail = ProductDetail::find($order_detail->product_detail_id);
                    $product_detail->quantity = $product_detail->quantity - $order_detail->quantity;
                    $product_detail->save();
                }
            }
        }

        $total = OrderDetail::whereNull('deleted_at')->where('order_id', $order->id)->sum('price');
        $order->total = $total;
        $order->sale_price = $total_sale;
        $order->save();

        $payment = new Payment();
        if ($request->payment == "delivery") {
            $payment->method = $request->payment;
        } else {
            $payment->method = "online";
        }
        $payment->order_id = $order->id;
        $payment->ship_id = getLimitFee($request->province)->id ?? null;
        if ($request->payment != "delivery") {
            $payment->payment_gateway = $request->payment;
        }
        $payment->code_bank =  $request->bank;
        $payment->status = 0;
        $payment->amount = $total + (getLimitFee($request->province)->fee ?? 0);
        $payment->save();

        Session::forget('discount_cart');
        Session::forget('cart');

        return $order;
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

    public function store(Request $request)
    {
        if (isset($request->payment) && $request->payment == 'delivery') {
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

            $order = $this->payment($request);

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
        } else if (isset($request->payment) && $request->payment == 'vnpay') {
            session(['url_prev' => url()->previous()]);

            $order = $this->payment($request);
            if (empty($order)) {
                return redirect()->back()->withError('Cần chọn đầy đủ thông tin để thanh toán');
            }

            if (Session::has('order') || empty(Session::has('order'))) {
                Session::forget('order');
            }

            Session::put('order', $order);

            $data = Order::where('id', $order->id)
                ->whereNull('deleted_at')
                ->with(['payment' => function ($query) {
                    $query->with('ship');
                }])
                ->with(['order_detail' => function ($query) {
                    $query->with(['product_detail', 'discount']);
                }])
                ->first();

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
        } else if (isset($request->payment) && $request->payment == 'momo') {
            session(['url_prev' => url()->previous()]);

            $order = $this->payment($request);
            if (empty($order)) {
                return redirect()->back()->withError('Cần chọn đầy đủ thông tin để thanh toán');
            }

            if (Session::has('order') || empty(Session::has('order'))) {
                Session::forget('order');
            }

            Session::put('order', $order);

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
                'signature' => $signature,
            );
            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);
           return  redirect($jsonResult['payUrl']);
        }
    }
    public function getPriceDiscount($discount)
    {
        $carts = Session::get('cart');
        $list_id_apply = json_decode($discount->apply);
        if ($discount->type == 'product') {
            foreach ($carts as $key => $cart) {
                if (in_array($cart['product'], $list_id_apply)) {
                    $carts[$key]['discount'] = $discount;
                }
            }
        } else if ($discount->type == 'category') {
            foreach ($carts as $key => $cart) {
                if ($this->checkCategoryDiscount($cart['product'], $list_id_apply)) {
                    $carts[$key]['discount'] = $discount;
                }
            }
        }
        Session::put('discount_cart', $discount);
        Session::put('cart', $carts);
        return;
    }

    public function checkDiscount(Request $request)
    {
        // $request->session()->forget('cart');
        // $request->session()->forget('discount');
        // return;
        if (empty($request->discount)) {
            return response()->json([
                'error' => true,
                'message' => 'Vui lòng nhập lại mã giảm giá nếu có',
            ]);
        }

        $discount = Discount::where('discount_code', $request->discount)
            ->whereNull('deleted_at')
            ->where('active', 0)
            ->with(['user', 'userApply'])
            ->first();

        if (empty($discount)) {
            return response()->json([
                'error' => true,
                'message' => 'Nhập sai mã giảm giá vui lòng nhập lại',
            ]);
        }
        if (!Auth::check()) {
            return response()->json([
                'error' => true,
                'message' => 'Vui lòng đăng nhập để sử dụng giảm giá',
            ]);
        }

        if ($discount->userApply->id != Auth::id()) {
            return response()->json([
                'error' => true,
                'message' => 'Mã giảm giá không áp dụng cho tài khoản này',
            ]);
        }

        $now = Carbon::now()->format('d/m/Y');
        $start_date = Carbon::parse($discount->start_date)->format('d/m/Y');
        $end_date = Carbon::parse($discount->end_date)->format('d/m/Y');

        $compareNowToStartTime = Carbon::createFromFormat('d/m/Y', $now)->lt(Carbon::createFromFormat('d/m/Y', $start_date));
        $compareNowToEndTime = Carbon::createFromFormat('d/m/Y', $now)->gt(Carbon::createFromFormat('d/m/Y', $end_date));

        if ($compareNowToStartTime || $compareNowToEndTime) {
            return response()->json([
                'error' => true,
                'message' => 'Mã giảm giá sử dụng không đúng thời gian giảm giá',
                'lt' => Carbon::createFromFormat('d/m/Y', $now)->lt(Carbon::createFromFormat('d/m/Y', $start_date)),
                'gt' => Carbon::createFromFormat('d/m/Y', $now)->gt(Carbon::createFromFormat('d/m/Y', $end_date)),
                'now' => $now,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);
        }

        $this->getPriceDiscount($discount);

        return response()->json([
            'error' => false,
            'message' => 'Sử dụng mã giảm giá thành công',
            'data' => $discount,
            'cart' => Session::get('cart'),
            'discount' => Session::get('discount_cart')
        ]);
    }

    public function checkShip(Request $request)
    {
        $id_province = $request->id_province;
        if (empty($id_province)) {
            return response()->json([
                'error' => true,
                'message' => 'Chưa chọn tỉnh/thành phố',
                'data' => null,
            ]);
        }

        $ship = Ship::where('city_id', $id_province)->first();

        if (empty($ship)) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy phí vận chuyển',
                'data' => null,
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Lấy phí vận chuyển thành công',
            'data' => $ship,
            // 'total' =>$total,
        ]);
    }

    public function returnVNPay(Request $request)
    {
        $url = session('url_prev', '/');
        $order = Session::get('order');

        if ($request->vnp_ResponseCode == "00") {
            $payment = Payment::whereNull('deleted_at')->where('order_id', $order->id)->first();
            $payment->status = 1;
            $payment->payment_log = json_encode($request->all());
            $payment->save();

            if (Session::has('discount')) {
                $discount = Session::get('discount');
                $discount->active = 1;
                $discount->save();
            }
            $order = Order::where('id', $order->id)
                ->whereNull('deleted_at')
                ->with(['payment' => function ($query) {
                    $query->with('ship');
                }])
                ->with(['order_detail' => function ($query) {
                    $query->with(['product_detail', 'discount']);
                }])
                ->first();

            Mail::to($order->email)->send(new MailCheckout($order));

            Session::forget('order');
            Session::forget('discount');
            // $order_id = $this->paymentVNPay($request);
            return redirect()->route('user.order.detail', ['id' => $order->id])->with('success', 'Đặt hàng thành công');
        }
        Session::forget('order');
        Session::forget('discount');
        session()->forget('url_prev');
        return redirect($url)->with('error', 'Lỗi trong quá trình thanh toán phí dịch vụ');
    }

    public function returnMomo(Request $request)
    {
      
        $url = session('url_prev', '/');
       $ids = request()->get('extraData');
        if ($request->resultCode == '0') {
            $payment = Payment::where('order_id', $ids)->first();
            $payment->status = 1;
            $payment->payment_log = json_encode($request->all());
            $payment->save();

            if (Session::has('discount')) {
                $discount = Session::get('discount');
                $discount->active = 1;
                $discount->save();
            }
            $order = Order::where('id', $ids)
                ->whereNull('deleted_at')
                ->with(['payment' => function ($query) {
                    $query->with('ship');
                }])
                ->with(['order_detail' => function ($query) {
                    $query->with(['product_detail', 'discount']);
                }])
                ->first();

            Session::forget('order');
            Session::forget('discount');
            Mail::to($order->email)->send(new MailCheckout($order));

            return redirect()->route('user.order.detail', ['id' => $order->id])->with('success', 'Đặt hàng thành công');
        }
        Session::forget('order');
        Session::forget('discount');
        session()->forget('url_prev');
        return redirect($url)->with('error', $request->localMessage);
    }

    public function removeDiscount(Request $request)
    {
        $session_cart = Session::get('cart');

        foreach ($session_cart as $key => $item) {
            unset($session_cart[$key]['discount']);
        }
        // unset($session_cart['discount']);
        Session::put('cart', $session_cart);
        Session::forget('discount_cart');

        return response()->json([
            'error' => false,
            'message' => 'Get success',
            'data' => Session::get('cart'),
        ]);
    }

    public function getDistrict(Request $request)
    {
        if (empty($request->id)) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy tỉnh/thành phố',
                'data' => null,
                'cart' => Session::get('cart'),
            ]);
        }

        $districts =  Province::where('id', $request->id)->first()->districts;
        $ship = Ship::where('city_id', $request->id)->first();

        return response()->json([
            'error' => false,
            'message' => 'Lấy danh sách quận/huyện thành công',
            'data' => $districts,
            'cart' => Session::get('cart'),
            'fee' => $ship->fee ?? 0,
        ]);
    }

    public function getWard(Request $request)
    {
        if (empty($request->id)) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy quận/huyện',
                'data' => null,
            ]);
        }

        $ward =  District::where('id', $request->id)->first()->wards;
        // $ship = Ship::where('city_id',$request->id)->first();

        return response()->json([
            'error' => false,
            'message' => 'Lấy danh sách xã/phường/thị trấn thành công',
            'data' => $ward,
        ]);
    }

    public function getAddress(Request $request)
    {
        $province_id = $request->province_id;
        $district_id = $request->district_id;
        if (empty($province_id)) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy tỉnh/thành phố',
                'data' => null,
            ]);
        }

        if (empty($district_id)) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy quận/huyện',
                'data' => null,
            ]);
        }

        $district =  Province::where('id', $province_id)->first()->districts;
        $ward =  District::where('id', $district_id)->first()->wards;
        $ship = Ship::where('city_id', $province_id)->first();

        return response()->json([
            'error' => false,
            'message' => 'Lấy danh sách thành công',
            'data' => ['district' => $district, 'ward' => $ward],
            'cart' => Session::get('cart'),
            'fee' => $ship->fee ?? 0,
        ]);
    }

    public function deleteAddress(Request $request)
    {
        $street = $request->street;
        $province_id = $request->province_id;
        $district_id = $request->district_id;
        $ward_id = $request->ward_id;
        if (empty($province_id)) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy tỉnh/thành phố',
                'data' => null,
            ]);
        }

        if (empty($district_id)) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy quận/huyện',
                'data' => null,
            ]);
        }

        if (empty($ward_id)) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy xã/phường/thị trấn',
                'data' => null,
            ]);
        }

        if (empty($street)) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy đường',
                'data' => null,
            ]);
        }

        $address = Address::where('user_id', Auth::id())
            ->where('province_id', $province_id)
            ->where('district_id', $district_id)
            ->where('ward_id', $ward_id)
            ->where('street', $street)
            ->first();
        if (empty($address)) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy địa chỉ',
                'data' => null,
            ]);
        }

        $address->delete();

        $address_check = Address::where('user_id', Auth::id())
            ->where('is_primary', 1)
            ->first();
        if (empty($address_new)) {
            $address_first = Address::where('user_id', Auth::id())
                ->where('is_primary', 0)
                ->first();

            if (!empty($address_first)) {
                $address_first->is_primary = 1;
                $address_first->save();
            }
        }

        $list_address = Address::where('deleted_at')
            ->where('user_id', Auth::id())
            ->with(['user', 'province', 'district', 'ward'])
            ->orderBy('is_primary', 'desc')
            ->get();

        return response()->json([
            'error' => false,
            'message' => 'Xoán thành công',
            'data' => $list_address,
        ]);
    }
}
