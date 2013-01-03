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

$core = array();

$core['title'] = 'Game Website';
$core['version'] = '1.0';
$core['cookie'] = 'gw1998';
$core['clean_url'] = false;

$core['site_url'] = '';
$core['site_dir'] = dirname(__FILE__);

$core['root_dir'] = $core['site_dir'] . '/library';
$core['includes_dir'] = $core['root_dir'] . '/includes';
$core['modules_dir'] = $core['root_dir'] . '/modules';

$db = array();

$db['server'] = '';
$db['name'] = '';
$db['user'] = '';
$db['password'] = '';