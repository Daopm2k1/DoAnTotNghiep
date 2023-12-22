<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Comment;

class Comment extends Model
{
    //
    use SoftDeletes;
    protected $table = 'comments';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function deleteChild()
    {
        $comment = Comment::whereNull('deleted_at')->where('parent_id',$this->id)->get();

        if(!empty($comment))
        {
            foreach($comment as $key=>$item)
            {
                $item->delete();
            }
        }
        return;
    }
}
