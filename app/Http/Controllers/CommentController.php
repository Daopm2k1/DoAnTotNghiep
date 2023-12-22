<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\Comment;
use Auth;
use Illuminate\Support\Collection;

class CommentController extends Controller
{
    //
    public function addComment(Request $request){
        $rule = [
            'comment_text' => 'required',
        ];
        $messages = [
            'comment_text.required' => 'Nhập nội dung bình luận',
        ];
        $validator = Validator::make($request->all(),$rule,$messages);
        if($validator->fails())
        {
            return response()->json([
               'error'=>true,
               'message'=>$validator->errors(),
            ]);
        }

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->product_id = $request->product_id;
        $comment->comment_text = $request->comment_text;
        if(!empty($request->comment_id))
        {
            $comment->parent_id = $request->comment_id;
        }
        $comment->save(); 

        $data = Comment::whereNull('deleted_at')
                            ->where('id',$comment->id)
                            ->with(['user','product'])
                            ->with(array('children' => function($query) {
                                $query->with(['user','product']);
                            }))
                            ->first();
        return response()->json([
            'error'=>false,
            'message'=>'Bình luận thành công',
            'data' => $data,
        ]);
    }
    
    public function deleteComment(Request $request)
    {
        $id = $request->comment_id;
        $parent_id = $request->parent_id;

        if(empty($id))
        {
            return response()->json([
                'error'=>true,
                'message'=>'Không tìm thấy bình luận',
                'data' => null,
            ]);
        }
        $comment = Comment::where('id',$id)->first();
        $comment->delete();

        if($parent_id == 0)
        {
            return response()->json([
                'error'=>false,
                'message'=>'Xoá bình luận thành công',
                'data' => $comment,
                'child'=>false,
            ]);
        }
        return response()->json([
            'error'=>false,
            'message'=>'Xoá bình luận con thành công',
            'data' => $comment,
            'child'=>true,
        ]);
    }

    public function loadMore(Request $request)
    {
        $page = ($request->page ?? 1) + 1;
        $product_id = $request->product_id;

        $comments = Comment::whereNull('deleted_at')
                            ->whereNull('parent_id')
                            ->where('product_id',$product_id)
                            ->with(['user','product'])
                            ->with(array('children' => function($query) {
                                $query->with(['user','product']);
                            }))
                            ->orderBy('created_at','desc')
                            // ->paginate(5 * $page)
                            ->offset(0)
                            ->limit(10 * $page)
                            ->get()
                            // ->getCollection()
                            ->toArray();
                        
        $comments_check = Comment::whereNull('deleted_at')
                            ->whereNull('parent_id')
                            ->where('product_id',$product_id)
                            ->with(['user','product'])
                            ->with(array('children' => function($query) {
                                $query->with(['user','product']);
                            }))
                            ->orderBy('created_at','desc')
                            ->get()
                            ->toArray();
                           
        // $comments = new  Collection(array_reverse($comments));

        return response()->json([
            'error'=>false,
            'message'=>'Load more',
            'data' => $comments,
            'page' => $page,
            'all' => count($comments) == count($comments_check)
        ]);
    }
}
