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

function game_main()
{
	global $core;

	$actions = array('list', 'view', 'edit', 'comment', 'delete');

	$core['current_action'] = 'list';
	if (!empty($_REQUEST['action']) && in_array($_REQUEST['action'], $actions))
		$core['current_action'] = $_REQUEST['action'];

	call_user_func($core['current_module'] . '_' . $core['current_action']);
}

function game_list()
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
			'created' => strftime('%d %B %Y', $row['created']),
			'played' => $row['played'],
			'rating' => $row['rating'],
		);
	}
	db_free_result($request);

	$template['page_title'] = 'Game List';
	$core['current_template'] = 'game_list';
}

function game_view()
{
	global $core, $template;

	$id_game = !empty($_REQUEST['game']) ? (int) $_REQUEST['game'] : 0;

	$request = db_query("
		SELECT
			g.id_game, g.name, g.description, g.id_user, g.created,
			g.played, g.rating, u.username, g.comments
		FROM game AS g
			INNER JOIN user AS u ON (u.id_user = g.id_user)
		WHERE g.id_game = $id_game
		LIMIT 1");
	$template['game'] = array();
	while ($row = db_fetch_assoc($request))
	{
		$template['game'] = array(
			'id' => $row['id_game'],
			'name' => $row['name'],
			'description' => $row['description'],
			'creator' => $row['username'],
			'created' => strftime('%d %B %Y', $row['created']),
			'played' => $row['played'],
			'rating' => $row['rating'],
			'comments' => $row['comments'],
		);
	}
	db_free_result($request);

	if (empty($template['game']))
		fatal_error('The game requested does not exist!');

	if (!empty($template['game']['comments']))
	{
		$request = db_query("
			SELECT
				c.id_comment, c.id_user, c.body,
				c.created, u.username
			FROM comment AS c
				INNER JOIN user AS u ON (u.id_user = c.id_user)
			WHERE c.id_game = $id_game
			ORDER BY c.id_comment DESC");
		$template['game']['comments'] = array();
		while ($row = db_fetch_assoc($request))
		{
			$template['game']['comments'][$row['id_comment']] = array(
				'id' => $row['id_comment'],
				'user' => array(
					'id' => $row['id_user'],
					'name' => $row['username'],
				),
				'body' => $row['body'],
				'created' => strftime('%d %B %Y, %H:%M', $row['created']),
			);
		}
		db_free_result($request);
	}

	$template['page_title'] = 'View Game - ' . $template['game']['name'];
	$core['current_template'] = 'game_view';
}

function game_edit()
{
	global $core, $template, $user;

	$id_game = !empty($_REQUEST['game']) ? (int) $_REQUEST['game'] : 0;
	$is_new = empty($id_game);

	if (!empty($_POST['save']))
	{
		$values = array();
		$fields = array(
			'name' => 'string',
			'description' => 'string',
		);

		foreach ($fields as $field => $type)
		{
			if ($type === 'string')
				$values[$field] = !empty($_POST[$field]) ? htmlspecialchars($_POST[$field], ENT_QUOTES) : '';
		}

		if ($values['name'] === '')
			fatal_error('Name field cannot be empty!');

		if ($is_new)
		{
			$fields['id_user'] = 'integer';
			$fields['created'] = 'integer';

			$values['id_user'] = $user['id'];
			$values['created'] = time();

			$insert = array();
			foreach ($values as $field => $value)
				$insert[$field] = "'" . $value . "'";

			db_query("
				INSERT INTO game
					(" . implode(', ', array_keys($insert)) . ")
				VALUES
					(" . implode(', ', $insert) . ")");
		}
		else
		{
			$update = array();
			foreach ($values as $field => $value)
				$update[] = $field . " = '" . $value . "'";

			db_query("
				UPDATE game
				SET " . implode(', ', $update) . "
				WHERE id_game = $id_game
				LIMIT 1");
		}
	}

	if (!empty($_POST['save']) || !empty($_POST['cancel']))
		redirect(build_url('game'));

	if ($is_new)
	{
		$template['game'] = array(
			'is_new' => true,
			'id' => 0,
			'name' => '',
			'description' => '',
		);
	}
	else
	{
		$request = db_query("
			SELECT id_game, name, description
			FROM game
			WHERE id_game = $id_game
			LIMIT 1");
		while ($row = db_fetch_assoc($request))
		{
			$template['game'] = array(
				'is_new' => false,
				'id' => $row['id_game'],
				'name' => $row['name'],
				'description' => $row['description'],
			);
		}
		db_free_result($request);

		if (!isset($template['game']))
			fatal_error('The game requested does not exist!');
	}

	$template['page_title'] = (!$is_new ? 'Edit' : 'Add') . ' Game';
	$core['current_template'] = 'game_edit';
}

function game_comment()
{
	global $user;

	$id_game = !empty($_REQUEST['game']) ? (int) $_REQUEST['game'] : 0;
	$id_comment = !empty($_GET['comment']) ? (int) $_GET['comment'] : 0;
	$comment = !empty($_POST['comment']) ? htmlspecialchars($_POST['comment'], ENT_QUOTES) : '';

	$request = db_query("
		SELECT id_game
		FROM game
		WHERE id_game = $id_game
		LIMIT 1");
	list ($id_game) = db_fetch_row($request);
	db_free_result($request);

	if (empty($id_game))
		fatal_error('The game requested does not exist!');

	if (!empty($comment) && empty($_POST['submit']))
		fatal_error('You did not submit the form!');
	elseif (!empty($comment))
	{
		db_query("
			INSERT INTO comment
				(id_game, id_user, body, created)
			VALUES
				($id_game, $user[id], '$comment', " . time() . ")");

		db_query("
			UPDATE game
			SET comments = comments + 1
			WHERE id_game = $id_game
			LIMIT 1");
	}

	if (!empty($id_comment))
	{
		$request = db_query("
			SELECT id_comment
			FROM comment
			WHERE id_comment = $id_comment
			LIMIT 1");
		list ($id_comment) = db_fetch_row($request);
		db_free_result($request);

		if (empty($id_comment))
			fatal_error('The comment requested does not exist!');

		db_query("
			DELETE FROM comment
			WHERE id_comment = $id_comment
			LIMIT 1");

		db_query("
			UPDATE game
			SET comments = comments - 1
			WHERE id_game = $id_game
			LIMIT 1");
	}

	redirect(build_url(array('game', 'view', $id_game)));
}

function game_delete()
{
	$id_game = !empty($_REQUEST['game']) ? (int) $_REQUEST['game'] : 0;

	$request = db_query("
		SELECT id_game
		FROM game
		WHERE id_game = $id_game
		LIMIT 1");
	list ($id_game) = db_fetch_row($request);
	db_free_result($request);

	if (empty($id_game))
		fatal_error('The game requested does not exist!');

	db_query("
		DELETE FROM game
		WHERE id_game = $id_game
		LIMIT 1");

	db_query("
		DELETE FROM comment
		WHERE id_game = $id_game");

	db_query("
		DELETE FROM play
		WHERE id_game = $id_game");

	db_query("
		DELETE FROM rating
		WHERE id_game = $id_game");

	redirect(build_url('game'));
}