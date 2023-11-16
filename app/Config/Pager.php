<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
	/*
	|--------------------------------------------------------------------------
	| Templates
	|--------------------------------------------------------------------------
	|
	| Pagination links are rendered out using views to configure their
	| appearance. This array contains aliases and the view names to
	| use when rendering the links.
	|
	| Within each view, the Pager object will be available as $pager,
	| and the desired group as $pagerGroup;
	|
	*/
	public $templates = [
		'default_full'   => 'App\Views\Pagers\foundation_full',
		'default_simple' => 'App\Views\Pagers\foundation_full',
		'default_head'   => 'App\Views\Pagers\foundation_full',
		'front_full'     => 'App\Views\Pagers\foundation_full',

	];

	/*
	|--------------------------------------------------------------------------
	| Items Per Page
	|--------------------------------------------------------------------------
	|
	| The default number of results shown in a single page.
	|
	*/
	public $perPage = 20;
}
