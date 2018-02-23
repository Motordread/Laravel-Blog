@extends('layouts.master')

@section('title')
	{{ config('laravel-blog::meta.index_page.page_title') }}
@endsection

@section('meta_description')
	{{ config('laravel-blog::meta.index_page.meta_description') }}
@endsection

@section('meta_keywords')
	{{ config('laravel-blog::meta.index_page.meta_keywords') }}
@endsection

@section('content')
	@include('laravel-blog::partials.list')
	@include('laravel-blog::partials.archives')
@stop