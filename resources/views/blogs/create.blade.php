@extends('layout')
 @section('title','Blog Add')
 @section('content')
<div class="container">
    <h1>Create Blog</h1>
    <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" id="title">
            @if ($errors->has('title'))
            <div class="error">{{ $errors->first('title') }}</div>
        @endif
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" class="form-control" id="image">
            @if ($errors->has('image'))
            <div class="error">{{ $errors->first('image') }}</div>
        @endif
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email">
            @if ($errors->has('email'))
            <div class="error">{{ $errors->first('email') }}</div>
        @endif
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" class="form-control" id="content" rows="5"></textarea>
            @if ($errors->has('content'))
            <div class="error">{{ $errors->first('content') }}</div>
        @endif
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Category</label>
            <select name="category" class="form-control" id="category">
                <option value="">Select Category</option>
                <option value="software">Software</option>
                <option value="seo">SEO</option>
                <option value="testing">Testing</option>
            </select>
            @if ($errors->has('category'))
            <div class="error">{{ $errors->first('category') }}</div>
        @endif
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
