@extends('layouts.app')

@section('title', 'Edit Mobil')

@section('content')
@php
    // Define $job safely for use in this view and included partials
    $job = strtolower(optional(auth()->user())->job ?? '');
@endphp

<h3>Edit Mobil</h3>

{{-- Pastikan _form.blade.php siap menerima $mobil dan $job --}}
@include('mobil._form', ['mobil' => $mobil, 'job' => $job])

@endsection
