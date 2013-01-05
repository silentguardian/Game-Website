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

function template_about_main()
{
	global $template;

	echo '
		<div class="page-header">
			<h2>About</h2>
		</div>
		<p class="content">
			Game Website is the base that will be hosting a game project.
		</p>
		<p class="content">
			This tool is coded in <a href="http://php.net">PHP</a> and uses <a href="http://twitter.github.com/bootstrap">Bootstrap</a> CSS framework.
		</p>';
}