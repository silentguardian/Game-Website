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

function template_profile_main()
{
	global $core, $template, $user;

	echo '
		<form class="form-horizontal" action="', build_url('profile'), '" method="post">
			<fieldset>
				<legend>Edit Profile</legend>
				<div class="control-group">
					<label class="control-label" for="email_address">Email address:</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="email_address" name="email_address" value="', $template['email_address'], '" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="choose_password">Choose password:</label>
					<div class="controls">
						<input type="password" class="input-xlarge" id="choose_password" name="choose_password" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="verify_password">Verify password:</label>
					<div class="controls">
						<input type="password" class="input-xlarge" id="verify_password" name="verify_password" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="current_password">Current password:</label>
					<div class="controls">
						<input type="password" class="input-xlarge" id="current_password" name="current_password" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="points">Coins:</label>
					<div class="controls">
						<span class="input-xlarge uneditable-input" id="coins">', $user['coins'], '</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="points">Points:</label>
					<div class="controls">
						<span class="input-xlarge uneditable-input" id="points">', $user['points'], '</span>
					</div>
				</div>';

	foreach ($core['categories'] as $id_category => $namespace)
	{
		echo '
				<div class="control-group">
		  			<label class="control-label" for="', $namespace, '">', $template['categories'][$id_category], ':</label>
					<div class="controls">
						<select id="', $namespace, '" name="', $namespace, '">
							<option value="0">Default</option>';

		foreach ($template[$namespace . 's'] as $id => $name)
		{
			echo '
							<option value="', $id, '"', ($id == $user[$namespace] ? ' selected="selected"' : ''), '>', $name, '</option>';
		}

		echo '
						</select>
			  		</div>
				</div>';
	}

	echo '
				<div class="form-actions">
					<input type="submit" class="btn btn-primary" name="save" value="Save changes" />
				</div>
			</fieldset>
		</form>';
}