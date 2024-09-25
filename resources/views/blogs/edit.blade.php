@extends('layout')
@section('title', 'Edit Blog')

@section('content')
<div class="container">
    <h1>Edit Blog</h1>
    <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" id="title" value="{{ $blog->title }}" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" class="form-control" id="image" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ $blog->email }}" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" class="form-control" id="content" rows="5" required>{{ $blog->content }}</textarea>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select name="category" class="form-control" id="category">
                <option value="">Select Category</option>
                <option value="software" {{ $blog->category == 'software' ? 'selected' : '' }}>Software</option>
                <option value="seo" {{ $blog->category == 'seo' ? 'selected' : '' }}>SEO</option>
                <option value="testing" {{ $blog->category == 'testing' ? 'selected' : '' }}>Testing</option>
            </select>
            @if ($errors->has('category'))
                <div class="error">{{ $errors->first('category') }}</div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection