<?php
namespace Apps\Gallery\Model;

use Core\Lib\Amvc\Model;
use Core\Tools\SimpleImage\SimpleImage;

/**
 * Album model
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.d
 * @package App Gallery
 * @subpackage Model/Picture
 * @license MIT
 * @copyright 2014 by author
 */
final class PictureModel extends Model
{

	protected $tbl = 'app_gallery_pictures';

	protected $alias = 'pic';

	/**
	 * Returns a random picture of a specific album
	 *
	 * @param int $id_album Id of album
	 * @return Core\Lib\Data\Data
	 */
	public function getRndAlbumPicture($id_album)
	{
		// get random row
		$rand_row = $this->read([
			'type' => 'val',
			'field' => 'FLOOR(RAND() * COUNT(*)) AS rand_row',
			'filter' => 'pic.id_album=:id_album',
			'param' => [
				':id_album' => $id_album
			]
		]);

		return $this->read([
			'field' => [
				'pic.id_picture',
				'pic.title',
				'pic.description',
				'pic.id_member',
				'pic.picture',
				'CONCAT("' . $this->cfg('url_gallery_upload') . '", "/", album.dir_name, "/thumbs/", pic.picture) AS src'
			],
			'join' => [
				[
					'app_gallery_albums',
					'album',
					'INNER',
					'pic.id_album=album.id_album'
				]
			],
			'filter' => 'pic.id_album= :id_album',
			'param' => [
				':id_album' => $id_album
			],
			'limit' => [
				$rand_row,
				1
			]
		]);
	}

	/**
	 * Returns all pictures of a specific album
	 *
	 * @param int $id_album Id of album
	 * @return \Core\Lib\Data\Data
	 */
	public function getAlbumPictures($id_album)
	{
		return $this->read([
			'type' => '*',
			'field' => [
				'pic.id_picture',
				'pic.title',
				'pic.description',
				'pic.id_member',
				'pic.picture',
				'CONCAT("' . $this->cfg('url_gallery_upload') . '", "/", album.dir_name, "/thumbs/", pic.picture) AS src'
			],
			'join' => [
				[
					'app_gallery_albums',
					'album',
					'INNER',
					'pic.id_album=album.id_album'
				]
			],
			'filter' => 'pic.id_album = :id_album',
			'param' => [
				':id_album' => $id_album
			],
			'order' => 'pic.date_upload DESC'
		], 'processAlbumPicture');
	}

	protected function processAlbumPicture($picture)
	{
		// link to picture detail page
		$picture->url = $this->di['core.content.url']->compile('gallery_picture', [
			'id_picture' => $picture->id_picture
		]);

		if (! isset($picture->title)) {
			$picture->title = $this->txt('picture_without_title');
		}
		return $picture;
	}

	/**
	 * Returns a random picture from any album accessible to the user.
	 * Returns false when no picture was found.
	 *
	 * @return boolean \Core\Lib\Data\Data
	 */
	public function getRndPicture()
	{
		// Get IDs of gallery accessible for this user
		$albums = $this->getModel('Album')->getAlbumIDs();

		// No data means we can stop our work here.
		if (! $albums) {
			return false;
		}

		// Only pictures from galleries the user can access
		$rand_row = $this->read([
			'type' => 'val',
			'field' => [
				'FLOOR(RAND() * COUNT(*)) AS rand_row'
			],
			'filter' => 'pic.id_album IN (:albums)',
			'param' => [
				':albums' => $albums
			]
		]);

		// only one pic
		$this->read([
			'field' => [
				'pic.id_picture',
				'pic.title',
				'pic.description',
				'pic.id_member',
				'pic.picture',
				'CONCAT("' . $this->cfg('url_gallery_upload') . '", "/", album.dir_name, "/", pic.picture) AS src'
			],
			'join' => [
				[
					'app_gallery_albums',
					'album',
					'INNER',
					'pic.id_album=album.id_album'
				]
			],
			'filter' => 'pic.id_album IN (:albums)',
			'param' => [
				':albums' => $albums
			],
			'limit' => [
				$rand_row,
				1
			]
		]);

		// Add url for link to picture detail page
		$this['page'] = $this->di['core.content.url']->compile('gallery_picture', [
			'id_picture' => $this['id_picture']
		]);

		return $this->data;
	}

	/**
	 * Returns the data and correponding albuminformations of a specific picture
	 *
	 * @param int $id_picture
	 * @return \Core\Lib\Data\Data
	 */
	public function getPicture($id_picture)
	{
		// get picture
		$this->read([
			'field' => [
				'pic.*',
				'user.display_name AS owner',
				'CONCAT("' . $this->cfg('url_gallery_upload') . '", "/", album.dir_name, "/", pic.picture) AS src'
			],
			'join' => [
				[
					'users',
					'user',
					'LEFT',
					'user.id_user=pic.id_member'
				],
				[
					'app_gallery_albums',
					'album',
					'INNER',
					'pic.id_album=album.id_album'
				]
			],
			'filter' => 'pic.id_picture = :id_picture AND pic.id_album IN (' . implode(',', $this->getModel('Album')->getAlbumIDs()) . ')',
			'param' => [
				':id_picture' => $id_picture,
			]
		]);

		// get gallerydata
		$this['album'] = $this->getModel('Album')->getAlbumInfos($this['id_album']);
		$this['filesize'] = $this->di['core.io.file']->convFilesize($this['filesize']);

		return $this->data;
	}

	/**
	 * Deletes a specific picture
	 *
	 * @param int $id_picture
	 * @return boolean
	 */
	public function deletePicture($id_picture)
	{
		// load current picture data
		$this->find($id_picture);

		// if fitting accessrights or owner of picture
		if ($this->checkAccess('gallery_picture_delete') || $this['id_member'] == $this->di['core.user']->getId()) {
			$this->delete($id_picture);
			return true;
		}

		return false;
	}

	/**
	 * Deletes all pictures of a specific user.
	 * If user id is not sent, the id of the current user will be used.
	 *
	 * @param int $id_user Optional user id
	 * @return boolean
	 */
	function deleteAllPicturesOfUser($id_user = null)
	{
		// id_user as argument and allowed to delete gallery pictures?
		if (isset($id_user) && $this->checkAccess('gallery_picture_delete')) {
			$this->setFilter('id_member = :id_member');
			$this->setParameter(':id_member', $id_user);
			return true;
		}

		// no argument, delete the pictures of current user
		if (! isset($id_user)) {
			$this->setFilter('id_member = :id_member');
			$this->setParameter(':id_member', $this->di['core.user']->getId());
			return true;
		}

		return false;
	}

	/**
	 * Deletes all pictures of a specific album
	 *
	 * @param int $id_album Id of album to delete
	 */
	function deleteAlbumPictures($id_album)
	{
		$this->delete([
			'filter' => 'id_album=:id_album',
			'param' => [
				':id_album' => $id_album
			]
		]);
	}

	/**
	 * Saves uploaded image and creates record in picture table.
	 *
	 * @param \Core\Lib\Data\Data $data Data to save
	 * @param int $id_album id of album the picture is from
	 */
	public function saveUploadedPicture($data, $id_album)
	{
		$fileio = $this->di['core.io.file'];

		// Get uploaded picture data
		$uploads = $fileio->getUploads();

		// Handle upload errors
		if ($uploads['error'] !== 0) {
			// General upload error
			$this->addError('@', $this->txt('upload_error_' . $uploads['error']));
			return;
		}

		// Load album infos
		$album = $this->getModel('Album')->getAlbum($id_album);

		// Mime type not allowed
		if (! isset($album->mime_types{$uploads['type']})) {
			$this->addError('@', $this->txt('upload_mime_type_not_allowed'));
		}

		// Uploadsize than file size set in config
		if ($uploads['size'] > $fileio->getMaximumFileUploadSize()) {
			$this->addError('@', $this->txt('upload_error_filesize'));
		}

		// End here on errors.
		if ($this->hasErrors()) {
			return;
		}

		// # First error check passed. Go on with data processing...

		// Set posted data to model
		$this->data = $data;

		// Add album id
		$this['id_album'] = $id_album;

		// Get album path
		$album_path = $this->getModel('Album')->getAlbumPath($id_album);

		// Cleanup filename
		$img_filename = $fileio->cleanFilename($uploads['name']);

		// We need the pure image name and later the extension
		list ($img_name, $extension) = explode('.', $img_filename);

		// This date is uesed for image filename encoding and late on for the
		// gallery record in db
		$date_upload = time();

		// Create a unique picture id by md5()ing the combined image filename, user id and uploaddate
		$uniqe_id = md5($img_filename . $this->di['core.user']->getId() . $date_upload);

		// Images ares stroed with the code from above to prevent duplicate filenames
		$img_filename = $img_name . '-' . $uniqe_id . '.' . $extension;

		// Full imagae path for moving the uploaded file
		$img_path = $album_path . '/' . $img_filename;

		// Move the tmp image tho the gallery by checking set overwrite config
		$move_ok = $fileio->moveUploadedFile($uploads['tmp_name'], $img_path);

		// Was the file moved without errors?
		if (! $move_ok) {
			$this->addError('@', sprintf($this->txt('move_upload_failed'), $img_path));
			return;
		}

		// If this check fails, the file will be deleted and an error added to the model
		try {
			$img = new SimpleImage($img_path);
		}
		catch (\Exception $e) {
			// Damn bastard user!
			unlink($img_path);

			$this->addError('@', sprintf($this->txt('upload_is_no_image'), $img_path));
			return;
		}

		// # Reaching this point means we have no errors and the uploaded file
		// # is placed in the albums directory.

		// Should we create a thumbnail?
		if ($this->cfg('thumbnail_use')) {
			$thumb_path = $album_path . '/thumbs/' . $img_filename;
			$img->fit_to_width($this->cfg('thumbnail_width'))
				->save($thumb_path, $this->cfg('thumbnail_quality'));
			$this['thumb'] = 1;
		}

		// Users don't need to set a title on upload. When title is empty and
		// no empty titles are allowed by config the image name will be used instead.
		if (empty($this['title']) && ! $this->cfg('empty_title')) {
			$this['title'] = $img_name;
		}

		$this['unique_id'] = $uniqe_id;
		$this['filesize'] = $uploads['size'];
		$this['picture'] = $img_filename;
		$this['type'] = $uploads['type'];
		$this['id_member'] = $this->di['core.user']->getId();
		$this['date_upload'] = $date_upload;
		$this['date_update'] = $date_upload;

		// Get some infos about the image
		$img_size = getimagesize($img_path);

		$this['width'] = $img_size[0];
		$this['height'] = $img_size[1];

		// Save the image data whithout further validation
		$this->save(false);
	}
}
