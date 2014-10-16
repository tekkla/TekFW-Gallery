<?php
namespace Apps\Gallery;

use Core\Lib\Amvc\App;

/**
 * Gallery app
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.d
 * @package App
 * @subpackage Gallery
 * @license MIT
 * @copyright 2014 by author
 */
class Gallery extends App
{

	// App uses language files
	protected $language = true;

	// App has a seperate css file
	protected $css_file = true;

	// App config
	protected $config = [
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

	protected $routes = [
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

	protected $permissions = [
		'manage_album',
		'manage_image',
		'allow_upload'
	];

	protected function addPaths()
	{
		// fileupload dir and url
		$this->cfg('dir_gallery_upload', BASEDIR . $this->cfg('path'));
		$this->cfg('url_gallery_upload', BASEURL . $this->cfg('path'));
	}

	public function onBefore()
	{
		return '<div id="gallery">';
	}

	public function onAfter()
	{
		return '</div>';
	}

	/*
	 * Creates the arrayelements of Raidmanager menu.
	 */
	public function addMenuButtons(&$menu_buttons)
	{
		// Load the list of accessible albums
		$album_list = $this->getModel('Album')->getAlbumList();

		$gallery_menu_buttons = [];

		if ($album_list) {
			$gallery_album_buttons = [];

			foreach ($album_list as $album) {
				$gallery_album_buttons['gallery_' . FileIO::cleanFilename($album->title)] = [
					'title' => $album->title,
					'href' => Url::factory('gallery_album_album', [
						'id_album' => $album->id_album
					])->getUrl(),
					'show' => true
				];
			}

			$gallery_menu_buttons['gallery_album_list'] = [
				'title' => $this->txt('album'),
				'href' => Url::factory('gallery_album_index')->getUrl(),
				'show' => true,
				'sub_buttons' => $gallery_album_buttons
			];
		}

		$gallery_menu_buttons['gallery_upload'] = [
			'title' => $this->txt('upload'),
			'href' => Url::factory('gallery_picture_upload')->getUrl(),
			'show' => $this->checkAccess('allow_upload')
		];
		$gallery_menu_buttons['gallery_new'] = [
			'title' => $this->txt('album_new'),
			'href' => Url::factory('gallery_album_new')->getUrl(),
			'show' => $this->checkAccess('gallery_manage_album')
		];
		$gallery_menu_buttons['gallery_config'] = [
			'title' => $this->txt('config'),
			'href' => Url::factory('admin_app_config', [
				'app_name' => 'gallery'
			])->getUrl(),
			'show' => $this->checkAccess('gallery_manage_album')
		];

		$menu_buttons['gallery'] = [
			'title' => $this->txt('headline'),
			'href' => Url::factory('gallery_album_index')->getUrl(),
			'show' => ! empty($album_list) || $this->checkAccess('gallery_manage_album'),
			'sub_buttons' => $gallery_menu_buttons
		];
	}

	/**
	 * Writes membername to the picture and gallery tables of the member to delete
	 *
	 * @param int $id_member
	 */
	public function onMemberDelete($id_member)
	{
		// Get the member name by member id
		$model = App::getInstance('forum')->getModel('members');
		$model->find($id_member, [
			'member_name',
			'real_name'
		]);
		$member_name = $member->real_name ? $member->real_name : $member->member_name;

		// Create Gallery app
		$app = App::create('Gallery');

		// Update the albums of this member
		$model = $app->getModel('Album');
		$model->setField('member_name');
		$model->setFilter('id_member={int:id_member}');
		$model->setParameter([
			'membername' => $member_name,
			'id_member' => $id_member
		]);
		$model->update();

		// And then the pictures
		$model = $app->getModel('Picture');
		$model->setField('member_name');
		$model->setFilter('id_member={int:id_member}');
		$model->setParameter([
			'membername' => $member_name,
			'id_member' => $id_member
		]);
		$model->update();
	}
}
