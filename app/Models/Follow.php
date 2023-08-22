<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public function userDoingTheFollowing() {
        return $this->belongsTo(User::class, 'user_id');
    } // 1 fan has '1 follow' with 1 idol

    public function userBeingFollowed() {
        return $this->belongsTo(User::class, 'followeduser');
    } // 1 idol has '1 follow' by 1 fan

}
