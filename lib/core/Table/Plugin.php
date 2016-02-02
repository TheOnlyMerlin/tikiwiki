<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER['SCRIPT_NAME'], basename(__FILE__)) !== false) {
	header('location: index.php');
	exit;
}

/**
 * Class Table_Plugin
 *
 * For use by plugins to apply tablesorter to tables generated by the plugin. Creates parameters that
 * can be merged in with the plugin's parameters and generates settings from user input that can be
 * used in Table_Factory
 */
class Table_Plugin
{
	public $params = array();
	public $settings;
	public $perms = true;


	public function __construct()
	{
		global $prefs;
		if ($prefs['disableJavascript'] === 'y' || $prefs['feature_jquery_tablesorter'] === 'n') {
			$this->perms = false;
		}

	}

	/**
	 * Creates parameters that can be appended to a plugin's native parameters so the user can
	 * set tablesorter functionality
	 */
	public function createParams()
	{
		$this->params = array(
			'server' => array(
				'required' => false,
				'name' => tra('Server Side Processing'),
				'description' => tr(
					'Enter %0y%1 to have the server do the sorting and filtering through Ajax and %0n%1 to have the
					browser do it (n is the default). Set to %0y%1 (and also set the %2Paginate%3 parameter) if you do not
					want all rows fetched at once, but rather fetch rows as you paginate, filter or sort.',
					'<b>', '</b>', '<em>', '</em>'
				),
				'default' => 'n',
				'filter' => 'striptags',
			),
			'sortable' => array(
				'required' => false,
				'name' => tra('Overall Sort Settings'),
				'description' => tr(
					'Enter %0y%1 to allow sorting and %0n%1 to disallow (n is the default). Enter type:%0save%1
					to allow sorts to be saved between page refreshes. Enter type:%0reset%1;text:***** to allow sorting
					and show an unsort button with custom text. Enter %0type:savereset%1;text:buttontext to allow the
					same for saved sorts.',
					'<b>', '</b>'
				),
				'default' => 'n',
				'filter' => 'striptags',
			),
			'sortList' => array(
				'required' => false,
				'name' => tra('Pre-sorted Columns'),
				'description' => tra(
					'Bracketed numbers for column number (first column = 0) and sort direction
					(0 = ascending, 1 = descending, n = no sort, y = allow sorting but no pre-sort), for example:
					[0,y],[1,0],[2,n]. If the first pre-sorted or no filter column is not the first column, then you
					should use the y parameter (as in [0,y]) to assign all previous columns.'
				),
				'default' => '',
				'filter' => 'striptags',
				'advanced' => true,
			),
			'tsortcolumns' => array(
				'required' => false,
				'name' => tra('Sort Settings by Column'),
				'description' => tr(
					'Set %0type%1 and %0group%1 settings for each column, using %0|%1 to separate columns. To
					show group headings upon page load, the %2Pre-sorted Columns%3 parameter will need to be set for a
					column with a group setting. Group will not work in plugins where the %2Server Side Processing%3
					parameter is set to \'y\'.', '<b>', '</b>', '<em>', '</em>')
				. '<br>' . tr('%0type%1 tells the sorter what type of date is being sorted and choices include:
					%0text%1, %0digit%1, %0currency%1, %0percent%1, %0usLongDate%1, %0shortDate%1, %0isoDate%1,
					%0dateFormat-ddmmyyyy%1, %0ipAddress%1, %0url%1, %0time%1.
					Also handle strings in numeric columns with %0string-min%1 and %0string-max%1. Handle empty cells
					with %0empty-top%1, %0empty-bottom%1 or %0empty-zero%1.', '<b>', '</b>')
				. '<br>' . tr('%0group%1 creates automatic row headings upon sort with the heading text determined by
					the setting as follows: %0letter%1 (first letter), %0word%1 (first word), %0number%1, %0date%1,
					%0date-year%1, %0date-month%1, %0date-day%1, %0date-week%1, %0date-time%1. %0letter%1 and %0word%1
					can be extended, e.g., %0word-2%1 shows first 2 words. %0number-10%1 will group rows in blocks of
					ten. Group will not work in plugins where the %2Server Side Processing%3 parameter is set to
					\'y\'.', '<b>', '</b>', '<em>', '</em>'
				),
				'default' => '',
				'filter' => 'striptags',
				'advanced' => true,
			),
			'tsfilters' => array(
				'required' => false,
				'name' => tra('Column Filters'),
				'description' => tr(
					'Enter %0y%1 for a blank text filter on all columns, or %0n%1 for no filters. Or set custom column filters
					separated by %0|%1 for each column for the following filter choices and parameters:', '<b>', '</b>'
				)
					. '<br> <b>Text - </b>type:text;placeholder:xxxx<br>' .
					tra('(For PluginTrackerlist this will be an exact search, for other plugins partial values will work.)') . '<br>
					<b>Dropdown - </b>type:dropdown;placeholder:****;option:****;option:****;option:**** <br>' .
					tra('(options generated automatically if not set and the server parameter is not \'y\')') . '<br>
					<b>' . tra('Date range - ') . '</b>type:date;format:yy-mm-dd;from:2013-06-30;to:2020-12-31<br>' .
					tra('(from and to values set defaults for these fields when user clicks on the input field)') . '<br>
					<b>' . tra('Numeric range - ') . '</b>type:range;from:0;to:50<br>
					<b>' . tra('No filter - ') . '</b>type:nofilter<br>' .
					tra(
						'For example: tsfilters="type:dropdown;placeholder:Type to filter..." would result in a dropdown
						filter on the first column with all unique values in that column in the dropdown list.'
					),
				'default' => '',
				'filter' => 'striptags',
				'advanced' => true,
			),
			'tsfilteroptions' => array(
				'required' => false,
				'name' => tra('Filter Options'),
				'description' => tr(
					'The following options are available: %0reset%1 (adds button to take off filters), and %0hide%1
					(Filters are revealed upon mouseover. Hide doesn\'t work when date and range filters are used.). To use both, set
					tsfilteroptions="type:reset;text:button text;style:hide"', '<b>', '</b>'
				),
				'default' => '',
				'filter' => 'striptags',
				'advanced' => true,
			),
			'tspaginate' => array(
				'required' => false,
				'name' => tra('Paginate'),
				'description' => tr(
					'Enter %0y%1 to set default values based on the site setting for maximum records in listings (on the
				 	pagination table of the Look & Feel admin panel). Set to %0n%1 (and %2server%3 cannot be set to
				 	%0y%1) for no pagination. Set custom values as in the following example: ',
						'<b>', '</b>', '<em>', '</em>') .
					'<b>max</b>:40;<b>expand</b>:60;expand:100;expand:140',
				'default' => '',
				'filter' => 'striptags',
				'advanced' => true,
			),
		);
	}

	/**
	 * To be used within plugin program to convert user parameter settings into the settings array
	 * that can be used by Table_Factory to generate the necessary jQuery
	 *
	 * @param null   $id				//html element id for table and surrounding div
	 * @param string $sortable			//see params above
	 * @param null   $sortList			//see params above
	 * @param string $tsortcolumns		//see params above
	 * @param null   $tsfilters			//see params above
	 * @param null   $tsfilteroptions	//see params above
	 * @param null   $tspaginate		//see params above
	 * @param null   $ajaxurl			//only needed if ajax will be used to pull partial record sets
	 * @param null   $totalrows			//only needed if ajax will be used to pull partial record sets
	 */
	public function setSettings ($id = null, $server = 'n', $sortable = 'n', $sortList = null, $tsortcolumns = null,
		$tsfilters = null, $tsfilteroptions = null, $tspaginate = null, $ajaxurl = null, $totalrows = null)
	{
		$s = array();

		//id
		if (!empty($id)) {
			$s['id'] = $id;
		}

		//sortable
		switch ($sortable) {
			case 'y':
			case 'server':
				$s['sort']['type'] = true;
				break;
			case 'n':
				$s['sort']['type'] = false;
				break;
			default:
				$sp = $this->parseParam($sortable);
				if (isset($sp[0]['type'])) {
					$s['sort']['type'] = $sp[0]['type'];
				}
		}

		//sortlist
		if (!empty($sortList) && $s['sort']['type'] !== false) {
			$crop = substr($sortList, 1);
			$crop = substr($crop, 0, -1);
			$slarray = explode('],[', $crop);
			if (is_array($slarray)) {
				foreach ($slarray as $l) {
					$lpieces = explode(',', $l);
					if (isset($lpieces[1])) {
						switch ($lpieces[1]) {
							case '0':
								$dir = 'asc';
								break;
							case '1':
								$dir = 'desc';
								break;
							case 'y':
								$dir = true;
								break;
							case 'n':
								$dir = false;
								break;
							default:
								if($s['sort']['type'] !== false) {
									$dir = true;
								} else {
									$dir = false;
								}
						}
						if ($dir === false || $dir === true) {
							$s['sort']['columns'][$lpieces[0]]['type'] = $dir;
						} else {
							$s['sort']['columns'][$lpieces[0]]['dir'] = $dir;
						}
					}
				}
			}
		}

		if (!empty($tsortcolumns)) {
			$tsc = $this->parseParam($tsortcolumns);
			if (is_array($tsc)) {
				foreach ($tsc as $col => $info) {
					if (isset($s['sort']['columns'][$col])) {
						$s['sort']['columns'][$col] = $s['sort']['columns'][$col] + $info;
					} else {
						$s['sort']['columns'][$col] = $info;
					}
				}
				ksort($s['sort']['columns']);
			}
		} else {
			$s['sort']['group'] = false;
		}

		//tsfilters
		if (!empty($tsfilters)) {
			switch ($tsfilters) {
				case 'y':
					$s['filters']['type'] = 'text';
					break;
				case 'n':
					$s['filters']['type'] = false;
					break;
				default:
					$tsf = $this->parseParam($tsfilters);
					if (is_array($tsf)) {
						$s['filters']['columns'] = $this->parseParam($tsfilters);
					}
			}
		}

		//tsfilteroptions
		if (!empty($tsfilteroptions) && !empty($s['filters']['type'])) {
			$tsfo = $this->parseParam($tsfilteroptions);
			switch ($tsfo[0]['type']) {
				case 'reset':
					$s['filters']['type'] = 'reset';
					break;
				case 'hide':
					$s['filters']['hide'] = true;
					break;
			}
		}

		//tspaginate
		if (!empty($tspaginate)) {
			$tsp = $this->parseParam($tspaginate);
			//pagination must be on if server side processing is on ($server == 'y')
			if (is_array($tsp[0]) || $tsp[0] !== 'n' || ($tsp[0] === 'n' && $server === 'y')) {
				if (is_array($tsp[0])) {
					$s['pager'] = $tsp[0];
					if (is_array($s['pager']['expand'])) {
						if (isset($s['pager']['max']) && $s['pager']['max'] > 0) {
							$s['pager']['expand'] = array_merge(array($s['pager']['max']), $s['pager']['expand']);
						} else {
							$s['pager']['max'] = min($s['pager']['expand']);
						}
						$s['pager']['expand'] = array_unique($s['pager']['expand']);
						sort($s['pager']['expand']);
					}
				}
				$s['pager']['type'] = true;
			} elseif ($tsp[0] === 'n' && $server === 'n') {
				$s['pager']['type'] = false;
			}
		}

		//ajaxurl
		if (!empty($ajaxurl) && $server === 'y') {
			$s['ajax']['url'] = $this->getAjaxurl($ajaxurl);
			$s['ajax']['type'] = true;
		} else {
			$s['ajax']['type'] = false;
		}

		//totalrows
		if (!empty($totalrows)) {
			$s['total'] = $totalrows;
		}

		$this->settings = $s;

	}

	/**
	 * Utility to convert string entered by user for a parameter setting to an array
	 *
	 * @param $param
	 *
	 * @return array
	 */
	function parseParam ($param)
	{
		if (!empty($param)) {
			$ret = explode('|', $param);
			foreach ($ret as $key => $pipe) {
				$ret[$key] = strpos($pipe, ';') !== false ? explode(';', $pipe) : $pipe;
				if (!is_array($ret[$key])) {
					if (strpos($ret[$key], ':') !== false) {
						$colon = explode(':', $ret[$key]);
						unset($ret[$key]);
						if ($colon[1] == 'nofilter') {
							$colon[1] = false;
						}
						$ret[$key][$colon[0]] = $colon[1];
					}
				} elseif (is_array($ret[$key])) {
					foreach ($ret[$key] as $key2 => $subparam) {
						if (strpos($subparam, ':') !== false) {
							$colon = explode(':', $subparam);
							unset($ret[$key][$key2]);
							if ($colon[0] == 'expand' || $colon[0] == 'option') {
								if ($colon[0] == 'option') {
									$colon[0] = 'options';
								}
								$ret[$key][$colon[0]][] = $colon[1];
							} else {
								$ret[$key][$colon[0]] = $colon[1];
							}
						}
					}
				}
			}
			ksort($ret);
			return $ret;
		} else {
			return $param;
		}
	}

	/**
	 * Utility to add ajax parameters to URL
	 *
	 * @param $ajaxurl
	 *
	 * @return string
	 */
	private function getAjaxurl($ajaxurl)
	{
		$str = '{sort:sort}&{filter:filter}';
		$url = parse_url($ajaxurl);
		if (isset($url['query'])) {
			$query = $url['query'] . '&' . $str;
		} else {
			$query = $str;
		}
		return $url['path'] . '?' . $query;
	}

}
