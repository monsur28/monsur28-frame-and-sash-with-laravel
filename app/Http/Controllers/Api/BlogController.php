<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    // Display a listing of blogs
    public function index()
    {
        $blogs = Blog::all();
        return response()->json($blogs, 200);
    }

    // Store a newly created blog
    public function store(Request $request)
    {
        $validated = $request->validate([
            'blog_title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'blog_content' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|in:draft,published',
        ]);

        $blog = Blog::create($validated);

        return response()->json(['message' => 'Blog created successfully', 'blog' => $blog], 201);
    }

    // Display a specific blog
    public function show($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        return response()->json($blog, 200);
    }

    // Update a specific blog
    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $validated = $request->validate([
            'blog_title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:100',
            'blog_content' => 'sometimes|string',
            'date' => 'sometimes|date',
            'status' => 'sometimes|in:draft,published',
        ]);

        $blog->update($validated);

        return response()->json(['message' => 'Blog updated successfully', 'blog' => $blog], 200);
    }

    // Remove a specific blog
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully'], 200);
    }
}
