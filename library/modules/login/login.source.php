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

function login_main()
{
	global $core, $template;

	if (!empty($_POST['submit']))
	{
		$username = !empty($_POST['username']) ? $_POST['username'] : '';
		$password = !empty($_POST['password']) ? $_POST['password'] : '';

		if ($username === '' || preg_match('~[^A-Za-z0-9\._]~', $username) || $password === '')
			fatal_error('Invalid username or password!');

		$request = db_query("
			SELECT id_user, password
			FROM user
			WHERE username = '$username'
			LIMIT 1");
		list ($id, $real_password) = db_fetch_row($request);
		db_free_result($request);

		$hash = sha1($password);
		if ($hash !== $real_password)
			fatal_error('Invalid username or password!');

		create_cookie(60 * 3153600, $id, $hash);

		redirect(build_url());
	}

	$template['page_title'] = 'User Login';
	$core['current_template'] = 'login_main';
}