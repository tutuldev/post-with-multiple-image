<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'codes.*' => 'nullable|string',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $imagePaths = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $imagePaths[] = $image->store('uploads', 'public');
        }
    }

    Post::create([
        'title' => $request->title,
        'description' => $request->description,
        'codes' => $request->codes,
        'images' => $imagePaths,
    ]);

    return redirect()->route('posts.index')->with('success', 'Post created');
}


    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

public function update(Request $request, Post $post)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'codes.*' => 'nullable|string',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $imagePaths = $post->images ?? [];

    foreach ($request->input('remove_images', []) as $removePath) {
        if (\Storage::disk('public')->exists($removePath)) {
            \Storage::disk('public')->delete($removePath);
        }
        $imagePaths = array_filter($imagePaths, fn($img) => $img !== $removePath);
    }

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $imagePaths[] = $image->store('uploads', 'public');
        }
    }

    $post->update([
        'title' => $request->title,
        'description' => $request->description,
        'codes' => $request->codes,
        'images' => array_values($imagePaths),
    ]);

    return redirect()->route('posts.index')->with('success', 'Post updated');
}




 public function destroy(Post $post)
{
    // সব ইমেজ ডিলিট করো
    if ($post->images && is_array($post->images)) {
        foreach ($post->images as $image) {
            if (\Storage::disk('public')->exists($image)) {
                \Storage::disk('public')->delete($image);
            }
        }
    }

    $post->delete();

    return redirect()->route('posts.index')->with('success', 'Post deleted successfully');
}

}
