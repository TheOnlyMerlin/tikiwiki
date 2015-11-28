<?php
// $Id$
include_once('tiki-setup.php');

if ($tiki_p_admin != 'y') {
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra("You don't have permission to use this feature"));
	$smarty->display('error.tpl');
	die;
}
function list_perms($objectId, $objectType) {
	global $userlib, $tikilib, $prefs;
	$ret = array();
	$perms = $userlib->get_object_permissions($objectId, $objectType);
	if (!empty($perms)) {
		foreach ($perms as $perm) {
			$ret[] = array('group'=>$perm['groupName'], 'perm'=>$perm['permName'], 'reason'=>'Special');
		}
	} elseif ($prefs['feature_categories'] == 'y') {
		global $categlib; include_once('lib/categories/categlib.php');
		$categs = $categlib->get_object_categories($objectType, $objectId);
		if (!empty($categs)) {
			foreach ($categs as $categId) {
				$category_perms = $userlib->get_object_permissions($categId, 'category');
				// return array(array('groupName'=>g, 'permName'=>p), ...)
				$config = array();
				if (!empty($category_perms)) {
					foreach ($category_perms as $category_perm) {
						$config[$category_perm['groupName']][$category_perm['permName']] = 'y';
					}

					foreach ($config as $gr=>$perms) {
						$ps = $tikilib->get_perm_from_categPerms($perms, $objectType, false);
						foreach ($ps as $perm=>$ok) {
							$categPerm = true;
							if ($ok == 'y') {
								$ret[] = array('group'=>$gr, 'perm'=>$perm, 'reason'=>'Category', 'detail'=> $categId);
							}
						}
					}
				}
			}
		}
	}
	return array('objectId'=>$objectId, 'special'=>$ret);
}

$types = array('wiki page', 'file gallery');
$all_groups = $userlib->list_all_groups();
$res = array();
foreach ($types as $type) {
	$type_perms = $userlib->get_permissions(0, -1, 'permName_asc', '', $tikilib->get_permGroup_from_objectType($type));
	foreach ($all_groups as $gr) {
		$perms = $userlib->get_group_permissions($gr);
		foreach ($type_perms['data'] as $type_perm) {
			if (in_array($type_perm['permName'] , $perms)) {
				$res[$type]['default'][] = array('group'=>$gr, 'perm'=>$type_perm['permName']);
			}
		}
	}
	switch ($type) {
	case 'wiki page':
	case 'wiki':
		$pages = $tikilib->list_pageNames();
		foreach ($pages['data'] as $page) {
			$res[$type]['objects'][] = list_perms($page['pageName'], $type);
		}
		break;
	case 'file galleries':
	case 'file gallery':
		$files = $tikilib->list_file_galleries();

		foreach ($files['data'] as $file) {
			$res[$type]['objects'][] = list_perms($file['id'], $type);
		}
		break;
	default:
		break;
	}
}

$smarty->assign_by_ref('res', $res);
$smarty->assign('mid', 'tiki-list_object_permissions.tpl');
$smarty->display('tiki.tpl');
