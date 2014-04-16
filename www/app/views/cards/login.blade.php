@extends('layout')

@section('content')
{{ Form::open(array('url'=>'login')) }}
<h1>Login</h1>
<p class="errors">
	{{ $errors->first('email') }}
	{{ $errors->first('password') }}
</p>

<p>
	{{ Form::label('email','Email Address') }}
	{{ Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com')) }}
</p>

<p>
	{{ Form::label('password', 'Password') }}
	{{ Form::password('password') }}
</p>

<p>{{ Form::submit("Submit") }}</p>
{{ Form::close() }}
@stop