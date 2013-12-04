@if (!$posts->isEmpty())

	@foreach ($posts as $post)

		<div class="post">

			<h2>
				<a href="{{ $post->getUrl() }}">
					{{ $post->title }}
				</a>
			</h2>

			<p class="date">
				{{ date(Config::get('laravel-blog::published_date_format'), strtotime($post->published_date)) }}
			</p>

			@if (!empty($post->you_tube_video_id))
				<div class="posts-index-youtube-thumb">
				{{
					str_replace('%YOU_TUBE_VIDEO_ID%', $post->you_tube_video_id,
						Config::get('laravel-blog::you_tube_thumbnail_code'))
				}}
				</div>
			@elseif (!empty($post->image))
				{{ $post->getThumbnailImage() }}
			@endif

			<p>{{ $post->summary }}</p>

		</div>

	@endforeach

	{{ $posts->links() }}

@else

	<p>
		There aren't any posts at the moment.
	</p>

@endif