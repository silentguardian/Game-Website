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

function template_list_main()
{
	global $template;

	echo '
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
						<a class="btn" href="', build_url(array('module' => 'view', 'game' => $game['id']), false), '">View</a>
					</td>
				</tr>';
	}

	echo '
			</tbody>
		</table>';
}