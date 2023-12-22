<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\ProductDetail;

class ColorController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        $query = Color::whereNull('deleted_at');
        if(!empty($request->search))
        {
            $query->where('name','like','%'.$request->search.'%');
        }
        $color = $query->paginate(10);
        $data = [
            'rows' => $color,
            'breadcrumbs'        => [
                [
                    'name' => 'Màu sắc',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isProduct'=>true,
        ];
        return view('admin.color.index',$data);
    }
    public function store(Request $request)
    {
        if(empty($request->id_color))
        {
            // dd($request->all());
            $color = Color::where('name',$request->name_color)->orWhere('color_code',$request->color_code)->whereNull('deleted_at')->first();
            if(!empty($color))
            {
                return redirect()->back()->with('error', 'Màu đã tồn tại');
            }
            $color = new Color();
            $color->name = $request->name_color;
            $color->color_code = $request->color_code;
            $color->save();
            return redirect()->back()->with('success', 'Tạo màu thành công');
        }

        $color = Color::find($request->id_color);
        if(empty($color))
        {
            return redirect()->back()->with('error', 'Không tìm thấy màu');
        }

         $color = Color::where('color_code',$request->color_code)
                        ->whereNull('deleted_at')
                        ->where('id','<>',$request->id_color)->first();
        if(!empty($color))
        {
            return redirect()->back()->with('error', 'Màu đã tồn tại');
        }

        $color = Color::where('name',$request->name_color)
                        ->whereNull('deleted_at')
                        ->where('id','<>',$request->id_color)->first();
        if(!empty($color))
        {
            return redirect()->back()->with('error', 'Màu đã tồn tại');
        }

        $color = Color::find($request->id_color);
        $color->name = $request->name_color;
        $color->color_code = $request->color_code;
        $color->save();
        return redirect()->back()->with('success', 'Cập nhật màu thành công');
    }
    public function delete(Request $request)
    {
        // dd(json_decode($request->list_id));
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $color = Color::find($id);
            if(!empty($color))
            {
                $product_detail = ProductDetail::whereNull('deleted_at')->where('color_id',$color->id)->get();

                if(count($product_detail) > 0)
                {
                    return redirect()->back()->with('error', 'Đã có sản phẩm có màu '.$color->name);
                }

                $color->delete();
            }
        }
        return redirect()->back()->with('success', 'Xóa màu thành công');
    }
}