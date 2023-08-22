<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // actuallyUpdate: Update post
    public function actuallyUpdate(Post $post, Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']); // strip_tags will return a string delimited from HTML or PHP tags
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);

        return back()->with('success', 'Post successfully update.');
    }

    // showEditForm: final update post
    public function showEditForm(Post $post) {
        return view('edit-post', ['post' => $post]);
    }

    // delete: delete post
    public function delete(Post $post) {
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post successfully deleted.');
    }

    // viewSinglePost
    public function viewSinglePost(Post $post) // $post contains the id value for each post created and must match the incoming variable from route. Power of model in Laravel
    {
        // $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h3><br>'); // https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet
        // Str::markdown: present HTML and escape malicious code
        return view('single-post', ['post' => $post]); // After processing the "$post" data, it will convert the data to the variable name 'post'
    }

    // storeNewPost: save post
    public function storeNewPost(Request $request) // create a post
    {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id(); // id of the current user account and assign this id value to $incomingFields['user_id']

        // $incomingFields variable is holding data in array
        $newPost = Post::create($incomingFields); // Save entry to database. Post.php(Model) -> $fillable
        
        return redirect("/post/{$newPost->id}")->with('success', 'New post successfully created.');
    }

    // showCreateForm
    public function showCreateForm()
    {
        return view('create-post');
    }
}
