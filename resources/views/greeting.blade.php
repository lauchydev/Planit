@extends('layouts.master')

@section('title')
  Result
@endsection

@section('content')
    <p>
    Hello {{$name}}
    Next year, you will be {{$age}} years old.
    </p>
@endsection