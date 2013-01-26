<?php

/**
 * @package Game Website
 *
 * @author BG
 * @copyright 2013 BG
 * @license BSD 3-clause 
 *
 * @version 1.0
 */

if (!defined('CORE'))
	exit();

function api_main()
{
	global $core;

	$actions = array('none', 'code');

	$core['current_action'] = 'none';
	if (!empty($_REQUEST['action']) && in_array($_REQUEST['action'], $actions))
		$core['current_action'] = $_REQUEST['action'];

	call_user_func($core['current_module'] . '_' . $core['current_action']);
}

function api_none()
{
	exit('Why might you be here?!');
}

function api_code()
{
	$id_game = !empty($_REQUEST['api']) ? (int) $_REQUEST['api'] : 0;
	$id_level = !empty($_REQUEST['code']) && $_REQUEST['code'] > 0 && $_REQUEST['code'] < 6 ? (int) $_REQUEST['code'] : 0;

	$request = db_query("
		SELECT id_game
		FROM game
		WHERE id_game = $id_game
		LIMIT 1");
	list ($id_game) = db_fetch_row($request);
	db_free_result($request);

	if (empty($id_game))
		exit('error=nogame');

	$request = db_query("
		SELECT id_item, value
		FROM customize
		WHERE id_game = $id_game
			AND id_level = $id_level");
	$items = array();
	while ($row = db_fetch_assoc($request))
		$items[$row['id_item']] = $row['value'];
	db_free_result($request);

	if (empty($items))
		exit('error=nocode');

	$output = array();
	for ($level = 1; $level < 6; $level++)
	{
		for ($item = 1; $item < 11; $item++)
			$output[] = 'c' . $item . '=' . (isset($items[$item]) ? urlencode($items[$item]) : '');
	}

	exit(implode('&', $output));
}