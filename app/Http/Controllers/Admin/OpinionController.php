<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Opinion;

class OpinionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        $query = Opinion::whereNull('deleted_at')->orderBy('created_at', 'desc');
        $opinion = $query->paginate(10);
        $data = [
            'rows' => $opinion,
            'breadcrumbs'        => [
                [
                    'name' => 'Ý kiến',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isOpinion'=>true,
        ];
        return view('admin.opinion.index',$data);
    }

    public function delete(Request $request)
    {
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $opinion = Opinion::find($id);
            if(!empty($opinion))
            {
                $opinion->delete();
            }
        }
        return redirect()->back()->with('success', 'Xóa ý kiến thành công');
    }
}
