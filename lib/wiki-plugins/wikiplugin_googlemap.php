<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

function wikiplugin_googlemap_help() {
	return tra("googlemap").":~np~{GOOGLEMAP(type=locator|user, mode=normal|satellite|hybrid, key=XXXXX name=xxx, width=500, height=400, frameborder=1|0, defaultx=-79.4, defaulty=43.707, defaultz=14, setdefaultxyz=1|0, locateitemtype=wiki page|..., locateitemid=xxx)}{GOOGLEMAP}~/np~";
}

function wikiplugin_googlemap_info() {
	return array(
		'name' => tra('googlemap'),
		'documentation' => 'PluginGoogleMap',
		'description' => tra("Displays a Google map"),
		'prefs' => array( 'wikiplugin_googlemap' ),
//		'validate' => 'all',
		'params' => array(
			'type' => array(
				'safe' => true,
				'required' => true,
				'name' => tra('Type of items'),
				'description' => tra('Type of items to show on google map'),
			),
			'mode' => array(
				'safe' => true,
				'required' => true,
				'name' => tra('Map display mode'),
				'description' => tra('Map display mode'),
			),
			'key' => array(
					'safe' => true,
					'required' => false,
					'name' => tra('API Key'),
					'description' => tra('Google maps key, if not set in user preferences'),
				),
			'name' => array(
				'safe' => true,
				'required' => false,
				'name' => tra('Map ID'),
				'description' => tra('Id suffix of Google map div to avoid conflicts with other maps on same page'),
			),
			'width' => array(
				'safe' => true,
				'required' => false,
				'name' => tra('Width'),
				'description' => tra('Pixels or %'),
			),
			'height' => array(
				'safe' => true,
				'required' => false,
				'name' => tra('Height'),
				'description' => tra('Pixels or %'),
			),
			'frameborder' => array(
				'safe' => true,
				'required' => false,
				'name' => tra('Show Border'),
				'description' => '1|0',
			),
			'defaultx' => array(
				'safe' => true,
				'required' => false,
				'name' => tra('Default longitude to center map'),
				'description' => tra('Longitude value e.g. -79.39'),
			),
			'defaulty' => array(
				'safe' => true,
				'required' => false,
				'name' => tra('Default latitude to center map'),
				'description' => tra('Latitude value e.g. 43.7'),
			),
			'defaultz' => array(
				'safe' => true,
				'required' => false,
				'name' => tra('Default zoom level to view map'),
				'description' => tra('An integer between 0 and 19'),
			),
			'setdefaultxyz' => array(
				'safe' => true,
				'required' => false,
				'name' => tra('Allow user to set map view as user default'),
				'description' => tra('1|0, allow user to set current map view as default view for himself only'),
			),
			'locateitemtype' => array(
				'safe' => true,
				'required' => false,
				'name' => tra('Type of item being geotagged'),
				'description' => tra('user, wiki page, blog, etc..., will attempt to use current object if not specified'),
			),
			'locateitemid' => array(
				'safe' => true,
				'required' => false,
				'name' => tra('ID of item being geotagged'),
				'description' => tra('Name of page, blog ID, etc..., will attempt to use current object if not specified'),
			),			
		),
	);
}

function wikiplugin_googlemap($data, $params) {

	global $prefs, $smarty, $tikilib, $access;
	
	$access->check_feature('feature_gmap');
	
	$type = $params["type"];
	$smarty->assign('gmaptype', $type);
	
	if (isset($params["mode"]) && $params["mode"]) {
		$smarty->assign( 'gmapmode', $params["mode"] );
	} else {
		$smarty->assign( 'gmapmode', '' );
	}
	
	if (isset($params["key"]) && $params["key"]) {
		$smarty->assign( 'gmapkey', $params["key"] );
	} elseif ($prefs["gmap_key"]) {
		$smarty->assign( 'gmapkey', $prefs["gmap_key"] );
	} else {
		return tra("Google Maps API key not set");
	}
	
	if (isset($params["name"]) && $params["name"]) {
		$gmapname = str_replace(' ', '', $params["name"]);
	} else {
		$gmapname = 'default';
	}
	$smarty->assign( 'gmapname',  $gmapname);
	
	if (isset($params["defaultx"]) && $params["defaultx"]) {
		$smarty->assign( 'gmap_defaultx', $params["defaultx"] );
	} else {
		$smarty->assign( 'gmap_defaultx', $prefs["gmap_defaultx"] );
	}
	
	if (isset($params["defaulty"]) && $params["defaulty"]) {
		$smarty->assign( 'gmap_defaulty', $params["defaulty"] );
	} else {
		$smarty->assign( 'gmap_defaulty', $prefs["gmap_defaulty"] );
	}
	
	if (isset($params["defaultz"]) && $params["defaultz"]) {
		$smarty->assign( 'gmap_defaultz', $params["defaultz"] );
	} else {
		$smarty->assign( 'gmap_defaultz', $prefs["gmap_defaultz"] );
	}
	
	if (isset($params["setdefaultxyz"]) && $params["setdefaultxyz"]) {
		$access->check_feature('feature_ajax');
		$smarty->assign( 'gmap_defaultset', true) ;
		global $ajaxlib;
		include_once ('lib/ajax/ajaxlib.php');
		$ajaxlib->registerFunction('saveGmapDefaultxyz');
	} else {
		$smarty->assign( 'gmap_defaultset', false) ;
	}
	
	if (isset($params["width"]) && $params["width"]) {
		$smarty->assign( 'gmapwidth', $params["width"] );
	} else {
		$smarty->assign( 'gmapwidth', 500 );
	}
	
	if (isset($params["height"]) && $params["height"]) {
		$smarty->assign( 'gmapheight', $params["height"] );
	} else {
		$smarty->assign( 'gmapheight', 400 );
	}
	
	if (isset($params["frameborder"]) && $params["frameborder"]) {
		$smarty->assign( 'gmapframeborder', 1 );
	} else {
		$smarty->assign( 'gmapframeborder', 0 );
	}
	
	if (isset($params["locateitemtype"]) && $params["locateitemtype"]) {
		$locateitemtype = $params["locateitemtype"];
	} else {
		$locateitemtype = '';
	}
	if (isset($params["locateitemid"]) && $params["locateitemid"]) {
		$locateitemid = $params["locateitemid"];
	} else {
		$locateitemid = '';
	}
	
	
	if ($type == 'user') {
		$query = "SELECT `login`, `avatarType`, `avatarLibName`, `userId`, p1.`value` as lon, p2.`value` as lat FROM `users_users` as u ";
		$query.= "left join `tiki_user_preferences` as p1 on p1.`user`=u.`login` and p1.`prefName`=? ";
		$query.= "left join `tiki_user_preferences` as p2 on p2.`user`=u.`login` and p2.`prefName`=? ";
		$result = $tikilib->query($query, array('lon','lat'));
		while ($res = $result->fetchRow()) {
			if ($res['lon'] and $res['lon'] < 180 and $res['lon'] > -180 and $res['lat'] and $res['lat'] < 180 and $res['lat'] > -180) {
				$res['lon'] = number_format($res['lon'],5);
				$res['lat'] = number_format($res['lat'],5);

				$image = $tikilib->get_user_avatar( $res );
				$realName = $tikilib->get_user_preference( $res["login"], 'realName', '' );
				if (!$realName) {
					$nameShow = $res['login'];	
				} else {
					$nameShow = $realName;
				}
				$nameShow = '<a href="tiki-user_information.php?userId=' . $res['userId'] . '">' . $nameShow . '</a>';
				$out[] = array($res['lat'],$res['lon'],addslashes($image).'&nbsp;'.$nameShow.'<br />Lat: '.$res['lon'].'&deg;<br /> Long: '.$res['lat'].'&deg;');
			}
		}

		$smarty->assign('users',$out);
	} elseif ($type == 'locator') {
		$access->check_feature('feature_ajax');
		global $ajaxlib;
		include_once ('lib/ajax/ajaxlib.php');
		if ($locateitemtype == 'user') {
			$smarty->assign('gmapitemtype', 'user');
			global $userlib, $user, $tiki_p_admin_users;
			if ($locateitemid && $tiki_p_admin_users == 'y' && $locateitemid != $user && $userlib->user_exists($locateitemid)) {
				$smarty->assign('gmapitem', $locateitemid);
			} else {
				$smarty->assign('gmapitem', $user);
			}
			$ajaxlib->registerFunction('saveGmapUser');
		}
	}
	
	$ret = '~np~' . $smarty->fetch('wiki-plugins/wikiplugin_googlemap.tpl') . '~/np~';
	return $ret;

}

function saveGmapDefaultxyz($feedback, $pointx, $pointy, $pointz) {
	global $tikilib, $ajaxlib, $user;
	$objResponse = new xajaxResponse();
	if (!$user) {
		$objResponse->assign($feedback, "innerHTML", tra("Not logged in"));
		return $objResponse;
	}
	if (!is_numeric($pointx) || !is_numeric($pointy) || !is_numeric($pointz) ||
		 !($pointx > -180 && $pointx < 180 && $pointy > -180 && $pointy < 180 && $pointz >= 0 && $pointz < 20) ) {
		$objResponse->assign($feedback, "innerHTML", tra("Error: Invalid Lon. and Lat. values"));
		return $objResponse;		
	}
	$tikilib->set_user_preference($user, 'gmap_defx', $pointx);
	$tikilib->set_user_preference($user, 'gmap_defy', $pointy);
	$tikilib->set_user_preference($user, 'gmap_defz', $pointz);	
	
	$objResponse->assign($feedback, "innerHTML", tra("Map view saved as default for ") . $user);
	return $objResponse;
}

function saveGmapUser($feedback, $pointx, $pointy, $pointz, $u) {
	global $tikilib, $ajaxlib, $user, $userlib, $tiki_p_admin_users;
	$objResponse = new xajaxResponse();
	if (!($u == $user || $tiki_p_admin_users == 'y' && $u != $user && $userlib->user_exists($u))) {		
		$objResponse->assign($feedback, "innerHTML", tra("You can only set your own location"));
		return $objResponse;		
	}
	if (!is_numeric($pointx) || !is_numeric($pointy) || !is_numeric($pointz) ||
		 !($pointx > -180 && $pointx < 180 && $pointy > -180 && $pointy < 180 && $pointz >= 0 && $pointz < 20) ) {
		$objResponse->assign($feedback, "innerHTML", tra("Please select a point to set both Lon. and Lat."));
		return $objResponse;		
	}
	$tikilib->set_user_preference($u, 'lon', $pointx);
	$tikilib->set_user_preference($u, 'lat', $pointy);
	$tikilib->set_user_preference($u, 'zoom', $pointz);	
	$objResponse->assign($feedback, "innerHTML", tra("User location saved for ") . $u);
	return $objResponse;
}
