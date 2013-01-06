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

function template_game_list()
{
	global $template;

	echo '
		<div class="page-header">
			<div class="pull-right">
				<a class="btn btn-warning" href="', build_url(array('game', 'edit')), '">Create Game</a>
			</div>
			<h2>Game List</h2>
		</div>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Name</th>
					<th class="align_center">Played</th>
					<th class="align_center">Rating</th>
					<th class="align_center">Creator</th>
					<th class="align_center">Created</th>
					<th class="align_center">Actions</th>
				</tr>
			</thead>
			<tbody>';

	if (empty($template['games']))
	{
		echo '
				<tr>
					<td class="align_center" colspan="7">There are not any games added yet!</td>
				</tr>';
	}

	foreach ($template['games'] as $game)
	{
		echo '
				<tr>
					<td>', $game['name'], '</td>
					<td class="span2 align_center">', $game['played'], '</td>
					<td class="span2 align_center">', $game['rating'], '</td>
					<td class="span2 align_center">', $game['creator'], '</td>
					<td class="span2 align_center">', $game['created'], '</td>
					<td class="span2 align_center">
						<a class="btn btn-info" href="', build_url(array('game', 'view', $game['id'])), '">View</a>
						<a class="btn btn-success" href="', build_url(array('game', 'play', $game['id'])), '">Play</a>
					</td>
				</tr>';
	}

	echo '
			</tbody>
		</table>';
}

function template_game_view()
{
	global $template;

	echo '
		<div class="page-header">
			<div class="pull-right">
				<a class="btn" href="', build_url('game'), '">Back to List</a>
				<a class="btn btn-success" href="', build_url(array('game', 'play', $template['game']['id'])), '">Play</a>
				<a class="btn btn-primary" href="', build_url(array('game', 'modify', $template['game']['id'])), '">Modify</a>
				<a class="btn btn-primary" href="', build_url(array('game', 'edit', $template['game']['id'])), '">Edit</a>
				<a class="btn btn-danger" href="', build_url(array('game', 'delete', $template['game']['id'])), '">Delete</a>
			</div>
			<h2>View Game - ', $template['game']['name'], '</h2>
		</div>
		<dl class="dl-horizontal">
			<dt>Name:</dt>
			<dd>', $template['game']['name'], '</dd>
			<dt>Description:</dt>
			<dd>', nl2br($template['game']['description']), '</dd>
			<dt>Creator:</dt>
			<dd>', $template['game']['creator'], '</dd>
			<dt>Created:</dt>
			<dd>', $template['game']['created'], '</dd>
			<dt>Played:</dt>
			<dd>', $template['game']['played'], '</dd>
			<dt>Rating:</dt>
			<dd>', $template['game']['rating'], '</dd>
			<dt>Comments:</dt>
			<dd>', $template['game']['comments'], '</dd>
		</dl>';
}

function template_game_edit()
{
	global $template;

	echo '
		<form class="form-horizontal" action="', build_url(array('game', 'edit')), '" method="post">
			<fieldset>
				<legend>', (!$template['game']['is_new'] ? 'Edit' : 'Create'), ' Game</legend>
				<div class="control-group">
					<label class="control-label" for="name">Name:</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="name" name="name" value="', $template['game']['name'], '" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="description">Description:</label>
					<div class="controls">
						<textarea class="input-xlarge" id="description" name="description" rows="3">', $template['game']['description'], '</textarea>
					</div>
				</div>
				<div class="form-actions">
					<input type="submit" class="btn btn-primary" name="save" value="Save changes" />
					<input type="submit" class="btn" name="cancel" value="Cancel" />
				</div>
			</fieldset>
			<input type="hidden" name="game" value="', $template['game']['id'], '" />
		</form>';
}