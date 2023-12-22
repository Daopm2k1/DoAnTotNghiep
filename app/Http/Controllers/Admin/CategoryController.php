<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        $query = Category::whereNull('deleted_at');
        if(!empty($request->search))
        {
            $query->where('name','like','%'.$request->search.'%');
        }
        $category = $query->paginate(10);
        $data = [
            'rows' => $category,
            'breadcrumbs'        => [
                [
                    'name' => 'Danh mục',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isProduct'=>true,
        ];
        return view('admin.category.index',$data);
    }

    public function store(Request $request)
    {
        if(empty($request->id_category))
        {
            $category_check = Category::where('name',$request->name_category)->whereNull('deleted_at')->get();
            if(count($category_check)>0)
            {
                return redirect()->back()->with('error', 'Đã có tên danh mục');
            }

            $category = new Category();
            $category->name = $request->name_category;
            $category->save();
            return redirect()->route('admin.category.index')->with('success', 'Tạo danh mục thành công');
        }
        $category = Category::find($request->id_category);
        if(empty($category))
        {
            return redirect()->route('admin.category.index')->with('error', 'Không tìm thấy danh mục');
        }

        $category_check = Category::where('name',$request->name_category)->where('id','<>',$request->id_category)->whereNull('deleted_at')->get();
        if(count($category_check)>0)
        {
            return redirect()->back()->with('error', 'Đã có tên danh mục');
        }
        
        $category->name = $request->name_category;
        $category->save();
        return redirect()->route('admin.category.index')->with('success', 'Cập nhật danh mục thành công');
    }

    public function delete(Request $request)
    {
        // dd(json_decode($request->list_id));
        $flag = false;
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $category = Category::find($id);
            if(!empty($category))
            {   
                if(count(Product::where('category_id',$category->id)->whereNull('deleted_at')->get()) != 0)
                {
                    return redirect()->back()->with('error', 'Danh mục '.$category->name.' đã có sản phẩm');
                }
            }
        }

        foreach($list_id as $id)
        {
            $category = Category::find($id);
            if(!empty($category))
            {   
                $category->forceDelete();
            }
        }
        return redirect()->back()->with('success', 'Xóa danh mục thành công');
    }
}
