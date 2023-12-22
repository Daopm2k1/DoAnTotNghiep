<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCate;
use App\Models\ImagePost;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;

class PostController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        $query = Post::whereNull('deleted_at');
        if(!empty($request->search))
        {
            $query->where('name','like','%'.$request->search.'%');
        }
        $post = $query->paginate(10);
        
        $data = [
            'rows' => $post,
            'breadcrumbs'        => [
                [
                    'name' => 'Bài viết',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isPost'=>true,
        ];
        
        return view('admin.post.index',$data);
    }

    public function create(Request $request)
    {
        $post_cates = PostCate::all();
        $data = [
            'post_cates' => $post_cates,
            'rows' => null,
            'breadcrumbs'        => [
                [
                    'name' => 'Bài viết',
                    'url'  => 'admin/post',
                ],
                [
                    'name' => 'Tạo bài viết',
                    // 'url'  => 'admin/user/create',
                ],
            ],
            'isPost'=>true,
        ];
        
        return view('admin.post.createOrEdit',$data);
    }

    public function edit(Request $request,$id)
    {
        $post = Post::where('id',$id)->whereNull('deleted_at')->with(['images'])->first();
        // dd($product);
        $post_cates = PostCate::whereNull('deleted_at')->get();
        if(empty($post))
        {
            return redirect()->route('admin.post.index')->with('error', 'Không tìm thấy bài viết');
        }

        $image = ImagePost::where('post_id',$post->id)->where('is_primary',1)->first();
        $list_image = ImagePost::where('post_id',$post->id)->where('is_primary',0)->get();

        $data = [
            'post_cates' => $post_cates,
            'rows' => $post,
            'primary'=>$image,
            'list_image'=>$list_image,
            'breadcrumbs'        => [
                [
                    'name' => 'Bài viết',
                    'url'  => 'admin/post',
                ],
                [
                    'name' => 'Cập nhật bài viết',
                    // 'url'  => $url,
                ],
            ],
            'isPost'=>true,
        ];
       
        return view('admin.post.createOrEdit',$data);
    }

    public function addImagePost($post,$requestImage,$primary=false)
    {
        $image = new ImagePost();
        $image->post_id = $post->id;
        if($primary)
        {
            $image->is_primary = $primary;
        }
        $image->save();

        $file = $requestImage->getClientOriginalName();
        $fileName = pathinfo($file, PATHINFO_FILENAME);
        $imageName = $fileName."_".$post->id."_".$image->id.".".$requestImage->getClientOriginalExtension();
        
        $image_resize = Image::make($requestImage->getRealPath());              
        $image_resize->resize(300, 450);
        $image_resize->save('images/post/'.$imageName);
        
        $image->path = '/images/post/'.$imageName;
        $image->name = $imageName;
        $image->save();
        return;
    }
    
    public function store(Request $request)
    {
        // dd($request->all());
        $rule = [
            'name' => 'required',
            'path' => 'required',
            'description' => 'required',
            'author' => 'required',
            'content'  => 'required',
        ];
        $messages = [
            'name.required' => 'Nhập tên bài viết',
            'author.required' => 'Nhập tên tác giả',
            'content.required' => 'Nhập nội dung',
            'description.required' => 'Nhập mô tả',
            'image.required' => 'Chọn ảnh',
            'path.required' => 'Nhập tên bài viết để có đường dẫn',
            'mimes'=>'Ảnh phải có dạng *.jpg,*.png,*.jpeg',
            // 'max'=> 'The :attribute must be less than :max',
        ];
        $validator = Validator::make($request->all(),$rule,$messages);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator);
        }
        if(empty($request->id))
        {
            $post_check = Post::whereNull('deleted_at')->where('name',$request->name)->get();
            if(count($post_check)>0)
            {
                return redirect()->back()->with('error', 'Đã có tên bài viết');
            }

            $post = new Post();
            $post->name = $request->name;
            $post->author = $request->author;
            $post->post_content = $request->content;
            $post->description = $request->description;
            $post->path = $request->path;
            $post->post_cate_id = $request->post_cate_id;
            $post->save();

            if(!empty($request->image))
            {
                $this->addImagePost($post,$request->image,true);
            }

            return redirect()->route('admin.post.index')->with('success', 'Tạo bài viết thành công');
        }

        //Edit
        $post = Post::where('id',$request->id)->whereNull('deleted_at')->first();
        if(empty($post))
        {
            return redirect()->back()->with('error', 'Không tìm thấy bài viết');
        }
        
        $post_check = Post::whereNull('deleted_at')->where('id','<>',$request->id)->where('name',$request->name)->get();
        if(count($post_check)>0)
        {
            return redirect()->back()->with('error', 'Đã có tên bài viết');
        }

        $post->name = $request->name;
        $post->author = $request->author;
        $post->post_content = $request->content;
        $post->description = $request->description;
        $post->path = $request->path;
        $post->post_cate_id = $request->post_cate_id;
        $post->save();
        
        if(!empty($request->image))
        {
            if(!empty($request->id_img))
            {
                $image = ImagePost::find($request->id_img);
                if(File::exists(public_path().$image->path)) {
                    File::delete(public_path().$image->path);
                }
                $image->forceDelete();
            }
            $this->addImagePost($post,$request->image,true);
        }

        return redirect()->back()->with('success', 'Cập nhật bài viết thành công');
    }

    public function delete(Request $request)
    {
        // dd(json_decode($request->list_id));
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $post = Post::find($id);
            if(!empty($post))
            {
                $images = ImagePost::whereNull('deleted_at')
                                        ->where('post_id',$post->id)
                                        ->get();
                if(count($images) > 0)
                {
                    foreach($images as $image)
                    {
                        if(File::exists(public_path().$image->path)) {
                            File::delete(public_path().$image->path);
                        }
                        $image->forceDelete();
                    }
                }

                $post->delete();
            }
        }
        return redirect()->back()->with('success', 'Xóa bài viết thành công');
    }

}
