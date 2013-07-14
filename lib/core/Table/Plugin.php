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

class Table_Plugin
{
	public static $params = array();
	public $settings;

	public function createParams()
	{
		$this->params = array(
			'sortable' => array(
				'required' => false,
				'name' => tra('Column Sort'),
				'description' => tr(
					'Enter %0y%1 to allow sorting and %0n%1 to disallow (n is the default). Enter type:%0save%1
					to allow sorts to be saved between page refreshes. Enter type:%0reset%1;text:***** to allow sorting and
					show an unsort button with custom text. Enter %0type:savereset%1;text:buttontext to allow the same for saved sorts.',
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
					(0 = ascending, 1 = descending, n = no sort), for example: [0,0],[1,0],[2,n]'
				),
				'default' => '',
				'filter' => 'striptags',
			),
			'tsfilters' => array(
				'required' => false,
				'name' => tra('Column Filters'),
				'description' => tr(
					'Enter %0y%1 for a blank text filter on all columns, or %0n%1 for no filters. Or set custom column filters
					separated by %0|%1 for each column for the following filter choices and parameters:','<b>', '</b>'
				)
					. '<br> <b>Text - </b>type:text;placeholder:xxxx<br>
					<b>Dropdown - </b>type:dropdown;placeholder:****;option:****;option:****;option:**** <br>' .
					tra('(options generated automatically if not set)') . '<br>
					<b>' . tra('Date range - ') . '</b>type:date;format:yyyy-mm-dd;from:2013-06-30;to:2013-12-31<br>' .
					tra('(from and to values set defaults for these fields when user clicks on the input field)') . '<br>
					<b>' . tra('Numeric range - ') . '</b>type:range;from:0;to:50<br>
					<b>' . tra('No filter - ') . '</b>type:nofilter<br>' .
					tra(
						'For example: tsfilters="type:dropdown;placeholder:Type to filter..." would result in a dropdown
						filter on the first column with all unique values in that column in the dropdown list.'
					),
				'default' => '',
				'filter' => 'striptags',
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
			),
			'tspaginate' => array(
				'required' => false,
				'name' => tra('Paginate'),
				'description' => tra(
					'Enter y to set default values: 20 rows max and expand dropdown with values from
				 	10-200. Set custom values as in the following example: '
				) .
					'<b>max</b>:40;<b>expand</b>:60;expand:100;expand:140',
				'default' => '',
				'filter' => 'striptags',
			),
		);
	}

	function setSettings ($id = null, $sortable = 'y', $sortList = null, $tsfilters = null,
						 $tsfilteroptions = null, $tspaginate = null, $ajaxurl = null, $totalrows = null)
	{
		$s = array();

		//id
		if (!empty($id)) {
			$s['id'] = $id;
		}

		//sortable
		switch ($sortable) {
			case 'y':
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
					switch ($lpieces[1]) {
						case '0':
							$dir = 'asc';
							break;
						case '1':
							$dir = 'desc';
							break;
						case 'n':
							$dir = false;
							break;
					}
					if (isset($dir)) {
						$s['sort']['columns'][$lpieces[0]]['type'] = $dir;
					}
				}
			}
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
			if (is_array($tsp[0]) || $tsp[0] === 'y') {
				if (is_array($tsp[0])) {
					$s['pager'] = $tsp[0];
				}
				$s['pager']['type'] = true;
			} elseif ($tsp[0] === 'n') {
				$s['pager']['type'] = false;
			}
		}

		//ajaxurl
		if (!empty($ajaxurl)) {
			$s['ajax']['url'] = $ajaxurl;
		} else {
			$s['ajax'] = false;
		}

		//totalrows
		if (!empty($totalrows)) {
			$s['total'] = $totalrows;
		}

		$this->settings = $s;

	}


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

}