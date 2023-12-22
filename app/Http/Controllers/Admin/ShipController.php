<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ship;
use App\Models\Payment;
use Vanthao03596\HCVN\Models\Province;
use Illuminate\Support\Facades\Validator;

class ShipController extends Controller
{
    //
        //
        public function __construct()
        {
            $this->middleware(['auth','admin']);
        }
        public function index(Request $request)
        {
            $key_search = $request->search;
            if(!empty($key_search))
            {
                $ship = Ship::whereHas('city',function($query) use ($key_search){
                                $query->where('name','LIKE','%'.$key_search.'%');
                            })
                            ->with('city')->paginate(10);
            }
            else
            {
                $ship = Ship::whereHas('city',function($query) use ($key_search){
                    $query->where('name','LIKE','%'.$key_search.'%');
                })->with('city')->paginate(10);   
            }

            $cities = Province::get();
            // dd($cities);

            $data = [
                'rows' => $ship,
                'cities' => $cities,
                'breadcrumbs'        => [
                    [
                        'name' => 'Phí vận chuyển',
                        // 'url'  => 'admin/dashboard',
                    ]
                ],
                'isShip'=>true,
            ];
            return view('admin.ship.index',$data);
        }
    
        public function store(Request $request)
        {
            $rule = [
                'city' => 'required',
                'fee' => 'required|numeric|digits_between:4,11',
            ];
            $messages = [
                'city.required' => 'Chọn thành phố',
                'fee.numeric' => 'Phí vận chuyển phải là số',
                'fee.required' => 'Nhập phí vận chuyển',
                'fee.digits_between' => ' Phải vận chuyển phải từ 1000 đến 99999999999',
            ];
            $validator = Validator::make($request->all(),$rule,$messages);
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator);
            }
            
            //  dd($request->all());
            if(empty($request->id_ship))
            {
                $ship = Ship::where('city_id',$request->city)->first();
                // dd($ship);
                if(!empty($ship))
                {
                    return redirect()->back()->with('error', 'Đã có phí vận chuyển cho thành phố này');
                }
                $ship = new Ship();
                $ship->city_id = $request->city;
                $ship->fee = $request->fee;
                $ship->save();
                return redirect()->back()->with('success', 'Tạo phí vận chuyển thành công');
            }
        }
    
        public function delete(Request $request)
        {
            $flag = false;
            $list_id = json_decode($request->list_id);
            foreach($list_id as $id)
            {
                $ship = Ship::find($id);

                if(!empty($ship))
                {   
                    // if(!empty(Payment::where('ship_id',$ship->id)->get()))
                    // {
                    //     return redirect()->back()->with('error', 'Phí vận chuyển đang được sử dụng');
                    // }
                    $ship->delete();
                }
            }

            return redirect()->back()->with('success', 'Xóa phí vận chuyển thành công');
        }
}
