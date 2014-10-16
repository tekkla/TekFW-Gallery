<?php
if (!defined('TEKFW'))
	die('Can not run without CoreFramework');

return [
	'grid' => [
		'group' => 'display',
		'control' => 'select',
		'data' => [
			'array',
			[
				2,
				3,
				4,
				6,
				12
			],
			1
		],
		'default' => 4
	],
	'thumbnail_use' => [
		'group' => 'thumbnail',
		'control' => 'switch',
		'default' => 1
	],
	'thumbnail_width' => [
		'group' => 'thumbnail',
		'control' => 'number',
		'default' => 640
	],
	'thumbnail_quality' => [
		'group' => 'thumbnail',
		'control' => [
			'number',
			[
				'min' => 1,
				'max' => 100
			]
		],
		'default' => 80
	],
	'path' => [
		'group' => 'upload',
		'control' => 'input',
		'default' => '/Uploads/images/Gallery'
	],
	'upload_mime_types' => [
		'group' => 'upload',
		'control' => 'optiongroup',
		'data' => [
			'array',
			[
				'image/gif',
				'image/jpeg',
				'image/png',
				'image/bmp'
			],
			1
		]
	]
];