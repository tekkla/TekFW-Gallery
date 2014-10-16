<?php
namespace Apps\Gallery\Controller;

use Core\Lib\Amvc\Controller;
use Core\Lib\Content\Html\Controls\ButtonGroup;
use Core\Lib\Content\Html\Controls\UiButton;

/**
 * Album controller
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.d
 * @package App Gallery
 * @subpackage Controller/Album
 * @license MIT
 * @copyright 2014 by author
 */
final class AlbumController extends Controller
{

	public function Edit($id_album = null)
	{
		$post = $this->request->getPost();

		if ($post) {

			$this->model->saveAlbum($post);

			// save errors?
			if (! $this->model->hasErrors()) {

				// go to action set by model save action
				$this->message->success($this->txt('album_config_saved'));

				$url = $this->url->compile($this->request->getCurrentRoute(), [
					'id_album' => $this->model['id_album']
				]);

				$this->redirectExit($url);
			}
		}

		// ---------------------------------------
		// DATA
		// ---------------------------------------

		// load it only if the is no data present
		if (! $this->model->hasData()) {
			$this->model->getEdit($id_album);
		}

		// No model data means the user has no accessright to edit/add albums
		if (! $this->model->hasData()) {
			if (isset($id_album)) {
				$url = $this->url->compile('gallery_album_album', [
					'id_album' => $id_album
				]);
			}
			else {
				$url = $this->url->compile('gallery_album_index');
			}

			$this->redirectExit($url);
		}

		// ------------------------------
		// TEXT
		// ------------------------------
		$this->setVar([
			'title' => $this->txt('album_' . $this->model['mode']),
			'headline' => $this->txt('headline')
		]);

		// ---------------------------
		// FORM
		// ---------------------------

		// create form object
		$form = $this->getFormDesigner();

		// hidden raid id field only on edit
		if (isset($id_album)) {
			$form->createElement('hidden', 'id_album');
		}

		$form->createElement('hidden', 'mode');

		// album infos
		$form->openGroup('album_infos');

		if ($id_album === null) {
			$form->createElement('h3', $this->txt('album_headline_info'));
			$form->createElement('text', 'title');
		}
		else {
			$form->createElement('h2', '<small>' . $this->txt('album') . '</small><br>' . $this->model['title']);
		}

		$form->createElement('textarea', 'description');
		$form->createElement('text', 'category');
		$form->createElement('text', 'tags');
		$form->createElement('textarea', 'notes');
		$form->createElement('textarea', 'legalinfo');

		// accesrights
		$form->openGroup('album_upload');

		$form->createElement('h3', $this->txt('album_headline_upload'));

		// Get mimetypes from app cfg
		$mime = $this->cfg('upload_mime_types');

		// If we got no mime types, show info that upload is disabled by app config
		if (! $mime) {
			$control = $form->createElement('p', $this->txt('album_upload_not_active'));
			$control->addCss('text-danger');
		}
		else {
			$control = $form->createElement('optiongroup', 'mime_types');

			foreach ($mime as $mime_type) {
				/* @var $option \Core\Lib\Content\Html\Form\Option */
				$option = $control->createOption();

				$option->setValue($mime_type);
				$option->setInner($mime_type);

				if (isset($this->model['mime_types']->{$mime_type})) {
					$option->isSelected(1);
				}
			}

			$control->setDescription($this->txt('mime_type_help'));
		}

		// accesrights
		$form->openGroup('album_access');

		$form->createElement('h3', $this->txt('album_headline_access'));

		// we need the membergroups
		$membergroups = $this->security->getGroups();

		// Create an optiongroup
		$control = $form->createElement('optiongroup', 'accessgroups');

		$control->addCss('col-sm-6');

		// Add accessgroups description
		$control->setDescription($this->txt('accessgroups_help'));

		// Add membergroups as options
		foreach ($membergroups as $id_group => $group_name) {

			/* @var $option \Core\Lib\Content\Html\Form\Option */
			$option = $control->createOption();

			$option->setValue($id_group);
			$option->setInner($group_name);

			if (isset($this->model['accessgroups']->{$id_group})) {
				$option->isSelected(1);
			}
		}

		// buttons
		/* @var $control-> Core\Lib\Content\Html\Controls\Optiongroup */
		$control = $form->createElement('optiongroup', 'uploadgroups');

		// Add uploadgroups description
		$control->setDescription($this->txt('uploadgroups_help'));

		$control->addCss('col-sm-6');

		foreach ($membergroups as $id_group => $group_name) {

			/* @var $option Core\Lib\Content\Html\Form\Option */
			$option = $control->createOption();

			$option->setValue($id_group);
			$option->setInner($group_name);

			if (isset($this->model['uploadgroups']->{$id_group})) {
				$option->isSelected(1);
			}
		}

		// options
		$form->openGroup('album_options');

		$form->createElement('h3', $this->txt('album_headline_options'));

		$form->createElement('switch', 'scoring');

		$form->createElement('switch', 'anonymous');

		$form->createElement('number', 'img_per_user')->addAttribute('min', 0);

		$this->setVar('form', $form);

		// puiblish data to view
		$this->setVar('edit', $this->model);
	}

	public function Index($id_album)
	{
		$album = $this->model->getAlbum($id_album);

		if (! $album) {
			$this->redirectExit($this->url->compile('gallery_album_index'));
		}

		$this->setPageTitle($this->txt('headline') . ' - ' . $album->title);
		$this->setPageDescription($album->description);

		$this->setVar([
			'headline' => $this->txt('headline'),
			'nopics' => $this->txt('album_nopics'),
			'grid' => $this->cfg('grid'),
			'album' => $album
		]);

		if ($album->allow_upload || $album->allow_edit) {

			$button_group = $this->html->create('Controls\ButtonGroup');

			$button_group->addCss('pull-right');

			$args = [
				'id_album' => $id_album
			];

			if ($album->allow_upload && isset($this->model['mime_types'])) {

				$button = $this->html->create('Controls\UiButton');

				$button->setUrl($this->url->compile('gallery_picture_upload', $args));
				$button->setIcon('upload');
				$button->setText($this->txt('picture_upload'));

				$button_group->addButton($button);
			}

			if ($album->allow_edit) {
				$button = $this->html->create('Controls\UiButton');

				$button->setUrl($this->url->compile('gallery_album_edit', $args))
					->setIcon('edit')
					->setText($this->txt('album_edit'));

				$button_group->addButton($button);

				$button = $this->html->create('Controls\UiButton');

				$button->setUrl($this->url->compile('gallery_album_delete', $args));
				$button->setIcon('trash-o');
				$button->setText($this->txt('album_delete'));
				$button->setConfirm($this->txt('album_delete'));
				$button->addCss('pull-right');

				$button_group->addButton($button);
			}

			$this->setVar('buttons', $button_group);
		}

		// Add gallery link to linktree
		$this->addLinktree($this->txt('headline'), $this->url->compile('gallery_album_index'));

		// Add current album to linktree
		$this->addLinktree($album->title);
	}

	public function Gallery()
	{
		$albums = $this->model->getAlbums();

		if (! $albums)
			return false;

		$this->page->setTitle($this->txt('headline'));
		$this->page->setDescription($this->txt('description'));

		$this->setVar([
			'headline' => $this->txt('headline'),
			'intro' => $this->txt('intro'),
			'legal' => $this->txt('legal'),
			'albums' => $albums,
			'nopics' => $this->txt('album_nopics'),
			'grid' => $this->cfg('grid')
		]);

		// Create add gallery buttons for gallery admins
		if ($this->checkAccess('gallery_admin')) {
			$button = $this->html->create('Controls\UiButton');

			$button->setUrl($this->url->compile('gallery_album_new'));
			$button->setIcon('plus');
			$button->setText($this->txt('album_new'));
			$button->addCss('pull-right');

			$this->setVar('btn_add', $button);
		}

		// Add gallery link to linktree
		$this->addLinktree($this->txt('headline'));
	}

	public function Convert()
	{
		$this->model->convertAlbums();
	}

	public function Delete($id_album)
	{
		$this->model->deleteAlbum($id_album);

		if ($this->model->hasErrors()) {
			$this->addMessage('Delete error', 'error');
			$this->redirectExit($this->url->compile('gallery_album_album', [
				'id_album' => $id_album
			]));
		}
		else {
			$this->addMessage('Delete success', 'success');
			$this->redirectExit($this->url->compile('gallery_album_index'));
		}
	}
}
