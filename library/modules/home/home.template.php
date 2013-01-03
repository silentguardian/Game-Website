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

function template_home_main()
{
	global $template;

	echo '
		<div class="page-header">
			<div class="pull-right">
				', $template['total_user'], ' users &bull; ', $template['total_game'], ' games &bull; ', $template['total_play'], ' plays
			</div>
			<h2>Game Website</h2>
		</div>
		<div class="pull-left half">
			<div class="page-header">
				<h3>Most popular games</h3>
			</div>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Game</th>
						<th>Played</th>
					</tr>
				</thead>
				<tbody>';

	if (empty($template['popular_games']))
	{
		echo '
					<tr>
						<td class="align_center" colspan="2">There are not any popular games!</td>
					</tr>';
	}

	foreach ($template['popular_games'] as $game)
	{
		echo '
					<tr>
						<td>', $game['link'], '</td>
						<td>', $game['played'], '</td>
					</tr>';
	}

	echo '
				</tbody>
			</table>
		</div>';

	echo '
		<div class="pull-right half">
			<div class="page-header">
				<h3>Recently created games</h3>
			</div>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Game</th>
						<th>Created</th>
					</tr>
				</thead>
				<tbody>';

	if (empty($template['recent_games']))
	{
		echo '
					<tr>
						<td class="align_center" colspan="2">There are not any recent games!</td>
					</tr>';
	}

	foreach ($template['recent_games'] as $game)
	{
		echo '
					<tr>
						<td>', $game['link'], '</td>
						<td>', $game['created'], '</td>
					</tr>';
	}

	echo '
				</tbody>
			</table>
		</div>
		<br class="clear" />';

	echo '
		<div class="pull-left half">
			<div class="page-header">
				<h3>Top players</h3>
			</div>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Player</th>
						<th>Score</th>
					</tr>
				</thead>
				<tbody>';

	if (empty($template['top_players']))
	{
		echo '
					<tr>
						<td class="align_center" colspan="2">There are not any top players!</td>
					</tr>';
	}

	foreach ($template['top_players'] as $player)
	{
		echo '
					<tr>
						<td>', $player['name'], '</td>
						<td>', $player['score'], '</td>
					</tr>';
	}

	echo '
				</tbody>
			</table>
		</div>';

	echo '
		<div class="pull-right half">
			<div class="page-header">
				<h3>Latest players</h3>
			</div>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Player</th>
						<th>Registered</th>
					</tr>
				</thead>
				<tbody>';

	if (empty($template['latest_players']))
	{
		echo '
					<tr>
						<td class="align_center" colspan="2">There are not any latest players!</td>
					</tr>';
	}

	foreach ($template['latest_players'] as $player)
	{
		echo '
					<tr>
						<td>', $player['name'], '</td>
						<td>', $player['registered'], '</td>
					</tr>';
	}

	echo '
				</tbody>
			</table>
		</div>
		<br class="clear" />';
}