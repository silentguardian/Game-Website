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

		if ($values['email_address'] === '')
			fatal_error('You did not enter a valid email address!');

		if ($values['password'] === '')
			fatal_error('You did not enter a valid password!');

		if ($values['password'] !== $values['verify_password'])
			fatal_error('The passwords entered do not match!');

		db_query("
			INSERT INTO user
				(username, password, email_address, registered)
			VALUES
				('$values[username]', '$values[password]', '$values[email_address]', " . time() . ")");

		redirect(build_url('login'));
	}

	$template['page_title'] = 'Register';
	$core['current_template'] = 'register_main';
}