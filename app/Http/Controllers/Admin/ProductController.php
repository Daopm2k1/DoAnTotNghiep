<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\ProductDetail;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\ImageProduct;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        $query = Product::whereNull('deleted_at');
        if(!empty($request->search))
        {
            $query->where('name','like','%'.$request->search.'%');
        }
        $product = $query->with('category')->paginate(10);
        // dd($product[0]->category->name);
        $data = [
            'rows' => $product,
            'breadcrumbs'        => [
                [
                    'name' => 'Sản phẩm',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isProduct'=>true,
        ];
        return view('admin.product.index',$data);
    }

    public function create(Request $request)
    {
        $categories = Category::all();
        $data = [
            'categories' => $categories,
            'rows' => null,
            'breadcrumbs'        => [
                [
                    'name' => 'Sản phẩm',
                    'url'  => 'admin/product',
                ],
                [
                    'name' => 'Tạo sản phẩm',
                    // 'url'  => 'admin/user/create',
                ],
            ],
            'isProduct'=>true,
        ];
        return view('admin.product.createOrEdit',$data);
    }

    public function edit(Request $request,$id)
    {
        $product = Product::where('id',$id)->whereNull('deleted_at')->with(['category','images'])->first();
        // dd($product);
        $categories = Category::whereNull('deleted_at')->get();
        if(empty($product))
        {
            return redirect()->route('admin.product.index')->with('error', 'Không tìm thấy sản phẩm');
        }

        $image = ImageProduct::where('product_id',$product->id)->where('is_primary',1)->first();
        $list_image = ImageProduct::where('product_id',$product->id)->where('is_primary',0)->get();

        $tags = ProductTag::whereNull('deleted_at')->where('product_id',$id)->with(['tag','product'])->get();
        // $url = 'admin/user/edit/'.$id;
        $data = [
            'categories' => $categories,
            'rows' => $product,
            'primary'=>$image,
            'list_image'=>$list_image,
            'tags' => $tags,
            'breadcrumbs'        => [
                [
                    'name' => 'Sản phẩm',
                    'url'  => 'admin/product',
                ],
                [
                    'name' => 'Cập nhật sản phẩm',
                    // 'url'  => $url,
                ],
            ],
            'isProduct'=>true,
        ];
        return view('admin.product.createOrEdit',$data);
    }

    public function addImageProduct($product,$requestImage,$primary=false)
    {
        $image = new  ImageProduct();
        $image->product_id = $product->id;
        if($primary)
        {
            $image->is_primary = $primary;
        }
        $image->save();

        $file = $requestImage->getClientOriginalName();
        $fileName = pathinfo($file, PATHINFO_FILENAME);
        $imageName = $fileName."_".$product->id."_".$image->id.".".$requestImage->getClientOriginalExtension();
        
        $image_resize = Image::make($requestImage->getRealPath());              
        $image_resize->resize(300, 450);
        $image_resize->save('images/product/'.$imageName);
        
        $image->path = '/images/product/'.$imageName;
        $image->name = $imageName;
        $image->save();
        return;
    }
    
    public function store(Request $request)
    {
        // dd($request->all());
        $rule = [
            'name' => 'required',
            'price' => 'required|numeric|digits_between:4,11',
            'description' => 'required',
            'image' => 'mimes:jpeg,jpg,png|max:10000',
            'image1' => 'mimes:jpeg,jpg,png|max:10000',
            'image2' => 'mimes:jpeg,jpg,png|max:10000',
            'image3' => 'mimes:jpeg,jpg,png|max:10000',
        ];
        $messages = [
            'name.required' => 'Nhập tên sản phẩm',
            'price.numeric' => 'Giá phải là số',
            'price.required' => 'Nhập giá sản phẩm',
            'image.required' => 'Chọn ảnh',
            'digits_between' => 'Giá phải nhiều hơn 1000 và nhỏ hơn 99999999999',
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
            $product_check = Product::whereNull('deleted_at')->where('name',$request->name)->get();
            if(count($product_check)>0)
            {
                return redirect()->back()->with('error', 'Đã có tên sản phẩm');
            }

            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->category_id = $request->category;
            $product->price = $request->price;
            $product->save();

            if(!empty($request->image))
            {
                $this->addImageProduct($product,$request->image,true);
            }

            if(!empty($request->image1))
            {
                $this->addImageProduct($product,$request->image1);
            }

            if(!empty($request->image2))
            {
                $this->addImageProduct($product,$request->image2);
            }

            if(!empty($request->image3))
            {
                $this->addImageProduct($product,$request->image3);
            }

            return redirect()->route('admin.product.index')->with('success', 'Tạo sản phẩm thành công');
        }

        //Edit
        $product = Product::where('id',$request->id)->whereNull('deleted_at')->first();
        if(empty($product))
        {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm');
        }
        
        $product_check = Product::whereNull('deleted_at')->where('id','<>',$request->id)->where('name',$request->name)->get();
        if(count($product_check)>0)
        {
            return redirect()->back()->with('error', 'Đã có tên sản phẩm');
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->category_id = $request->category;
        $product->price = $request->price;
        $product->save();
        
        if(!empty($request->image))
        {
            if(!empty($request->id_img))
            {
                $image = ImageProduct::find($request->id_img);
                if(File::exists(public_path().$image->path)) {
                    File::delete(public_path().$image->path);
                }
                $image->forceDelete();
            }
            $this->addImageProduct($product,$request->image,true);
        }

        if(!empty($request->image1))
        {   
            if(!empty($request->id_img_0))
            {
                $image = ImageProduct::find($request->id_img_0);
                if(File::exists(public_path().$image->path)) {
                    File::delete(public_path().$image->path);
                }
                $image->forceDelete();
            }
            $this->addImageProduct($product,$request->image1);
        }

        if(!empty($request->image2))
        {   
            if(!empty($request->id_img_1))
            {
                $image = ImageProduct::find($request->id_img_1);
                if(File::exists(public_path().$image->path)) {
                    File::delete(public_path().$image->path);
                }
                $image->forceDelete();
            }
            $this->addImageProduct($product,$request->image2);
        }

        if(!empty($request->image3))
        {   
            if(!empty($request->id_img_2))
            {
                $image = ImageProduct::find($request->id_img_2);
                if(File::exists(public_path().$image->path)) {
                    File::delete(public_path().$image->path);
                }
                $image->forceDelete();
            }
            $this->addImageProduct($product,$request->image3);
        }

        return redirect()->back()->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function delete(Request $request)
    {
        // dd(json_decode($request->list_id));
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $product = Product::find($id);
            if(!empty($product))
            {
                $images = ImageProduct::whereNull('deleted_at')
                                        ->where('product_id',$product->id)
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

                $product_tag = ProductTag::whereNull('deleted_at')
                                ->where('product_id',$product->id)
                                ->get();
                if(!empty($product_tag))
                {
                    foreach($product_tag as $item)
                    {
                        $item->delete();
                    }
                }

                $product_detail = ProductDetail::whereNull('deleted_at')
                        ->where('product_id',$product->id)
                        ->get();

                if(!empty($product_detail))
                {
                    foreach($product_detail as $item_detail)
                    {
                        $item_detail->delete();
                    }
                }

                $wishlist = Wishlist::whereNull('deleted_at')
                    ->where('product_id',$product->id)
                    ->get();

                if(!empty($wishlist))
                {
                    foreach($wishlist as $item_wishlist)
                    {
                        $item_wishlist->delete();
                    }
                }

                $product->delete();
            }
        }
        return redirect()->back()->with('success', 'Xóa sản phẩm thành công');
    }

    public function addTag($id = '',Request $request)
    {
        if(empty($id))
        {
            return response()->json([
                'error'=>true,
                'message'=>'Không tìm thấy sản phẩm',
                'data' => null,
            ]);
        }

        if(empty($request->value))
        {
            return response()->json([
                'error'=>true,
                'message'=>'Nhập từ khóa',
                'data' => null,
            ]);
        }

        $value = strtolower($request->value);
        $slug =  Str::slug($value, '-');

        $tag = Tag::whereNull('deleted_at')->where('slug',$slug)->first();
        if(empty($tag))
        {
            $tag = new Tag();
            $tag->name = $value;
            $tag->slug = $slug;
            $tag->save();
        }

        $product_tag = ProductTag::whereNull('deleted_at')->where('product_id',$id)
                ->whereHas('tag',function($query) use ($slug){
                    $query->where('slug',$slug);
                })->first();

        if(!empty($product_tag))
        {
            return response()->json([
                'error'=>true,
                'message'=>'Từ khóa đã tồn tại',
                'data' => null,
            ]);
        }

        $data = ProductTag::whereNull('deleted_at')->where('product_id',$id)->with(['tag','product'])->get();
        if(count($data) >= 20)
        {
            return response()->json([
                'error'=>true,
                'message'=>'Chỉ được nhập tối đa 20 từ khóa',
                'data' => null,
            ]);
        }

        $product_tag = new ProductTag();
        $product_tag->product_id = $id;
        $product_tag->tag_id = $tag->id;
        $product_tag->save();

        $data = ProductTag::whereNull('deleted_at')->where('product_id',$id)->with(['tag','product'])->get();
    
        return response()->json([
            'error'=>false,
            'message'=>'Tạo từ khóa thành công',
            'data'=>$data,
        ]);
    }

    public function removeTag($id = '',Request $request)
    {
        $id_tag = $request->idTag;
        if(empty($id))
        {
            return response()->json([
                'error'=>true,
                'message'=>'Không tìm thấy sản phẩm',
                'data' => null,
            ]);
        }

        if(empty($id_tag))
        {
            return response()->json([
                'error'=>true,
                'message'=>'Không tìm thấy từ khóa sản phẩm',
                'data' => null,
            ]);
        }

        $product_tag = ProductTag::whereNull('deleted_at')->where('product_id',$id)
                        ->where('tag_id',$id_tag)->first();

        if(empty($product_tag))
        {
            return response()->json([
                'error'=>true,
                'message'=>'Không tìm thấy từ khóa sản phẩm',
                'data' => null,
            ]);
        }

        $product_tag->delete();

        $data = ProductTag::whereNull('deleted_at')->where('product_id',$id)->with(['tag','product'])->get();
    
        return response()->json([
            'error'=>false,
            'message'=>'Xóa từ khóa thành công',
            'data'=>$data,
        ]);

    }
}
