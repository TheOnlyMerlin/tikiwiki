<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_tabs_info() {
	return array(
		'name' => tra('Tabs'),
		'documentation' => 'PluginTabs',
		'description' => tra('Arrange content in tabs'),
		'prefs' => array( 'wikiplugin_tabs' ),
		'body' => tra('Tabs content separated by /////'),
		'icon' => 'pics/icons/tab_edit.png',
		'params' => array(
			'name' => array(
				'required' => false,
				'name' => tra('Tabset Name'),
				'description' => tra('Unique tabset name (if you want it to remember its last state). Ex: user_profile_tabs'),
				'default' => '',
			),
			'tabs' => array(
				'required' => true,
				'name' => tra('Tab Titles'),
				'description' => tra('Pipe separated list of tab titles. Ex: tab 1|tab 2|tab 3'),
				'default' => '',
			),
		),
	);
}

function wikiplugin_tabs($data, $params) {
	global $tikilib, $smarty;
	if (!empty($params['name'])) {
		$tabsetname = $params['name'];
	} else {
		$tabsetname = '';
	}
	
	$tabs = array();
	if (!empty($params['tabs'])) {
		$tabs = explode('|', $params['tabs']);
	} else {
		return "''".tra("No tab title specified. At least one has to be set to make the tabs appear.")."''";
	}
	if (!empty($data)) {
		$tabData = explode('/////', $data);
	}
	
	$smarty->assign( 'tabsetname', $tabsetname );
	$smarty->assign_by_ref( 'tabs', $tabs );
	$smarty->assign_by_ref( 'tabcontent', $tabData );

	$content = $smarty->fetch( 'wiki-plugins/wikiplugin_tabs.tpl' );

	return $content;
}