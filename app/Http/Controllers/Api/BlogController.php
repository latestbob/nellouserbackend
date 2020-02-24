<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;

class BlogController extends Controller
{

    public function index(Request $request)
    {
        $blogs = Blog::with(['author'])->orderBy('created_at', 'desc')->paginate();
        return $blogs;
    }
    
    public function create(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'post'  => 'required|string',
            'image' => 'nullable|string'
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['author_id'] = $request->user()->id;

        $blog = Blog::create($data);
        return $blog;
    }


    public function update(Request $request)
    {
        $data = $request->validate([
            'id'    => 'required|integer|exists:blogs',
            'title' => 'required|string',
            'post'  => 'required|string',
            'image' => 'nullable|string'
        ]);
        
        $blog = Blog::find($request->id);

        $blog->update($data);
        return $blog;
    }

    public function show(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required|string|exists:blogs'
        ]);

        $blog = Blog::where('slug', $data['slug'])->first();
        return $blog;
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required|string|exists:blogs'
        ]);

        Blog::where('slug', $data['slug'])->delete();
        return ['msg' => 'Blog deleted'];
    }
}
