@extends('layouts.app')

@section('title', 'Tambah Mobil')

@section('content')
<h3>Tambah Mobil</h3>

@include('mobil._form', ['mobil' => null])

@endsection
