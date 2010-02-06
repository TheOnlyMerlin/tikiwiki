<?php
// (c) Copyright 2002-2009 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: /cvsroot/tikiwiki/tiki/tiki-view_minical_topic.php,v 1.7.2.1 2008-03-01 16:07:36 lphuberdeau Exp $
require_once ('tiki-setup.php');
include_once ('lib/minical/minicallib.php');

$acces->check_feature('feature_minical', '');
$acces->check_permission('tiki_p_minical');

if (!$user) die;
if (!isset($_REQUEST["topicId"])) {
	die;
}
$info = $minicallib->minical_get_topic($user, $_REQUEST["topicId"]);
$type = & $info["filetype"];
$file = & $info["filename"];
$content = & $info["data"];
header("Content-type: $type");
header("Content-Disposition: inline; filename=$file");
echo "$content";
