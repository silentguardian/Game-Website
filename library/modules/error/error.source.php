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

function error_main()
{
	global $core, $template;

	$template['page_title'] = 'Error';
	$core['current_template'] = 'error_main';
}