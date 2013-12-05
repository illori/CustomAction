<?php

global $smcFunc, $user_info, $modSettings, $boardurl;

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s Settings.php.');

// If you install manually, you have to be logged in!
if(!$user_info['is_admin'])
{
	if($user_info['is_guest'])
	{
		echo $txt['admin_login'] . ':<br />';
		ssi_login($boardurl . '/install.php');
		die();
	}
	else
	{
		loadLanguage('Errors');
		fatal_error($txt['cannot_admin_forum']);
	}
}

// Okay, get down to business.
db_extend('packages');
if (!isset($modSettings['ca_enabled']))
{
	// For some reason updateSettings() refuses to set unset settings to an empty string.
	$modSettings['ca_cache'] = 'temp';

	// Enable custom actions for them.
	updateSettings(array(
		'ca_enabled' => 1,
		'ca_cache' => '',
		'ca_menu_cache' => serialize(array()),
	));
}

// We'll need a table.
$columns = array(
	array(
		'name' => 'id_action',
		'type' => 'smallint',
		'size' => 5,
		'null' => false,
		'auto' => true,
	),
	array(
		'name' => 'id_parent',
		'type' => 'smallint',
		'size' => 5,
		'default' => '0',
		'null' => false,
	),
	array(
		'name' => 'name',
		'type' => 'tinytext',
		'default' => '',
		'null' => false,
	),
	array(
		'name' => 'url',
		'type' => 'varchar',
		'size' => 40,
		'default' => '',
		'null' => false,
	),
	array(
		'name' => 'enabled',
		'type' => 'tinyint',
		'size' => 4,
		'default' => '0',
		'null' => false,
	),
	array(
		'name' => 'permissions_mode',
		'type' => 'tinyint',
		'size' => 4,
		'default' => '0',
		'null' => false,
	),
	array(
		'name' => 'action_type',
		'type' => 'tinyint',
		'size' => 4,
		'default' => '0',
		'null' => false,
	),
	array(
		'name' => 'menu',
		'type' => 'tinyint',
		'size' => 4,
		'default' => '0',
		'null' => false,
	),
	array(
		'name' => 'header',
		'type' => 'text',
		'default' => '',
		'null' => false,
	),
	array(
		'name' => 'body',
		'type' => 'mediumtext',
		'default' => '',
		'null' => false,
	),
	array(
		'name' => 'id_author',
		'type' => 'int',
		'default' => '0',
		'null' => false,
	),
);

$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_action'),
	),
	array(
		'type' => 'index',
		'columns' => array('url'),
	),
);

$smcFunc['db_create_table']('{db_prefix}custom_actions', $columns, $indexes, array(), 'update_remove');

?>