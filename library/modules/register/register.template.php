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

function template_register_main()
{
	global $template;

	echo '
		<form class="form-horizontal" action="', build_url('register'), '" method="post">
			<fieldset>
				<legend>Register</legend>
				<div class="control-group">
					<label class="control-label" for="username">Username:</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="username" name="username" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="email_address">Email address:</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="email_address" name="email_address" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="password">Password:</label>
					<div class="controls">
						<input type="password" class="input-xlarge" id="password" name="password" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="verify_password">Verify password:</label>
					<div class="controls">
						<input type="password" class="input-xlarge" id="verify_password" name="verify_password" />
					</div>
				</div>
				<div class="form-actions">
					<input type="submit" class="btn btn-primary" name="submit" value="Submit" />
				</div>
			</fieldset>
		</form>';
}