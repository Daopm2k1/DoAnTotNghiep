<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request,$id)
    {
        if(empty($id))
        {
            return redirect()->route('admin.comment.index')->with('error', 'Không tìm thấy bình luận sản phẩm');
        }
        $query = Comment::whereNull('deleted_at');
        $search = $request->search;
        if(!empty($search))
        {
            $query->whereHas('product',function($query) use ($search)
            {
                $query->where('name','like','%'.$search.'%');
            });
            $query->orwhereHas('user',function($query) use ($search)
            {
                $query->where('username','like','%'.$search.'%');
            });
        }
        $comment = $query->with(['user','product'])->paginate(10);
        // dd($comment);
        $data = [
            'rows' => $comment,
            'product_id' => $id,
            'breadcrumbs'        => [
                [
                    'name' => 'Sản phẩm',
                    'url'  => 'admin/product',
                ],
                [
                    'name' => $id,
                    // 'url'  => 'admin/dashboard',
                ],
                [
                    'name' => 'Bình luận',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isProduct'=>true,
        ];
        return view('admin.comment.index',$data);
    }

    public function delete(Request $request)
    {
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $comment = Comment::find($id);
            if(!empty($comment))
            {
                $comment->delete();
            }
        }
        return redirect()->back()->with('success', 'Xóa bình luận thành công');
    }
}
