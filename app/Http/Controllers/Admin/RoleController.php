<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
class RoleController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        if(!empty($request->search))
        {
            $role = Role::where('name','like','%'.$request->search.'%')->paginate(10);
        }
        else
        {
            $role = Role::paginate(10);   
        }
        $data = [
            'rows' => $role,
            'breadcrumbs'        => [
                [
                    'name' => 'Phân quyền',
                    // 'url'  => 'admin/dashboard',
                ]
            ],
            'isUser'=>true,
        ];
        return view('admin.role.index',$data);
    }

    public function store(Request $request)
    {
        if(empty($request->id_role))
        {
            $role = Role::where('name',$request->name_role)->first();
            if(!empty($role))
            {
                return redirect()->back()->with('error', 'Phân quyền đã tồn tại');
            }
            $role = new Role();
            $role->name = $request->name_role;
            $role->save();
            return redirect()->back()->with('success', 'Tạo phân quyền thành công');
        }
        // $role = Role::find($request->id_role);
        // if(empty($role))
        // {
        //     return redirect()->back()->with('error', 'Không tìm thấy phân quyền');
        // }

        // $roleCheck = Role::where('name',$request->name_role)->first();
        // if(!empty($roleCheck))
        // {
        //     return redirect()->back()->with('error', 'Phân quyền đã tồn tại');
        // }

        // $role->name = $request->name_role;
        // $role->save();
        // return redirect()->back()->with('success', 'Cập nhật phân quyền thành công');
    }

    public function delete(Request $request)
    {
        // dd(json_decode($request->list_id));
        $flag = false;
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $role = Role::find($id);
            if(!empty($role))
            {   
                if(count(User::role($role->name)->get()) != 0)
                {
                    return redirect()->back()->with('error', 'Phân quyền '.$role->name.' đã có tài khoản sử dụng');
                }
            }
        }

        foreach($list_id as $id)
        {
            $role = Role::find($id);
            if(!empty($role))
            {   
                $role->forceDelete();
            }
        }
        return redirect()->back()->with('success', 'Xóa phân quyền thành công');
    }
}
