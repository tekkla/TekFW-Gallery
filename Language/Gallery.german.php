<?php
// Version: 2.1; Gallery

$txt['app_gallery_name'] = 'Bildergalerie';

// Permissions
$txt['permissiongroup_gallery_classic_perm'] = 'TekFW: Galerie';
$txt['permissiongroup_simple_gallery_simple_perm'] = 'TekFW: Galerie administrieren';
$txt['permissionname_gallery_perm_manage_album'] = 'Alben managen';
$txt['permissionhelp_gallery_perm_manage_album'] = 'Gestattet das Anlegen, Anpassen und Löschen von Alben.';
$txt['permissionname_gallery_perm_manage_image'] = 'Bilder managen';
$txt['permissionhelp_gallery_perm_manage_image'] = 'Gestattet das die Verwaltung von Bildern mit den Rechten ihrew Daten zu verändern oder auch ganze Bilder zu löschen.';

// Album
$txt['app_gallery_headline'] = 'Bildergalerie';
$txt['app_gallery_upload'] = 'Bild hochladen';
$txt['app_gallery_intro'] = '';
$txt['app_gallery_legal'] = '';
$txt['app_gallery_pictures'] = 'Bilder';
$txt['app_gallery_nopics'] = 'Keine Bilder in dieser Galerie vorhanden.';
$txt['app_gallery_without_title'] = '<ohne Tite';
$txt['app_gallery_album'] = 'Album';
$txt['app_gallery_album_headline_info'] = 'Albuminformationen';
$txt['app_gallery_album_title'] = 'Titel';
$txt['app_gallery_album_description'] = 'Beschreibung';
$txt['app_gallery_album_category'] = 'Albumgruppe';
$txt['app_gallery_album_tags'] = 'Tags';
$txt['app_gallery_album_notes'] = 'interne Notizen';
$txt['app_gallery_album_legalinfo'] = 'rechtliche Hinweise';
$txt['app_gallery_album_new'] = 'Neues Album';
$txt['app_gallery_album_edit'] = 'Album bearbeiten';
$txt['app_gallery_album_delete'] = 'Album löschen';
$txt['app_gallery_album_headline_upload'] = 'Upload';
$txt['app_gallery_album_upload_not_active'] = 'Uploads sind deaktiviert, da in der Appkonfiguration keine erlaubten MIME-Typen gewählt wurden.';
$txt['app_gallery_album_mime_types'] = 'Erlaubte MIME-Typen';
$txt['app_gallery_mime_type_help'] = 'Gewählte MIME-Typen sind für den Upload erlaubt. Werden keine Typen ausgwählt, dann ist der Upload generell deaktiviert.';
$txt['app_gallery_album_headline_access'] = 'Benutzerrechte';
$txt['app_gallery_album_accessgroups'] = 'Album ansehen';
$txt['app_gallery_accessgroups_help'] = 'Diese Gruppen haben Zugriff auf das Album. Wenn keine Gruppe/n gewählt wurden, dann wird das Album nicht in der Galerie angezeit.';
$txt['app_gallery_album_uploadgroups'] = 'Bilder hochladen';
$txt['app_gallery_uploadroups_help'] = 'Diese Gruppen dürfen Bilder in das Album hochladen.';
$txt['app_gallery_album_headline_options'] = 'Optionen';
$txt['app_gallery_album_anonymous'] = 'Anonyme Bilder';
$txt['app_gallery_album_scoring'] = 'Scoring aktiv?';
$txt['app_gallery_album_img_per_user'] = 'Bilder pro User';

// Errors
$txt['app_gallery_album_error_title_already_exists'] = 'Dieser Albumtitel ist bereits vergeben.';

// Display
$txt['app_gallery_rnd_image'] = 'Zufallsbild';
$txt['app_gallery_title'] = 'Titel';
$txt['app_gallery_description'] = 'Beschreibung';
$txt['app_gallery_from_gallery'] = 'Aus Galerie';
$txt['app_gallery_picturedata'] = 'Bildinformationen';
$txt['app_gallery_filesize'] = 'Dateigröße';
$txt['app_gallery_uploader'] = 'Uploader';
$txt['app_gallery_dimension'] = 'Abmessungen';
$txt['app_gallery_date_upload'] = 'Datum/Zeit';
$txt['app_gallery_gallerydata'] = 'Zugehörige Galerie';
$txt['app_gallery_imgurl'] = 'URLs';
$txt['app_gallery_imgurl_original'] = 'Orginal';
$txt['app_gallery_imgurl_medium'] = 'Medium';
$txt['app_gallery_imgurl_thumb'] = 'Thumb';
$txt['app_gallery_optional'] = 'Optional';

// Upload
$txt['app_gallery_optional_info'] = 'Du kannst für das Bild einen Titel und eine Beschreibung angeben. Beides ist optional. Wenn kein Titel angegeben wird, dann wird der Dateiname des Bildes (ohne Endung) als Bildername verwendet.';
$txt['app_gallery_picture_id_album'] = 'Album';
$txt['app_gallery_picture_upload'] = 'Bild hochladen';
$txt['app_gallery_picture_title'] = 'Bildtitel';
$txt['app_gallery_picture_description'] = 'Beschreibung';
$txt['app_gallery_max_upload_size'] = 'Maximale Dateigröße: %s (%d Bytes)';
$txt['app_gallery_upload_error_0'] = 'There is no error, the file uploaded with success';
$txt['app_gallery_upload_error_1'] = 'Die hochgeladene Datei ist größer als erlaubt.';
$txt['app_gallery_upload_error_2'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
$txt['app_gallery_upload_error_3'] = 'Die Datei wurde nicht vollständig hochgeladen.';
$txt['app_gallery_upload_error_4'] = 'Es wurde keine Datei hochgeladen.';
$txt['app_gallery_upload_error_6'] = 'Der Temp-Ordner konnte nicht gefunden werden.';
$txt['app_gallery_file_already_exists'] = 'Die Datei "%s" existiert bereits.';
$txt['app_gallery_upload_is_no_image'] = 'Die hochgeladene Datei ist keine gültige Bilddatei.';

// Config
$txt['app_gallery_cfg_group_display'] = 'Darstellung';
$txt['app_gallery_cfg_grid'] = 'Grid';
$txt['app_gallery_cfg_grid_desc'] = 'Größe der Galerie- und Bilderübersichten. Wird 1 gewählt, dann wird die Gridansicht => deaktiviert.';
$txt['app_gallery_cfg_group_paths'] = 'Pfade';
$txt['app_gallery_cfg_path'] = 'Verzeichnis';
$txt['app_gallery_cfg_path_desc'] = 'Verzeichnis innerhalb des SMF Ordners an dem sich die Galerie befindet.';
$txt['app_gallery_cfg_group_upload'] = 'Upload Einstellungen';
$txt['app_gallery_cfg_upload_mime_types'] = 'Mime-Types';
$txt['app_gallery_cfg_upload_mime_types_desc'] = 'Die für den Upload zulässigen Mime-Types. Wenn keine Auswahl getroffen wird, dann wird der Upload deaktiviert.';
$txt['app_gallery_cfg_upload_no_overwrite'] = 'Überschreiben verbieten';
$txt['app_gallery_cfg_upload_no_overwrite_desc'] = 'Wenn aktiviert, dann werden die Dateiname hochgeladener Bilder um eine eindeutige ID erweitert. Dies verhinder, dass ein bereits mit dem Namen vorhandenes Bild überschrieben wird.';
$txt['app_gallery_cfg_group_thumbnail'] = 'Thumbnails';
$txt['app_gallery_cfg_thumbnail_use'] = 'Thumbnails nutzen';
$txt['app_gallery_cfg_thumbnail_use_desc'] = 'Wenn aktiv, dann wird von jedem hochgeladenen Bild ein Thumbnail mit den nachfolgenden Einstellungen angelegt.';
$txt['app_gallery_cfg_thumbnail_width'] = 'Thumbnail Breite (in px)';
$txt['app_gallery_cfg_thumbnail_width_desc'] = 'Breite der zu erstellenden Thumnails in Pixel.';
$txt['app_gallery_cfg_thumbnail_quality'] = 'Thumbnail JPEG-Qualität (1-100)';
$txt['app_gallery_cfg_thumbnail_quality_desc'] = 'Legt die Qualität für die Thumbnails fest. Dieser wert entspricht den %-Angaben für JPEG Kompression.';

