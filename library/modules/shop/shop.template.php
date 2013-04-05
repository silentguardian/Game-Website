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

function template_shop_list()
{
	global $core, $template, $user;

	echo '
		<div class="page-header">
			<div class="pull-right">';

	if ($template['can_manage'])
	{
		echo '
				<a class="btn btn-warning" href="', build_url(array('shop', 'edit')), '">Add Item</a>';
	}

	foreach ($core['categories'] as $id_category => $name)
	{
		echo '
				<a class="btn', ($core['current_category'] == $id_category ? ' btn-primary' : ''), '" href="', build_url(array('shop', 'list', $id_category)), '">', $name, '</a>';
	}

	echo '
			</div>
			<h2>Item List</h2>
		</div>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th class="align_center">Name</th>
					<th class="align_center">Description</th>
					<th class="align_center">Cost</th>
					<th class="align_center">Bought</th>
					<th class="align_center">Actions</th>
				</tr>
			</thead>
			<tbody>';

	if (empty($template['items']))
	{
		echo '
				<tr>
					<td class="align_center" colspan="6">There are not any items available!</td>
				</tr>';
	}

	foreach ($template['items'] as $item)
	{
		echo '
				<tr>
					<td>', $item['name'], '</td>
					<td>', $item['description'], '</td>
					<td class="span2 align_center">', $item['cost'], '</td>
					<td class="span2 align_center">', $item['bought'], '</td>
					<td class="span', ($template['can_manage'] ? '3' : '2'), ' align_center">
						<a class="btn btn-success', ($item['can_buy'] ? '' : ' disabled'), '" href="', build_url(array('shop', 'buy', $item['id'])), '">Buy</a>';

			if ($template['can_manage'])
			{
				echo '
						<a class="btn btn-primary" href="', build_url(array('shop', 'edit', $item['id'])), '">Edit</a>
						<a class="btn btn-danger" href="', build_url(array('shop', 'delete', $item['id'])), '">Delete</a>';
			}

			echo '
					</td>
				</tr>';
	}

	echo '
			</tbody>
		</table>';
}

function template_shop_edit()
{
	global $core, $template;

	echo '
		<form class="form-horizontal" action="', build_url(array('shop', 'edit')), '" method="post">
			<fieldset>
				<legend>', (!$template['item']['is_new'] ? 'Edit' : 'Add'), ' Item</legend>
				<div class="control-group">
					<label class="control-label" for="name">Name:</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="name" name="name" value="', $template['item']['name'], '" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="description">Description:</label>
					<div class="controls">
						<textarea class="input-xlarge" id="description" name="description" rows="3">', $template['item']['description'], '</textarea>
					</div>
				</div>
				<div class="control-group">
		  			<label class="control-label" for="category">Category:</label>
					<div class="controls">
						<select id="category" name="id_category">';

	foreach ($core['categories'] as $id_category => $name)
	{
		echo '
							<option value="', $id_category, '"', ($id_category == $template['item']['id_category'] ? ' selected="selected"' : ''), '>', $name, '</option>';
	}

	echo '
						</select>
			  		</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="name">Cost:</label>
					<div class="controls">
						<input type="text" class="input-large" id="cost" name="cost" value="', $template['item']['cost'], '" />
					</div>
				</div>
				<div class="form-actions">
					<input type="submit" class="btn btn-primary" name="save" value="Save changes" />
					<input type="submit" class="btn" name="cancel" value="Cancel" />
				</div>
			</fieldset>
			<input type="hidden" name="shop" value="', $template['item']['id'], '" />
		</form>';
}