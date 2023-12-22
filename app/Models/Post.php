<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ImagePost;
use Auth;

class Post extends Model
{
    //
    use SoftDeletes;
    protected $table = 'posts';

    public function post_cate()
    {
        return $this->hasMany(PostCate::class, 'id', 'post_cate_id');
    }
    public function images()
    {
        return $this->hasMany(ImagePost::class, 'post_id', 'id');
    }
    public function getImagePrimary()
    {
        $id = $this->id;
        $image = ImagePost::where('post_id',$id)->where('is_primary',1)->first();
        
        if(empty($image))
        {
            return '/images/post/no-image-post.png';
        }

        return $image->path;
    }

    public function getExtraImage()
    {
        $id = $this->id;
        $image = ImagePost::where('post_id',$id)->where('is_primary',0)->get();
        
        if(empty($image))
        {
            return;
        }

        return $image;
    }
}
