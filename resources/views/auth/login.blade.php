@extends('layout')
 @section('title','Login')
 @section('content')

 <div class="row">
    <div class="col-md-4 offset-md-4">
    <form action="{{ route('login') }}" method="POST" class="customform">
    @csrf
    <div class="form-group mb-3">
    <label for="email">Email</label>
    <input type="email" name="email" class="form-control" id="email">
            @if ($errors->has('email'))
            <div class="error">{{ $errors->first('email') }}</div>
        @endif
    </div>
    <div class="form-group mb-3">
    <label for="password">Password</label>
    <input type="password" name="password"  class="form-control" id="password">
    @if ($errors->has('password'))
    <div class="error">{{ $errors->first('password') }}</div>
    @endif
    </div>
    <div class="form-group mb-3">
        <button type="submit" class="btn btn-primary">Login</button>
         <br>
         <br>
        <a href="{{ route('register') }}">Registration</a>
 
    </div>
</form>
    </div>
 </div>

 @endsection