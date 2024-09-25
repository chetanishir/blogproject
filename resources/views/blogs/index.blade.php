@extends('layout')

@section('title', 'Blog List')

@section('content')
<div class="container mt-5">
    <h2>All Blogs</h2>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
    @foreach($blogs as $blog)
        <div class="card col-md-3 m-4">
            <a href="{{ route('blogs.show', $blog->id) }}">
            <img src="{{ Storage::url($blog->image) }}" class="img-thumbnail" alt="">
            <h2>{{ $blog->title }}</h2>
            <p>Author: {{ $blog->author_name }}</p>
            <p>Category: {{ $blog->category }}</p>
            <p>Date: {{ $blog->created_at->format('d M Y') }}</p>
            </a>
            <p>
              @if (Auth::id() == $blog->author_id)
                    <!-- Content visible only to the author of the blog -->
                   
                    <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    @endif
            </p>
            
            
        </div>
    @endforeach
        
    </div>
    @auth

    <div class="row">
    <div class="cardimport">
    <div class="col-md-12 mb-4">
    <a href="{{ route('blogs.export') }}" class="btn btn-success">Export Blogs</a>
    </div>
    </div> 
    </div>

    <div class="row">
    <div class="cardimport">
    <div class="col-md-12  mb-4">
    <h2>Import Blogs</h2>
    <form action="{{ route('blogs.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-primary">Import Blogs</button>
        @foreach ($errors->all() as $error)
                <ul> <li>{{ $error }}</li></ul>
            @endforeach
    </form>
    </div>
    </div>
    </div>
    @endauth
</div>
@endsection
