<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 *
 * \brief smarty_block_tabs : add tabs to a template
 *
 * params: name (optional but unique per page if set)
 * params: toggle=y on n default
 *
 * usage:
 * \code
 *	{tabset name='tabs}
 * 		{tab name='tab1'}tab content{/tab}
 * 		{tab name='tab2'}tab content{/tab}
 * 		{tab name='tab3'}tab content{/tab}
 *	{/tabset}
 * \endcode
 */

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

function smarty_block_tabset($params, $content, $smarty, &$repeat)
{
	global $prefs, $smarty_tabset_name, $smarty_tabset, $smarty_tabset_i_tab, $cookietab, $headerlib, $tabset_index;

	if ($smarty->getTemplateVars('print_page') == 'y' || $prefs['layout_tabs_optional'] === 'n') {
		$params['toggle'] = 'n';
	}
	if ( $repeat ) {
		// opening
		if (!is_array($smarty_tabset)) {
			$smarty_tabset = array();
		}
		$tabset_index = count($smarty_tabset) + 1;
		if ( ! empty($params['name']) ) {
			$smarty_tabset_name = $params['name'];	// names have to be unique
		} else {
			$short_name = str_replace(array('tiki-', '.php'), '', basename($_SERVER['SCRIPT_NAME']));
			$smarty_tabset_name = 't_' . $short_name . $tabset_index;
		}
		$smarty_tabset_name = TikiLib::remove_non_word_characters_and_accents($smarty_tabset_name);
		$smarty_tabset[$tabset_index] = array( 'name' => $smarty_tabset_name, 'tabs' => array());
		if (!isset($smarty_tabset_i_tab)) {
			$smarty_tabset_i_tab = 1;
		}

		if (!isset($cookietab) || $tabset_index > 1) {
			$cookietab = getCookie($smarty_tabset_name, 'tabs', 1);
		}
		// work out cookie value if there
		if ( isset($_REQUEST['cookietab']) && $tabset_index === 1) {	// overrides cookie if added to request as in tiki-admin.php?page=look&cookietab=6
			$cookietab = empty($_REQUEST['cookietab']) ? 1 : $_REQUEST['cookietab'];
			setCookieSection($smarty_tabset_name, $cookietab, 'tabs');	// too late to set it here as output has started
		}

		// If the tabset specifies the tab, override any kind of memory but only if not doing "no tabs" mode
		if (isset($params['cookietab']) && $cookietab !== 'n') {
			$cookietab = $params['cookietab'];
		}

		$smarty_tabset_i_tab = 1;

		return;
	} else {
		$content = trim($content);
		if (empty($content)) {
			return '';
		}
		$ret = ''; $notabs = '';
		//closing
		if ( $prefs['feature_tabs'] == 'y') {
			if (empty($params['toggle']) || $params['toggle'] != 'n') {
				$smarty->loadPlugin('smarty_function_button');
				if ($cookietab == 'n') {
					$button_params['_text'] = tra('Tab View');
				} else {
					$button_params['_text'] = tra('No Tabs');
				}
				$button_params['_size'] = 'mini';
				$button_params['_auto_args']='*';
				$button_params['_onclick'] = "setCookie('$smarty_tabset_name','".($cookietab == 'n' ? 1 : 'n' )."', 'tabs') ;";
				$button_params['_class'] = 'btn-xs';
				$notabs = smarty_function_button($button_params, $smarty);
				$notabs = "<div class='pull-right tabstoggle floatright'>$notabs</div>";
				$content_class = '';
			} else {
				$content_class = ' full_width';	// no no-tabs button
			}
		} else {
			return $content;
		}
		if ( $cookietab == 'n' ) {
			return $ret.$notabs.$content;
		}

		$ret .= '<div class="clearfix tabs" data-name="' . $smarty_tabset_name . '">' . $notabs;

		$count = 1;
		if ($prefs['mobile_feature'] === 'y' && $prefs['mobile_mode'] === 'y') {

			$ret .= '<div class="container' . $content_class . '" data-role="navbar"><ul>';
			foreach ($smarty_tabset[$tabset_index]['tabs'] as $value) {
				$ret .= '<li>'.
					'<a href="#" class="tabmark tab'.$count.' '.($count == $cookietab ? 'ui-btn-active' : '').'"' .
					' onclick="tikitabs('.$count.',this); return false;">'.$value.'</a></li>';
				++$count;
			}
			$ret .= '</ul></div>';

		} else {	// notmal non-mobile rendering

			$ret .= '<ul class="nav nav-tabs">';
			foreach ($smarty_tabset[$tabset_index]['tabs'] as $value) {
				$ret .= '<li class="'.($count == $cookietab ? 'active' : '').'"><a href="#content' . $smarty_tabset[$tabset_index]['name'] . $tabset_index . '-' . $count.'" data-toggle="tab">'.$value.'</a></li>';
				++$count;
			}
			$ret .= '</ul>';
		}
		$ret .= "</div>";
		$tabset_index--;

		return $ret . '<div class="tab-content">' . $content . '</div>';
	}
}
