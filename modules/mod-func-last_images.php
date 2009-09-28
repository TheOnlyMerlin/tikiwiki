<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

function module_last_images_info() {
	return array(
		'name' => tra('Last Images'),
		'description' => tra('List the specified number of images, starting from the most recently added.'),
		'prefs' => array("feature_galleries"),
		'params' => array(
			'content' => array(
				'name' => tra('Link content'),
				'description' => tra('Display the links as image names or thumbnails.') . " " . tra('Possible values: "names" or "thumbnails". Default value: "names"')
			)
		),
		'common_params' => array('nonums', 'rows')
	);
}

function module_last_images( $mod_reference, $module_params ) {
	global $smarty;
	global $imagegallib; include_once ("lib/imagegals/imagegallib.php");
	
	$smarty->assign("content", isset($module_params["content"]) ? $module_params["content"] : "names");
	$galleryId = isset($module_params["galleryId"]) ? $module_params["galleryId"] : -1;
	
	$ranking = $imagegallib->list_images(0, $mod_reference["rows"], 'created_desc', '', $galleryId);
	$smarty->assign('modLastImages', $ranking["data"]);
}
