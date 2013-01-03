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

	$template['popular_games'] = array();
	$template['recent_games'] = array();
	$template['top_players'] = array();
	$template['latest_players'] = array();

	$template['total_user'] = 0;
	$template['total_game'] = 0;
	$template['total_play'] = 0;

	$template['page_title'] = 'Home';
	$core['current_template'] = 'home_main';
}