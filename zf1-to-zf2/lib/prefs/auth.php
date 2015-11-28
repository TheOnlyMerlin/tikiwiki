<?php
// (c) Copyright 2002-2015 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_auth_list()
{
	return array(
		'auth_method' => array(
			'name' => tra('Authentication method'),
			'description' => tra('Tiki supports several authentication methods. The default value is to use the internal user database.'),
			'type' => 'list',
			'help' => 'External+Authentication',
			'perspective' => false,
			'options' => array(
				'tiki' => tra('Tiki'),
				'openid' => tra('Tiki and OpenID'),
				'pam' => tra('Tiki and PAM'),
				'ldap' => tra('Tiki and LDAP'),
				'cas' => tra('CAS (Central Authentication Service)'),
				'shib' => tra('Shibboleth'),
				'ws' => tra('Web Server'),
				'phpbb' => tra('phpBB'),
			),
			'default' => 'tiki',
		),
		'auth_token_access' => array(
			'name' => tra('Token Access'),
			'description' => tra('Allow to access the content with superior rights with the presentation of a token. The primary use of this authentication method is to grant temporary access to content to an external service.'),
			'help' => 'Token+Access',
			'perspective' => false,
			'type' => 'flag',
			'default' => 'n',
			'view' => 'tiki-admin_tokens.php',
		),
		'auth_token_access_maxtimeout' => array(
			'name' => tra('Token Access Max Timeout'),
			'description' => tra('The maximum duration for which the generated tokens will be valid.'),
			'type' => 'text',
			'size' => 5,
			'perspective' => false,
			'filter' => 'digits',
			'shorthint' => tra('(seconds)'),
			'default' => 3600*24*7,
		),
		'auth_token_access_maxhits' => array(
			'name' => tra('Token Access Max Hits'),
			'description' => tra('The maximum amount of times a token can be used before it expires.'),
			'type' => 'text',
			'size' => 5,
			'perspective' => false,
			'filter' => 'digits',
			'default' => 10,
		),
		'auth_token_tellafriend' => array(
			'name' => tra('Share access rights with friends when using Tell a friend'),
			'description' => tra('Allow users to share their access rights on the current page with a friend when sending the link by email. Lifespan of the links is defined by the site.'),
			'type' => 'flag',
			'perspective' => false,
			'dependencies' => array(
				'auth_token_access',
				'feature_tell_a_friend',
			),
			'default' => 'n',
		),
		'auth_token_share' => array(
			'name' => tra('Share access rights with friends when using Share'),
			'description' => tra('Allow users to share their access rights on the current page with a friend when sending the link by email/Twitter/Facebook. Lifespan of the links is defined by the site.'),
			'type' => 'flag',
			'perspective' => false,
			'dependencies' => array(
				'auth_token_access',
				'feature_share',
			),
			'default' => 'n',
		),
		'auth_phpbb_create_tiki' => array(
			'name' => tra('Create user if not in Tiki'),
            'description' => tra('Automatically create a new Tiki User for the PHPbb login'),
            'type' => 'flag',
			'perspective' => false,
			'default' => 'n',
		),
		'auth_phpbb_skip_admin' => array(
			'name' => tra('Use Tiki authentication for Admin login'),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'y',
		),
		'auth_phpbb_disable_tikionly' => array(
			'name' => tra("Disable Tiki users who don't have a phpBB login. (They could have been deleted)."),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'n',
		),
		'auth_phpbb_version' => array(
			'name' => tra('phpBB Version'),
            'description' => tra(''),
			'type' => 'list',
			'perspective' => false,
			'options' => array(
				'3' => tra('3'),
			),
			'default' => 3,
		),
		'auth_phpbb_dbhost' => array(
			'name' => tra('phpBB Database Hostname'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 40,
			'perspective' => false,
			'default' => '',
		),
		'auth_phpbb_dbuser' => array(
			'name' => tra('phpBB Database Username'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 40,
			'perspective' => false,
			'default' => '',
		),
		'auth_phpbb_dbpasswd' => array(
			'name' => tra('phpBB Database Password'),
            'description' => tra(''),
			'type' => 'password',
			'size' => 40,
			'perspective' => false,
			'default' => '',
		),
		'auth_phpbb_dbname' => array(
			'name' => tra('phpBB Database Name'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 40,
			'perspective' => false,
			'default' => '',
		),
		'auth_phpbb_table_prefix' => array(
			'name' => tra('phpBB Table Prefix'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 40,
			'perspective' => false,
			'default' => 'phpbb_',
		),
		'auth_ldap_permit_tiki_users' => array(
			'name' => tra('Use Tiki authentication for users created in Tiki'),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'n',
		),
		'auth_ldap_host' => array(
			'name' => tra('Host'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => '',
			'extensions' => ['ldap'],
		),
		'auth_ldap_port' => array(
			'name' => tra('Port'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'perspective' => false,
			'default' => '',
			'extensions' => ['ldap'],
		),
		'auth_ldap_debug' => array(
			'name' => tra('Write LDAP debug Information in Tiki Logs'),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'n',
			'view' => 'tiki-syslog.php'			
		),
		'auth_ldap_ssl' => array(
			'name' => tra('Use SSL (ldaps)'),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'n',
		),
		'auth_ldap_starttls' => array(
			'name' => tra('Use TLS'),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'n',
		),
		'auth_ldap_type' => array(
			'name' => tra('LDAP Bind Type'),
            'description' => tra(''),
			'type' => 'list',
			'perspective' => false,
			'options' => array(
				'default' => tra('Default: Anonymous Bind'),
				'full' => tra('Full: userattr=username,UserDN,BaseDN'),
				'ol' => tra('OpenLDAP: cn=username,BaseDN'),
				'ad' => tra('Active Directory (username@domain)'),
				'plain' => tra('Plain Username'),
			),
			'default' => 'default',
		),
		'auth_ldap_scope' => array(
			'name' => tra('Search scope'),
            'description' => tra(''),
			'type' => 'list',
			'perspective' => false,
			'options' => array(
				'sub' => tra('Subtree'),
				'one' => tra('One level'),
				'base' => tra('Base object'),
			),
			'default' => "sub",
		),
		'auth_ldap_version' => array(
			'name' => tra('LDAP version'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'perspective' => false,
			'default' => 3,
		),
		'auth_ldap_basedn' => array(
			'name' => tra('Base DN'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 15,
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_userdn' => array(
			'name' => tra('User DN'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_userattr' => array(
			'name' => tra('User attribute'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => 'uid',
		),
		'auth_ldap_useroc' => array(
			'name' => tra('User OC'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => 'inetOrgPerson',
		),
		'auth_ldap_nameattr' => array(
			'name' => tra('Realname attribute'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => 'displayName',
		),
		'auth_ldap_countryattr' => array(
			'name' => tra('Country attribute'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_emailattr' => array(
			'name' => tra('Email attribute'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_groupdn' => array(
			'name' => tra('Group DN'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_groupattr' => array(
			'name' => tra('Group name attribute'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => 'cn',
		),
		'auth_ldap_groupdescattr' => array(
			'name' => tra('Group description attribute'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_groupoc' => array(
			'name' => tra('Group OC'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => 'groupOfUniqueNames',
		),
		'auth_ldap_memberattr' => array(
			'name' => tra('Member attribute'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => 'uniqueMember',
		),
		'auth_ldap_memberisdn' => array(
			'name' => tra('Member is DN'),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'y',
		),
		'auth_ldap_usergroupattr' => array(
			'name' => tra('Group attribute'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_groupgroupattr' => array(
			'name' => tra('Group attribute in group entry'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'hint' => tra('(Leave this empty if the group name is already given in the user attribute)'),
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_adminuser' => array(
			'name' => tra('Admin user'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 15,
			'autocomplete' => 'off',
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_adminpass' => array(
			'name' => tra('Admin password'),
            'description' => tra(''),
			'type' => 'password',
			'size' => 15,
			'autocomplete' => 'off',
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_group_external' => array(
			'name' => tra('Use an external LDAP server for groups'),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'n',
		),
		'auth_ldap_group_host' => array(
			'name' => tra('Host'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => 'localhost',
		),
		'auth_ldap_group_port' => array(
			'name' => tra('Port'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'perspective' => false,
			'default' => '389',
		),
		'auth_ldap_group_debug' => array(
			'name' => tra('Write LDAP debug Information in Tiki Logs'),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'n',
		),
		'auth_ldap_group_ssl' => array(
			'name' => tra('Use SSL (ldaps)'),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'n',
		),
		'auth_ldap_group_starttls' => array(
			'name' => tra('Use TLS'),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'n',
		),
		'auth_ldap_group_type' => array(
			'name' => tra('LDAP Bind Type'),
            'description' => tra(''),
			'type' => 'list',
			'perspective' => false,
			'options' => array(
				'default' => tra('Default: Anonymous Bind'),
				'full' => tra('Full: userattr=username,UserDN,BaseDN'),
				'ol' => tra('OpenLDAP: cn=username,BaseDN'),
				'ad' => tra('Active Directory (username@domain)'),
				'plain' => tra('Plain Username'),
			),
			'default' => 'default',
		),
		'auth_ldap_group_scope' => array(
			'name' => tra('Search scope'),
            'description' => tra(''),
			'type' => 'list',
			'perspective' => false,
			'options' => array(
				'sub' => tra('Subtree'),
				'one' => tra('One level'),
				'base' => tra('Base object'),
			),
			'default' => 'sub',
		),
		'auth_ldap_group_version' => array(
			'name' => tra('LDAP version'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'perspective' => false,
			'default' => '3',
		),
		'auth_ldap_group_basedn' => array(
			'name' => tra('Base DN'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 15,
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_group_userdn' => array(
			'name' => tra('User DN'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_group_userattr' => array(
			'name' => tra('User attribute'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => 'uid',
		),
		'auth_ldap_group_corr_userattr' => array(
			'name' => tra('Corresponding user attribute in 1st directory'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => 'uid',
		),
		'auth_ldap_group_useroc' => array(
			'name' => tra('User OC'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 20,
			'perspective' => false,
			'default' => 'inetOrgPerson',
		),
		'auth_ldap_group_adminuser' => array(
			'name' => tra('Admin user'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 15,
			'perspective' => false,
			'default' => '',
		),
		'auth_ldap_group_adminpass' => array(
			'name' => tra('Admin password'),
            'description' => tra(''),
			'type' => 'password',
			'size' => 15,
			'perspective' => false,
			'default' => '',
		),
		'auth_ws_create_tiki' => array(
			'name' => tra('Create user if not in Tiki'),
            'description' => tra(''),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'n',
		),
	);
}