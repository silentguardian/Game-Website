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

function register_main()
{
	global $core, $template, $user;

	if (!empty($_POST['submit']))
	{
		$values = array();
		$fields = array(
			'username' => 'username',
			'email_address' => 'email',
			'password' => 'password',
			'verify_password' => 'password',
		);

		foreach ($fields as $field => $type)
		{
			if ($type === 'password')
				$values[$field] = !empty($_POST[$field]) ? sha1($_POST[$field]) : '';
			elseif ($type === 'username')
				$values[$field] = !empty($_POST[$field]) && !preg_match('~[^A-Za-z0-9\._]~', $_POST[$field]) ? $_POST[$field] : '';
			elseif ($type === 'email')
				$values[$field] = !empty($_POST[$field]) && preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', $_POST[$field]) ? $_POST[$field] : '';
		}

		if ($values['username'] === '')
			fatal_error('You did not enter a valid username!');

		$request = db_query("
			SELECT id_user
			FROM user
			WHERE username = '$values[username]'
			LIMIT 1");
		list ($duplicate_id) = db_fetch_row($request);
		db_free_result($request);

		if (!empty($duplicate_id))
			fatal_error('The username entered is already in use!');

		if ($values['email_address'] === '')
			fatal_error('You did not enter a valid email address!');

		$request = db_query("
			SELECT id_user
			FROM user
			WHERE email_address = '$values[email_address]'
			LIMIT 1");
		list ($duplicate_id) = db_fetch_row($request);
		db_free_result($request);

		if (!empty($duplicate_id))
			fatal_error('The email address entered is already in use!');

		if ($values['password'] === '')
			fatal_error('You did not enter a valid password!');

		if ($values['password'] !== $values['verify_password'])
			fatal_error('The passwords entered do not match!');

		$unique_id = substr(md5(session_id() . mt_rand() . (string) microtime()), 0, 10);

		$request = db_query("
			SELECT id_user
			FROM user
			WHERE id_unique = '$unique_id'
			LIMIT 1");
		list ($duplicate_id) = db_fetch_row($request);
		db_free_result($request);

		if (!empty($duplicate_id))
			$unique_id = substr(md5(session_id() . mt_rand() . (string) microtime()), 0, 10);

		db_query("
			INSERT INTO user
				(id_unique, username, password, email_address, registered)
			VALUES
				('$unique_id', '$values[username]', '$values[password]', '$values[email_address]', " . time() . ")");

		redirect(build_url('login'));
	}

	$template['page_title'] = 'Register';
	$core['current_template'] = 'register_main';
}