<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\ProductTag;
use Illuminate\Support\Str;

class TagController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        $query = Tag::whereNull('deleted_at');
        if(!empty($request->search))
        {
            $query->where('name','like','%'.$request->search.'%');
        }
        $tag = $query->paginate(10);
        $data = [
            'rows' => $tag,
            'breadcrumbs'        => [
                [
                    'name' => 'Từ khóa',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isProduct'=>true,
        ];
        return view('admin.tag.index',$data);
    }

    public function store(Request $request)
    {
        // dd(Str::slug($request->name_tag,'-'));
        if(empty($request->id_tag))
        {
            $tag =Tag::where('slug',Str::slug($request->name_tag,'-'))->whereNull('deleted_at')->first();
            if(!empty($tag))
            {
                return redirect()->back()->with('error', 'Từ khóa đã tồn tại');
            }
            $tag = new tag();
            $tag->name = $request->name_tag;
            $tag->slug = Str::slug($request->name_tag,'-');
            $tag->save();
            return redirect()->back()->with('success', 'Tạo từ khóa thành công');
        }
        $tag = Tag::where('slug',Str::slug($request->name_tag,'-'))
        ->where('id','<>',$request->id_tag)
        ->whereNull('deleted_at')->first();

        if(!empty($tag))
        {
            return redirect()->back()->with('error', 'Từ khóa đã tồn tại');
        }

        $tag = Tag::find($request->id_tag);
        if(empty($tag))
        {
            return redirect()->back()->with('error', 'Không tìm thấy từ khóa');
        }
        $tag->name = $request->name_tag;
        $tag->slug = Str::slug($request->name_tag,'-');
        $tag->save();
        return redirect()->back()->with('success', 'Cập nhật từ khóa thành công');
    }


    public function delete(Request $request)
    {
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $tag = Tag::find($id);
            if(!empty($tag))
            {
                $product_tags = ProductTag::whereNull('deleted_at')->where('tag_id',$tag->id)->get();

                if(!empty($product_tags))
                {
                    foreach($product_tags as $product_tag)
                    {
                        $product_tag->delete();
                    }
                }
                $tag->delete();
            }
        }
        return redirect()->back()->with('success', 'Xóa từ khóa thành công');
    }
}