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

	if (!empty($_POST['save']))
	{
		$values = array();
		$fields = array(
			'choose_password' => 'password',
			'verify_password' => 'password',
			'current_password' => 'password',
		);

		foreach ($fields as $field => $type)
		{
			if ($type === 'password')
				$values[$field] = !empty($_POST[$field]) ? sha1($_POST[$field]) : '';
		}

		if ($values['choose_password'] === '')
			redirect(build_url());

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

		db_query("
			UPDATE user
			SET password = '$values[verify_password]'
			WHERE id_user = $user[id]
			LIMIT 1");

		redirect(build_url('login'));
	}

	$template['page_title'] = 'Edit Profile';
	$core['current_template'] = 'profile_main';
}