<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: Indexable.php 36354 2011-08-20 15:15:21Z lphuberdeau $

interface Tracker_Field_Indexable extends Tracker_Field_Interface
{
	function getDocumentPart($baseKey, Search_Type_Factory_Interface $typeFactory);

	function getProvidedFields($baseKey);

	function getGlobalFields($baseKey);
}
