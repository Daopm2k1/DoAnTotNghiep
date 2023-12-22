<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InforContact;
use Auth;

class InforContactController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        $query = InforContact::whereNull('deleted_at');
        $infor_contact = $query->paginate(10);
        $data = [
            'rows' => $infor_contact,
            'breadcrumbs'        => [
                [
                    'name' => 'Thông tin liên hệ',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isPost'=>true,
        ];
        return view('admin.infor_contact.index',$data);
    }

    public function store(Request $request)
    {
        $infor_contact = InforContact::all();
        if(count($infor_contact)>0){
            return redirect()->route('admin.infor_contact.index')->with('error', 'Đã có thông tin liên hệ');
        }
        else {
            if(empty($request->id_inforcontact))
            {
                $infor_contact = new InforContact();
                $infor_contact->address = $request->address;
                $infor_contact->time = $request->time;
                $infor_contact->phone = $request->phone;
                $infor_contact->email = $request->email;
                $infor_contact->map = $request->map;
                $infor_contact->save();
                return redirect()->route('admin.infor_contact.index')->with('success', 'Tạo thông tin thành công');
            }
            
            $infor_contact->address = $request->address;
            $infor_contact->time = $request->time;
            $infor_contact->phone = $request->phone;
            $infor_contact->email = $request->email;
            $infor_contact->map = $request->map;
            $infor_contact->save();
            return redirect()->route('admin.infor_contact.index')->with('success', 'Cập nhật thông tin thành công');
        }
    }

    public function delete(Request $request)
    {
        
        $list_id = json_decode($request->list_id);

        foreach($list_id as $id)
        {
            $infor_contact = InforContact::find($id);
            if(!empty($infor_contact))
            {   
                $infor_contact->forceDelete();
            }
        }
        return redirect()->back()->with('success', 'Xóa thành công');
    }
}