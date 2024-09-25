@extends('layout')
 @section('title','Register')
 @section('content')
 <div class="row">
 <div class="col-md-4 offset-md-4">
 <form action="{{ route('register') }}" method="POST" class="customform">
    @csrf
    <div class="form-group mb-3">
    <label for="name">Name</label>
    <input type="text" class="form-control" value="{{ old('name') }}" name="name"  id="name">
    @if ($errors->has('name'))
                <div class="error">{{ $errors->first('name') }}</div>
            @endif
    </div>
    <div class="form-group  mb-3">
    <label for="email">Email</label>
    <input type="email" class="form-control" value="{{ old('email') }}" name="email" id="email">
    @if ($errors->has('email'))
                <div class="error">{{ $errors->first('email') }}</div>
            @endif
    </div>
    <div class="form-group  mb-3">
    <label for="password">Password</label>
    <input type="password" class="form-control" name="password" id="password">
    @if ($errors->has('password'))
    <div class="error">{{ $errors->first('password') }}</div>
@endif
    </div>
    <div class="form-group  mb-3">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
        @if ($errors->has('password_confirmation'))
        <div class="error">{{ $errors->first('password_confirmation') }}</div>
    @endif
        </div>
    <button type="submit" class="btn btn-primary">Register</button>
    <br>
         <br>
        <a href="{{ route('login') }}">Login</a>
</form>
 </div> 
 </div>
 @endsection