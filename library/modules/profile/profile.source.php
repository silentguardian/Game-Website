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

	$core['categories'] = array('hat', 'character', 'screw');
	$template['categories'] = array('Hat', 'Character', 'Screw');

	$request = db_query("
		SELECT id_unique, email_address
		FROM user
		WHERE id_user = $user[id]
		LIMIT 1");
	list ($template['id_unique'], $template['email_address']) = db_fetch_row($request);
	db_free_result($request);

	$request = db_query("
		SELECT s.id_item, s.id_category, s.name
		FROM inventory AS i
			INNER JOIN shop AS s ON (s.id_item = i.id_item)
		WHERE id_user = $user[id]");
	$template['hats'] = $template['characters'] = $template['screws'] = array();
	while ($row = db_fetch_assoc($request))
		$template[$core['categories'][$row['id_category']] . 's'][$row['id_item']] = $row['name'];
	db_free_result($request);

	if (!empty($_POST['save']))
	{
		$values = array();
		$fields = array(
			'email_address' => 'email',
			'choose_password' => 'password',
			'verify_password' => 'password',
			'current_password' => 'password',
			'hat' => 'integer',
			'character' => 'integer',
			'screw' => 'integer',
		);

		foreach ($fields as $field => $type)
		{
			if ($type === 'password')
				$values[$field] = !empty($_POST[$field]) ? sha1($_POST[$field]) : '';
			elseif ($type === 'email')
				$values[$field] = !empty($_POST[$field]) && preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', $_POST[$field]) ? $_POST[$field] : '';
			elseif ($type === 'integer')
				$values[$field] = !empty($_POST[$field]) ? (int) $_POST[$field] : 0;
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

		$request = db_query("
			SELECT id_user
			FROM user
			WHERE email_address = '$values[email_address]'
				AND id_user != '$user[id]'
			LIMIT 1");
		list ($duplicate_id) = db_fetch_row($request);
		db_free_result($request);

		if (!empty($duplicate_id))
			fatal_error('The email address entered is already in use!');

		foreach ($core['categories'] as $category)
		{
			if (!isset($template[$category . 's'][$values[$category]]))
				$values[$category] = 0;
		}

		$changes = array();
		if ($values['email_address'] !== $template['email_address'])
			$changes[] = "email_address = '$values[email_address]'";
		if ($values['choose_password'] !== '')
			$changes[] = "password = '$values[verify_password]'";
		foreach ($core['categories'] as $category)
		{
			if ($values[$category] != $user[$category])
				$changes[] = str_replace('character', 'charac', $category) . " = '$values[$category]'";
		}

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