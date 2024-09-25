@extends('layout')
@section('title', $blog->title)

@section('content')
<div class="container">
    <h1>{{ $blog->title }}</h1>
    <img src="{{ Storage::url($blog->image) }}" alt="{{ $blog->title }}" class="img-fluid">

    <p><strong>Email:</strong> {{ $blog->email }}</p>
    <p><strong>Category:</strong> {{ $blog->category }}</p>
    <p>{{ $blog->content }}</p>
    <p><strong>Author:</strong> {{ $blog->author_name }}</p>
    <p><strong>Date:</strong> {{ $blog->created_at->format('d M Y') }}</p>
</div>

<div class="container">

    <div class="row">
        @if($relatedBlogs->count() > 0)
        <div class="col-md-12"> <h3>Related Blogs</h3> </div>
        @endif
        @foreach($relatedBlogs as $relatedBlog)
        <div class="card col-md-3">
            <a href="{{ route('blogs.show', $relatedBlog->id) }}">
            <img src="{{ Storage::url($relatedBlog->image) }}" class="img-thumbnail" alt="">
            <h2>{{ $relatedBlog->title }}</h2>
            <p>Author: {{ $relatedBlog->author_name }}</p>
            <p>Category: {{ $relatedBlog->category }}</p>
            <p>Date: {{ $relatedBlog->created_at->format('d M Y') }}</p>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
 
