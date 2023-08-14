<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id']; // Put all entries containing data into each column in the database table after create a post

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Reference the '$post' field containing the id account user (single-post.blade.php) to the users table database via the 'user_id' field
        // 'user_id = auth()->id() in storeNewPost function (PostController.php)
    }

}
