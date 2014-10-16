<?php
if (! defined('TEKFW'))
	die('Can not run without CoreFramework');

return [
	[
		'name' => 'album_index',
		'route' => '/',
		'ctrl' => 'album',
		'action' => 'gallery'
	],
	[
		'name' => 'album_album',
		'route' => '/[i:id_album]',
		'ctrl' => 'album',
		'action' => 'index'
	],
	[
		'name' => 'album_new',
		'method' => 'GET|POST',
		'route' => '/new',
		'ctrl' => 'album',
		'action' => 'edit'
	],
	[
		'name' => 'album_edit',
		'method' => 'GET|POST',
		'route' => '/[i:id_album]/edit',
		'ctrl' => 'album',
		'action' => 'edit'
	],
	[
		'name' => 'album_delete',
		'method' => 'GET',
		'route' => '[i:id_album]/delete',
		'ctrl' => 'album',
		'action' => 'delete'
	],
	[
		'name' => 'picture',
		'route' => '/picture/[i:id_picture]',
		'ctrl' => 'picture',
		'action' => 'index'
	],
	[
		'name' => 'picture_edit',
		'route' => '/picture/[i:id_picture]/edit',
		'ctrl' => 'picture',
		'action' => 'edit'
	],
	[
		'name' => 'picture_random',
		'route' => '/picture/random',
		'ctrl' => 'picture',
		'action' => 'random'
	],
	[
		'name' => 'picture_upload',
		'method' => 'GET|POST',
		'route' => '/upload/[i:id_album]?',
		'ctrl' => 'picture',
		'action' => 'upload'
	]
];