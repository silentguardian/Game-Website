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

function list_main()
{
	global $core, $template;

	$request = db_query("
		SELECT
			g.id_game, g.name, g.id_user, g.created,
			g.played, g.rating, u.username
		FROM game AS g
			INNER JOIN user AS u ON (u.id_user = g.id_user)
		ORDER BY g.name");
	$template['games'] = array();
	while ($row = db_fetch_assoc($request))
	{
		$template['games'][$row['id_game']] = array(
			'id' => $row['id_game'],
			'name' => $row['name'],
			'creator' => $row['username'],
			'created' => strftime('%d/%m/%y', $row['created']),
			'played' => $row['played'],
			'rating' => $row['rating'],
		);
	}
	db_free_result($request);

	$template['page_title'] = 'Games';
	$core['current_template'] = 'list_main';
}