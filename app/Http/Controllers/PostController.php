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
        'code_titles' => 'nullable|array',
        'codes' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $data = $request->only(['title', 'description']);

    $data['code_titles'] = $request->code_titles ?? [];
    $data['codes'] = $request->codes ?? [];

    // Image Upload
    $uploadedImages = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('uploads', 'public');
            $uploadedImages[] = $path;
        }
    }
    $data['images'] = $uploadedImages;

    Post::create($data);

    return redirect()->route('posts.index')->with('success', 'Post created successfully.');
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
        'code_titles' => 'nullable|array',
        'codes' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'remove_images' => 'nullable|array',
    ]);

    $data = $request->only(['title', 'description']);

    $data['code_titles'] = $request->code_titles ?? [];
    $data['codes'] = $request->codes ?? [];

    // Remove old images
    $existingImages = $post->images ?? [];
    if ($request->has('remove_images')) {
        foreach ($request->remove_images as $removePath) {
            if (Storage::disk('public')->exists($removePath)) {
                Storage::disk('public')->delete($removePath);
            }
            $existingImages = array_filter($existingImages, fn($img) => $img !== $removePath);
        }
    }

    // Upload new images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('uploads', 'public');
            $existingImages[] = $path;
        }
    }

    $data['images'] = array_values($existingImages); // reindex

    $post->update($data);

    return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
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
