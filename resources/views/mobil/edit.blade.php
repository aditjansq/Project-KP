@extends('layouts.app')

@section('title', 'Edit Mobil')

@section('content')
<h3>Edit Mobil</h3>

@include('mobil._form', ['mobil' => $mobil])

@endsection
