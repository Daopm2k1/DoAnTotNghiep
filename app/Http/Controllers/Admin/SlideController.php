<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Product;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\User;
use Intervention\Image\ImageManagerStatic as Image;

class SlideController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        $slide = Slide::whereNull('deleted_at')->with('user')->paginate(10);
        $data = [
            'rows' => $slide,
            'breadcrumbs'        => [
                [
                    'name' => 'Trình chiếu',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isSlide'=>true,
        ];
        return view('admin.slide.index',$data);
    }

    public function store(Request $request)
    {
        $rule = [
            'image' => 'required|mimes:jpeg,jpg,png|max:10000',
        ];
        $messages = [
            'image.required' => 'Chọn hình ảnh',
            'image.mimes'=>'Ảnh phải có dạng *.png, *.jpg, *jpeg',
        ];

        $validator = Validator::make($request->all(),$rule,$messages);
        if($validator->fails())
        {
            return redirect()->back()->withError($validator->errors()->first());
        }
        $regex =  '(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w\#!:.?+=&%!\-\/]))?';

        if (!preg_match("/^$regex$/i", $request->path)) {
            dd(!preg_match("/^$regex$/i", $request->path), $request->path);
            return redirect()->back()->withError('Đường dẫn không hợp lệ');
        } 
        
        $slide = new Slide();
        $slide->user_id = Auth::id();
        $slide->title = $request->title;
        $slide->slide_content = $request->slide_content;
        $slide->path = $request->path;
        $slide->save();

        if(!empty($request->image))
        {
            $imageName=$slide->id.".".$request->image->getClientOriginalExtension();

            $image_resize = Image::make($request->image->getRealPath());              
            $image_resize->resize(400,400);
            $image_resize->save('images/slide/'.$imageName);
            // $request->image->move(public_path('images/slide'), $imageName);
            $slide->image = '/images/slide/'.$imageName;
        }
        $slide->save();
        return redirect()->back()->with('success', 'Tạo trình chiếu thành công');
    }

    public function delete(Request $request)
    {
        // dd(json_decode($request->list_id));
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $slide = slide::find($id);
            if(!empty($slide))
            {
                if(File::exists(public_path().$slide->image)) {
                    File::delete(public_path().$slide->image);
                }
                $slide->forceDelete();
            }
        }
        return redirect()->back()->with('success', 'Xóa trình chiếu thành công');
    }
    public function get_path_product(Request $request){
        $output = array(); 
        $result = Product::where('name', $request->product_name)->first();
        if($result){
            $output['path'] = 'product/' . $result->id . '/detail';
            $output['result'] = 1;
        }
        else{
            $output['path'] = $request->product_name;
            $output['result'] = -1;
        }
        echo json_encode($output);
    }
}