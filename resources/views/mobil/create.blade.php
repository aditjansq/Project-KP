@extends('layouts.app')

@section('title', 'Tambah Mobil')

@section('content')
    @include('mobil._form', ['mobil' => null])
@endsection
