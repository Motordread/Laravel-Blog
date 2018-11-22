<?php namespace Fbf\LaravelBlog;

use App\Http\Controllers\Controller;
use Thujohn\Rss\RssFacade as Rss;

class PostsController extends Controller {

	/**
	 * @var \Fbf\LaravelBlog\Post
	 */
	protected $post;

	/**
	 * @param \Fbf\LaravelBlog\Post $post
	 */
	public function __construct(Post $post)
	{
		$this->post = $post;
	}

	/**
	 * @return mixed
	 */
	public function index()
	{
		// Get the selected posts
		$posts = $this->post->live()
			->orderBy($this->post->getTable().'.is_sticky', 'desc')
			->orderBy($this->post->getTable().'.published_date', 'desc')
			->paginate(config('laravel-blog.views.index_page.results_per_page'));

		// Get the archives data if the config says to show the archives on the index page
		if (config('laravel-blog.views.index_page.show_archives'))
		{
			$archives = $this->post->archives();
		}

		return \View::make(config('laravel-blog.views.index_page.view'), compact('posts', 'archives'));
	}

	/**
	 * @param $selectedYear
	 * @param $selectedMonth
	 * @return mixed
	 */
	public function indexByYearMonth($selectedYear, $selectedMonth)
	{
		// Get the selected posts
		$posts = $this->post->live()
			->byYearMonth($selectedYear, $selectedMonth)
			->orderBy($this->post->getTable().'.is_sticky', 'desc')
			->orderBy($this->post->getTable().'.published_date', 'desc')
			->paginate(config('laravel-blog.views.index_page.results_per_page'));

		// Get the archives data if the config says to show the archives on the index page
		if (config('laravel-blog.views.index_page.show_archives'))
		{
			$archives = $this->post->archives();
		}

		return \View::make(config('laravel-blog.views.index_page.view'), compact('posts', 'selectedYear', 'selectedMonth', 'archives'));
	}

	/**
	 * @param $relationshipIdentifier
	 * @return mixed
	 */
	public function indexByRelationship($relationshipIdentifier)
	{
		// Get the selected posts
		$posts = $this->post->live()
			->byRelationship($relationshipIdentifier)
			->orderBy($this->post->getTable().'.is_sticky', 'desc')
			->orderBy($this->post->getTable().'.published_date', 'desc')
			->paginate(config('laravel-blog.views.index_page.results_per_page'));

		// Get the archives data if the config says to show the archives on the index page
		if (config('laravel-blog.views.index_page.show_archives'))
		{
			$archives = $this->post->archives();
		}

		return \View::make(config('laravel-blog.views.index_page.view'), compact('posts', 'archives'));
	}

	/**
	 * @param $slug
	 * @return mixed
	 */
	public function view($slug)
	{
		// Get the selected post
		$post = $this->post->live()
			->where($this->post->getTable().'.slug', '=', $slug)
			->firstOrFail();

		// Get the next newest and next oldest post if the config says to show these links on the view page
		$newer = $older = false;
		if (config('laravel-blog.views.view_page.show_adjacent_items'))
		{
			$newer = $post->newer();
			$older = $post->older();
		}

		// Get the archives data if the config says to show the archives on the view page
		if (config('laravel-blog.views.view_page.show_archives'))
		{
			$archives = $this->post->archives();
		}

		return \View::make(config('laravel-blog.views.view_page.view'), compact('post', 'newer', 'older', 'archives'));

	}

	/**
	 * @return mixed
	 */
	public function rss()
	{
		$feed = Rss::feed('2.0', 'UTF-8');
		$feed->channel(array(
			'title' => config('laravel-blog.meta.rss_feed.title'),
			'description' => config('laravel-blog.meta.rss_feed.description'),
			'link' => \URL::current(),
		));
		$posts = $this->post->live()
			->where($this->post->getTable().'.in_rss', '=', true)
			->orderBy($this->post->getTable().'.published_date', 'desc')
			->take(10)
			->get();
		foreach ($posts as $post){
			$feed->item(array(
				'title' => $post->title,
				'description' => $post->summary,
				'link' => \URL::action('\Fbf\LaravelBlog\PostsController@view', array('slug' => $post->slug)),
			));
		}
		return \Response::make($feed, 200, array('Content-Type', 'application/rss+xml'));
	}

}
