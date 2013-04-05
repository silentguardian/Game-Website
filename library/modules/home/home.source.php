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

function home_main()
{
	global $core, $template;

	$request = db_query("
		SELECT id_game, name, played
		FROM game
		ORDER BY played DESC
		LIMIT 5");
	$template['popular_games'] = array();
	while ($row = db_fetch_assoc($request))
	{
		$template['popular_games'][] = array(
			'name' => $row['name'],
			'href' => build_url(array('game', 'view', $row['id_game'])),
			'played' => $row['played'],
		);
	}
	db_free_result($request);

	$request = db_query("
		SELECT id_game, name, created
		FROM game
		ORDER BY id_game DESC
		LIMIT 5");
	$template['recent_games'] = array();
	while ($row = db_fetch_assoc($request))
	{
		$template['recent_games'][] = array(
			'name' => $row['name'],
			'href' => build_url(array('game', 'view', $row['id_game'])),
			'created' => strftime('%d %B %Y', $row['created']),
		);
	}
	db_free_result($request);

	$request = db_query("
		SELECT u.username, p.id_level
		FROM progress AS p
			INNER JOIN user AS u ON (u.id_user = p.id_user)
		ORDER BY p.id_level DESC
		LIMIT 5");
	$template['top_players'] = array();
	while ($row = db_fetch_assoc($request))
	{
		$template['top_players'][] = array(
			'username' => $row['username'],
			'progress' => $row['id_level'],
		);
	}
	db_free_result($request);

	$request = db_query("
		SELECT username, registered
		FROM user
		ORDER BY id_user DESC
		LIMIT 5");
	$template['latest_players'] = array();
	while ($row = db_fetch_assoc($request))
	{
		$template['latest_players'][] = array(
			'username' => $row['username'],
			'registered' => strftime('%d %B %Y', $row['registered']),
		);
	}
	db_free_result($request);

	$request = db_query("
		SELECT COUNT(id_user)
		FROM user
		LIMIT 1");
	list ($template['total_user']) = db_fetch_row($request);
	db_free_result($request);

	$request = db_query("
		SELECT COUNT(id_game)
		FROM game
		LIMIT 1");
	list ($template['total_game']) = db_fetch_row($request);
	db_free_result($request);

	$request = db_query("
		SELECT SUM(played)
		FROM game
		LIMIT 1");
	list ($template['total_play']) = db_fetch_row($request);
	db_free_result($request);

	$template['page_title'] = 'Home';
	$core['current_template'] = 'home_main';
}