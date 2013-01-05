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

function profile_main()
{
	global $core, $template, $user;

	$request = db_query("
		SELECT email_address
		FROM user
		WHERE id_user = $user[id]
		LIMIT 1");
	list ($template['email_address']) = db_fetch_row($request);
	db_free_result($request);

	if (!empty($_POST['save']))
	{
		$values = array();
		$fields = array(
			'email_address' => 'email',
			'choose_password' => 'password',
			'verify_password' => 'password',
			'current_password' => 'password',
		);

		foreach ($fields as $field => $type)
		{
			if ($type === 'password')
				$values[$field] = !empty($_POST[$field]) ? sha1($_POST[$field]) : '';
			elseif ($type === 'email')
				$values[$field] = !empty($_POST[$field]) && preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', $_POST[$field]) ? $_POST[$field] : '';
		}

		$request = db_query("
			SELECT password
			FROM user
			WHERE id_user = $user[id]
			LIMIT 1");
		list ($current_password) = db_fetch_row($request);
		db_free_result($request);

		if ($current_password !== $values['current_password'])
			fatal_error('The password entered is not correct!');

		if ($values['choose_password'] !== $values['verify_password'])
			fatal_error('The new passwords entered do not match.');

		if ($values['email_address'] === '')
			fatal_error('You did not enter a valid email address!');

		$changes = array();
		if ($values['email_address'] !== $template['email_address'])
			$changes[] = "email_address = '$values[email_address]'";
		if ($values['choose_password'] !== '')
			$changes[] = "password = '$values[verify_password]'";

		if (!empty($changes))
		{
			db_query("
				UPDATE user
				SET " . implode(', ', $changes) . "
				WHERE id_user = $user[id]
				LIMIT 1");
		}

		if ($values['choose_password'] !== '')
			redirect(build_url('login'));
		else
			redirect(build_url('profile'));
	}

	$template['page_title'] = 'Edit Profile';
	$core['current_template'] = 'profile_main';
}