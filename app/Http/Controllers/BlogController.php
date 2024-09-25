<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class BlogController extends Controller
{

     
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::orderBy('id', 'desc')->get();
        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // dd('Create method accessed'); // This will help verify if the route is being hit
    return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image',
            'email' => 'required|email|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
        ]);

        $path = $request->file('image') ? $request->file('image')->store('images', 'public') : null;
        
        Blog::create([
            'title' => $request->title,
            'image' => $path,
            'email' => $request->email,
            'content' => $request->content,
            'category' => $request->category,
            'date' => now(),
            'author_name' => Auth::user()->name,
            'author_id' => Auth::id(),
        ]);

        return redirect('/blogs')->with('success','Blog is submitted');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {  

        // Fetch related blogs by category, excluding the current blog
            $relatedBlogs = Blog::where('category', $blog->category)
            ->where('id', '!=', $blog->id)
            ->take(3) // Limit the number of related blogs
            ->get();
                return view('blogs.show', compact('blog', 'relatedBlogs'));
            }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {

          // Check if the authenticated user is the owner of the blog
        if (Auth::id() != $blog->author_id) {
            return redirect()->route('blogs.index')->with('error', 'Unauthorized');
        }
        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $validatedData =  $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image',
            'email' => 'required|email|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
        ]);

        $originalData = $blog->only(['title', 'image', 'email', 'content', 'category']);
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($blog->image);
            $path = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $path;

        } else {
            // $path = $blog->image;
            $validatedData['image'] = $originalData['image'];

        }


        //    $blog->update([
        //     'title' => $request->title,
        //     'image' => $path,
        //     'email' => $request->email,
        //     'content' => $request->content,
        //     'category' => $request->category,
        // ]);

        

        // Update the blog post
    $blog->update($validatedData);

    // Compare original data with updated data
    $changes = [];
    foreach ($validatedData as $key => $value) {
        if ($originalData[$key] !== $value) {
            $changes[$key] = [
                'Change' => $value,
            ];
        }
    }

    // Log the changes if there are any
    if (!empty($changes)) {
        Log::info('Blog post updated', [
            'blog_id' => $blog->id,
            'author_id' => $blog->author_id,
            'author_name' => $blog->author_name,
            'changes' => $changes,
            'updated_at' => now(),
        ]);
    }

        return redirect('/blogs')->with('success', $request->title.' Blog is updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        Storage::disk('public')->delete($blog->image);
         // Check if the authenticated user is the owner of the blog
         if (Auth::id() != $blog->author_id) {
            return redirect()->route('blogs.index')->with('error', 'Unauthorized');
        }
        $blog->delete();
        return redirect('/blogs')->with('success', 'Blog is deleted');
    }


    // create a method to handle the export of blog data to a CSV file.
    public function export()
    {
        // $blogs = Blog::all();

       // Get the current user's ID
       $currentUserId = Auth::id();
       // Get the blogs created by the current user
       $blogs = Blog::where('author_id', $currentUserId)->get();

        $filename = "blogs.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['ID', 'Title','Image','Email', 'Content', 'Category', 'Author ID']);

        foreach($blogs as $blog) {
            fputcsv($handle, [
                $blog->id,
                $blog->title,
                $blog->image,
                $blog->email,
                $blog->content,
                $blog->category,
                $blog->author_id
            ]);
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    // create a method to handle the import of blog data from a CSV file.

    public function import(Request $request)
{
  // Validate that the uploaded file is either a CSV or TXT file
  $request->validate([
    'file' => 'required|mimes:csv'
], [
    'file.required' => 'Please upload a file.', 
    'file.mimes' => 'The file must be a CSV file.'
]);

// Store the uploaded file temporarily
$filename = $request->file('file')->store('temp');
$filepath = storage_path('app/' . $filename);

// Open the file for reading
if (($handle = fopen($filepath, 'r')) !== false) {
    // Read the CSV header row
    $header = fgetcsv($handle);
    $currentUserId = Auth::id();

    // Loop through each row of the CSV file
    while (($csvLine = fgetcsv($handle)) !== false) {
        $blogData = array_combine($header, $csvLine);

        // Ensure required fields are present
        if (!isset($blogData['Title'], $blogData['Email'], $blogData['Content'], $blogData['Category'])) {
            continue; // Skip this line if required fields are missing
        }

        // Handle image processing
        if (isset($blogData['Image']) && filter_var($blogData['Image'], FILTER_VALIDATE_URL)) {
            $imageUrl = $blogData['Image'];
            $headers = @get_headers($imageUrl);

            if ($headers && strpos($headers[0], '200')) {
                try {
                    $imageContent = file_get_contents($imageUrl);

                    // Generate a unique name for the image
                    $extension = pathinfo($imageUrl, PATHINFO_EXTENSION);
                    $imageName = uniqid('img_', true) . '.' . $extension;
                    $imagePath = 'images/' . $imageName;

                    // Save the image to the public directory
                    Storage::disk('public')->put($imagePath, $imageContent);
                    $blogData['Image'] = $imagePath;

                    Log::info("Image downloaded and saved as: " . $imagePath);
                } catch (\Exception $e) {
                    Log::error("Failed to download image: " . $imageUrl . " - Error: " . $e->getMessage());
                    $blogData['Image'] = 'images/default.png'; // Default image path
                }
            } else {
                Log::warning("Image URL not reachable: " . $imageUrl);
                $blogData['Image'] = 'images/default.png'; // Default image path
            }
        } else {
            $blogData['Image'] = 'images/default.png'; // Default image path
        }

        // Check if the blog ID exists and belongs to the current author
        $blogId = $csvLine[array_search('ID', $header)] ?? null;
        $existingBlog = Blog::where('id', $blogId)
                            ->where('author_id', $currentUserId)
                            ->first();

        if ($existingBlog) {
            // Update existing blog if it belongs to the current author
            $existingBlog->update([
                'title' => $blogData['Title'],
                'image' => $blogData['Image'],
                'email' => $blogData['Email'],
                'content' => $blogData['Content'],
                'category' => $blogData['Category'],
                'author_id' => $currentUserId,
                'author_name' => Auth::user()->name,
                'date' => now(),
            ]);
        } else {
            // Create a new blog
            Blog::create([
                'title' => $blogData['Title'],
                'image' => $blogData['Image'],
                'email' => $blogData['Email'],
                'content' => $blogData['Content'],
                'category' => $blogData['Category'],
                'author_id' => $currentUserId,
                'author_name' => Auth::user()->name,
                'date' => now(),
            ]);
        }
    }

    // Close the file
    fclose($handle);
}

// Delete the temporary file
Storage::delete($filename);

// Redirect back to the blogs page with a success message
return redirect('/blogs')->with('success', 'Blogs imported successfully');
}

 


}
