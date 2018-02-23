<?php

// Main default listing e.g. http://domain.com/blog
Route::get(config('laravel-blog.routes.base_uri'), 'Fbf\LaravelBlog\PostsController@index');

// Archive (year / month) filtered listing e.g. http://domain.com/blog/yyyy/mm
Route::get(config('laravel-blog.routes.base_uri').'/{year}/{month}', 'Fbf\LaravelBlog\PostsController@indexByYearMonth')->where(array('year' => '\d{4}', 'month' => '\d{2}'));

if (config('laravel-blog.routes.relationship_uri_prefix'))
{
	// Relationship filtered listing, e.g. by category or tag, e.g. http://domain.com/blog/category/my-category
	Route::get(config('laravel-blog.routes.base_uri').'/'.config('laravel-blog.routes.relationship_uri_prefix').'/{relationshipIdentifier}', 'Fbf\LaravelBlog\PostsController@indexByRelationship');
}

// Blog post detail page e.g. http://domain.com/blog/my-post
Route::get(config('laravel-blog.routes.base_uri').'/{slug}', 'Fbf\LaravelBlog\PostsController@view');

// RSS feed URL e.g. http://domain.com/blog.rss
Route::get(config('laravel-blog.routes.base_uri').'.rss', 'Fbf\LaravelBlog\PostsController@rss');