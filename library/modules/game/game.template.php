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
		<div class="page-header">';

	if ($template['can_create'])
	{
		echo '
			<div class="pull-right">
				<a class="btn btn-warning" href="', build_url(array('game', 'edit')), '">Create Game</a>
			</div>';
	}

	echo '
			<h2>Game List</h2>
		</div>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Name</th>
					<th class="align_center">Played</th>
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
					<td class="align_center" colspan="6">There are not any games added yet!</td>
				</tr>';
	}

	foreach ($template['games'] as $game)
	{
		echo '
				<tr>
					<td>', $game['name'], '</td>
					<td class="span2 align_center">', $game['played'], '</td>
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
				<a class="btn btn-success" href="', build_url(array('game', 'play', $template['game']['id'])), '">Play</a>';

	if ($template['can_manage'])
	{
		echo '
				<a class="btn btn-warning" href="', build_url(array('game', 'customize', $template['game']['id'])), '">Customize</a>
				<a class="btn btn-primary" href="', build_url(array('game', 'edit', $template['game']['id'])), '">Edit</a>
				<a class="btn btn-danger" href="', build_url(array('game', 'delete', $template['game']['id'])), '">Delete</a>';
	}

	echo '
			</div>
			<h2>View Game - ', $template['game']['name'], '</h2>
		</div>
		<dl class="dl-horizontal well">
			<dt>Name:</dt>
			<dd>', $template['game']['name'], '</dd>
			<dt>Description:</dt>
			<dd>', nl2br($template['game']['description']), '</dd>
			<dt>Creator:</dt>
			<dd>', $template['game']['creator']['name'], '</dd>
			<dt>Created:</dt>
			<dd>', $template['game']['created'], '</dd>
			<dt>Played:</dt>
			<dd>', $template['game']['played'], '</dd>
		</dl>
		<div class="page-header">
			<h3>Comments</h3>
		</div>';

	if (empty($template['game']['comments']))
	{
		echo '
		<div class="well">
			There are not any comments for this game yet. Be the first one to comment!
		</div>';
	}
	else
	{
		foreach ($template['game']['comments'] as $comment)
		{
			echo '
		<div class="well">
			', $comment['body'], '
			<hr />';
			if ($comment['can_delete'])
			{
				echo '
			<div class="pull-right">
				<a class="btn btn-danger" href="', build_url(array('game', 'comment', $template['game']['id'], $comment['id'])), '">Delete</a>
			</div>';
			}

			echo '
			<div class="muted">
				Comment by ', $comment['user']['name'], ' on ', $comment['created'], '
			</div>
		</div>';
		}
	}

	if ($template['can_comment'])
	{
		echo '
		<form class="form-horizontal" action="', build_url(array('game', 'comment', $template['game']['id'])), '" method="post">
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="comment">Comment:</label>
					<div class="controls">
						<textarea class="input-xlarge span5" id="comment" name="comment" rows="3"></textarea>
					</div>
				</div>
				<div class="form-actions">
					<input type="submit" class="btn btn-primary" name="submit" value="Submit" />
				</div>
			</fieldset>
		</form>';
	}
}

function template_game_play()
{
	global $core, $template, $user;

	echo '
		<div class="page-header">
			<div class="pull-right">
				<a class="btn" href="', build_url(array('game', 'view', $template['game']['id'])), '">Back to Game</a>
			</div>
			<h2>Play Game - ', $template['game']['name'], '</h2>
		</div>
		<div class="align_center">
			<embed src="', $core['site_url'], 'media/movie.swf" flashvars="unique_id=', $user['unique'], '&game_id=', $template['game']['id'], '" width="800" height="600"></embed>
		</div>';
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

function template_game_customize()
{
	global $core, $template;

	echo '
		<div class="page-header">
			<div class="pull-right">
				<a class="btn" href="', build_url(array('game', 'view', $template['game']['id'])), '">Back to Game</a>
			</div>
			<h2>Customize Game - ', $template['game']['name'], '</h2>
		</div>';

	if ($template['empty_levels'])
	{
		echo '
		<div class="alert alert-error">
			<a class="close" data-dismiss="alert" href="#">×</a>
			<h4 class="alert-heading">Warning!</h4>
			You have not set any level codes for level(s) ', implode(', ', $template['empty_levels']), '. Unless you enter at least one level code for each level, users will not be able to play your game.
		</div>';
	}

	echo '
		<ul class="thumbnails">';

	for ($level = 1; $level < 6; $level++)
	{
		echo '
			<li class="span4">
				<div class="thumbnail">
					<img src="', $core['site_url'], 'interface/img/level.png" alt="">
					<div class="detail">
						<a class="btn btn-warning" href="', build_url(array('game', 'customize', $template['game']['id'], $level)), '">Customize Level</a>
						<h3>Level ', $level, (in_array($level, $template['empty_levels']) ? ' <span class="label label-important">no level code</span>' : ''), '</h3>
					</p>
				</div>
			</li>';
	}

	echo '
		</ul>';
}

function template_game_customize_level()
{
	global $template;

	echo '
		<form class="form-horizontal" action="', build_url(array('game', 'customize', $template['game']['id'], $template['game']['level'])), '" method="post">
			<fieldset>
				<div class="pull-right">
					<a class="btn" href="', build_url(array('game', 'view', $template['game']['id'])), '">Back to Game</a>
					<a class="btn" href="', build_url(array('game', 'customize', $template['game']['id'])), '">Back to Levels</a>
				</div>
				<legend>Customize Game - ', $template['game']['name'], ' - Level ', $template['game']['level'], '</legend>';

	for ($item = 1; $item < 11; $item++)
	{
		echo '
				<div class="control-group">
					<label class="control-label" for="code_', $item, '">Level code ', $item, ':</label>
					<div class="controls">
						<input type="text" class="input-xlarge" maxlength="10" id="code_', $item, '" name="code[', $item, ']"', isset($template['items'][$item]) ? ' value="' . $template['items'][$item] . '"' : '' , ' />
					</div>
				</div>';
	}

	echo '
				<div class="form-actions">
					<input type="submit" class="btn btn-primary" name="save" value="Save changes" />
					<input type="submit" class="btn" name="cancel" value="Cancel" />
				</div>
			</fieldset>
		</form>';
}