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

function shop_main()
{
	global $core, $template;

	$core['categories'] = array('Hats', 'Characters', 'Screws');

	$actions = array('list', 'buy', 'edit', 'delete');

	$core['current_action'] = 'list';
	if (!empty($_REQUEST['action']) && in_array($_REQUEST['action'], $actions))
		$core['current_action'] = $_REQUEST['action'];

	$core['current_category'] = 0;
	if (!empty($_REQUEST['shop']) && isset($core['categories'][(int) $_REQUEST['shop']]))
		$core['current_category'] = (int) $_REQUEST['shop'];

	call_user_func($core['current_module'] . '_' . $core['current_action']);
}

function shop_list()
{
	global $core, $template, $user;

	$request = db_query("
		SELECT
			id_item, name, description, cost, bought
		FROM shop
		WHERE id_category = $core[current_category]
		ORDER BY name");
	$template['items'] = array();
	while ($row = db_fetch_assoc($request))
	{
		$template['items'][$row['id_item']] = array(
			'id' => $row['id_item'],
			'name' => $row['name'],
			'description' => $row['description'],
			'cost' => $row['cost'],
			'bought' => $row['bought'],
			'can_buy' => $row['cost'] <= $user['coins'],
		);
	}
	db_free_result($request);

	$request = db_query("
		SELECT id_item
		FROM inventory
		WHERE id_user = $user[id]");
	$inventory = array();
	while ($row = db_fetch_assoc($request))
		$inventory[] = $row['id_item'];
	db_free_result($request);

	foreach ($template['items'] as $id => $item)
	{
		if (in_array($id, $inventory))
			$template['items'][$id]['can_buy'] = false;
	}

	$template['can_manage'] = $user['admin'];
	$template['page_title'] = 'Item List';
	$core['current_template'] = 'shop_list';
}

function shop_edit()
{
	global $core, $template, $user;

	$id_item = !empty($_REQUEST['shop']) ? (int) $_REQUEST['shop'] : 0;
	$is_new = empty($id_item);

	if ($is_new)
	{
		$template['item'] = array(
			'is_new' => true,
			'id' => 0,
			'id_category' => 0,
			'name' => '',
			'description' => '',
			'namespace' => '',
			'cost' => 0,
		);
	}
	else
	{
		$request = db_query("
			SELECT id_item, id_category, name, description, namespace, cost
			FROM shop
			WHERE id_item = $id_item
			LIMIT 1");
		while ($row = db_fetch_assoc($request))
		{
			$template['item'] = array(
				'is_new' => false,
				'id' => $row['id_item'],
				'id_category' => $row['id_category'],
				'name' => $row['name'],
				'description' => $row['description'],
				'namespace' => $row['namespace'],
				'cost' => $row['cost'],
			);
		}
		db_free_result($request);

		if (!isset($template['item']))
			fatal_error('The game requested does not exist!');
		elseif (!$user['admin'])
			fatal_error('You are not allowed to carry out this action!');
	}

	if (!empty($_POST['save']))
	{
		$values = array();
		$fields = array(
			'name' => 'string',
			'description' => 'string',
			'namespace' => 'string',
			'id_category' => 'integer',
			'cost' => 'integer',
		);

		foreach ($fields as $field => $type)
		{
			if ($type === 'string')
				$values[$field] = !empty($_POST[$field]) ? htmlspecialchars($_POST[$field], ENT_QUOTES) : '';
			elseif ($type === 'integer')
				$values[$field] = !empty($_POST[$field]) ? (int) $_POST[$field] : 0;
		}

		if ($values['name'] === '')
			fatal_error('Name field cannot be empty!');

		if ($values['namespace'] === '')
			fatal_error('Namespace field cannot be empty!');

		if (preg_replace('~[A-Za-z0-9_]~', '', $values['namespace']) !== '')
			fatal_error('Namespace can only include alphanumeric characters and underscores.');

		$request = db_query("
			SELECT id_item
			FROM shop
			WHERE namespace = '$values[namespace]'
				AND id_item != $id_item
			LIMIT 1");
		list ($duplicate_id) = db_fetch_row($request);
		db_free_result($request);

		if (!empty($duplicate_id))
			fatal_error('The namespace entered is already in use!');

		if ($is_new)
		{
			$insert = array();
			foreach ($values as $field => $value)
				$insert[$field] = "'" . $value . "'";

			db_query("
				INSERT INTO shop
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
				UPDATE shop
				SET " . implode(', ', $update) . "
				WHERE id_item = $id_item
				LIMIT 1");
		}
	}

	if (!empty($_POST['save']) || !empty($_POST['cancel']))
		redirect(build_url(array('shop', 'list', $values['id_category'])));

	$template['page_title'] = (!$is_new ? 'Edit' : 'Add') . ' Item';
	$core['current_template'] = 'shop_edit';
}

function shop_buy()
{
	global $user;

	$id_item = !empty($_REQUEST['shop']) ? (int) $_REQUEST['shop'] : 0;

	$request = db_query("
		SELECT id_item, id_category, cost
		FROM shop
		WHERE id_item = $id_item
		LIMIT 1");
	list ($id_item, $id_category, $cost) = db_fetch_row($request);
	db_free_result($request);

	if (empty($id_item))
		fatal_error('The item requested does not exist!');
	elseif ($user['coins'] < $cost)
		fatal_error('You do not have enough coins to buy this item!');

	db_query("
		REPLACE INTO inventory
			(id_user, id_item)
		VALUES
			($user[id], $id_item)");

	db_query("
		UPDATE user
		SET coins = coins - $cost
		WHERE id_user = $user[id]
		LIMIT 1");

	db_query("
		UPDATE shop
		SET bought = bought + 1
		WHERE id_item = $id_item
		LIMIT 1");

	redirect(build_url(array('shop', 'list', $id_category)));
}

function shop_delete()
{
	global $user;

	$id_item = !empty($_REQUEST['shop']) ? (int) $_REQUEST['shop'] : 0;

	$request = db_query("
		SELECT id_item, id_category
		FROM shop
		WHERE id_item = $id_item
		LIMIT 1");
	list ($id_item, $id_category) = db_fetch_row($request);
	db_free_result($request);

	if (empty($id_item))
		fatal_error('The game requested does not exist!');
	elseif (!$user['admin'])
		fatal_error('You are not allowed to carry out this action!');

	db_query("
		DELETE FROM shop
		WHERE id_item = $id_item
		LIMIT 1");

	db_query("
		DELETE FROM inventory
		WHERE id_item = $id_item");

	redirect(build_url(array('shop', 'list', $id_category)));
}