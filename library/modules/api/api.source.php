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

	$actions = array('none', 'code', 'set', 'get', 'point');

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
	$id_game = !empty($_REQUEST['game']) ? (int) $_REQUEST['game'] : 0;
	$id_level = !empty($_REQUEST['level']) && $_REQUEST['level'] > 0 && $_REQUEST['level'] < 7 ? (int) $_REQUEST['level'] : 0;

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
	for ($level = 1; $level < 7; $level++)
	{
		for ($item = 1; $item < 11; $item++)
			$output[] = 'c' . $item . '=' . (isset($items[$item]) ? urlencode($items[$item]) : '');
	}

	exit(implode('&', $output));
}

function api_get()
{
	$id_game = !empty($_REQUEST['game']) ? (int) $_REQUEST['game'] : 0;
	$id_user = !empty($_REQUEST['user']) ? (int) $_REQUEST['user'] : 0;

	$request = db_query("
		SELECT id_level
		FROM progress
		WHERE id_game = $id_game
			AND id_user = $id_user
		LIMIT 1");
	list ($progress) = db_fetch_row($request);
	db_free_result($request);

	$output = 'progress=' . ($progress === null ? 'undefined' : (int) $progress);

	exit($output);
}

function api_set()
{
	$id_game = !empty($_REQUEST['game']) ? (int) $_REQUEST['game'] : 0;
	$id_user = !empty($_REQUEST['user']) ? (int) $_REQUEST['user'] : 0;
	$id_level = !empty($_REQUEST['level']) && $_REQUEST['level'] > 0 && $_REQUEST['level'] < 6 ? (int) $_REQUEST['level'] : 0;

	if ($id_game === 0 || $id_user === 0 || $id_level === 0)
		exit('result=missingdata');

	db_query("
		REPLACE INTO progress
			(id_game, id_user, id_level)
		VALUES
			($id_game, $id_user, $id_level)");

	$output = 'result=success';

	exit($output);
}

function api_point()
{
	$id_user = !empty($_REQUEST['user']) ? (int) $_REQUEST['user'] : 0;
	$points = !empty($_REQUEST['points']) ? (int) $_REQUEST['points'] : 0;

	if ($id_user === 0 || $points === 0)
		exit('result=missingdata');

	db_query("
		UPDATE user
		SET points = points + $points
		WHERE id_user = $id_user
		LIMIT 1");

	$output = 'result=success';

	exit($output);
}