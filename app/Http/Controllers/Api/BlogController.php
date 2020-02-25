<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{

    public function index(Request $request)
    {
        $size = empty($request->size) ? 1 : $request->size;
        $blogs = Blog::with(['author'])->orderBy('created_at', 'desc')->paginate($size);
        return $blogs;
    }
    
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'post'  => 'required|string',
            'image' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response(['msg' => $validator->errors()], 400);
        }

        $data = $validator->validated();
        $data['slug'] = Str::slug($data['title']);
        $data['author_id'] = $request->user()->id;

        $blog = Blog::create($data);
        return ['data' => $blog ];
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|integer|exists:blogs',
            'title' => 'required|string',
            'post'  => 'required|string',
            'image' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response(['msg' => $validator->errors()], 400);
        }

        $data = $validator->validated();
        $blog = Blog::find($request->id);

        $blog->update($data);
        return ['data' => $blog ];
    }

    public function show(Request $request)
    {
        if (empty($request->slug)) {
            return response(['msg' => ['Slug is missing']], 400);
        }
    
        //$validator = Validator::make($request->all(), [
        //    'slug' => 'required|string|exists:blogs'
        //]);

        //$data = $validator->validated();
        $blog = Blog::with(['author'])->where('slug', $request->slug)->first();
        return $blog;
    }

    public function delete(Request $request)
    {
        if (empty($request->id)) {
            return response(['msg' => ['ID is missing']], 400);
        }
        //$validator = Validator::make($request->all(), [
        //    'id' => 'required|integer|exists:blogs'
        //]);

        //$data = $validator->validated();
        Blog::destroy($request->id);
        return ['msg' => 'Blog deleted'];
    }
}
