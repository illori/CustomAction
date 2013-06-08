<?php
/**********************************************************************************
* CustomAction.php                                                                *
***********************************************************************************
* Software Version:           3.0                                                 *
**********************************************************************************/

if (!defined('SMF'))
	die('Hacking attempt...');

function ViewCustomAction()
{
	global $context, $smcFunc, $db_prefix, $txt;

	// So which custom action is this?
	$request = $smcFunc['db_query']('', '
		SELECT id_action, name, permissions_mode, action_type, header, body
		FROM {db_prefix}custom_actions
		WHERE url = {string:url}
			AND enabled = 1',
		array(
			'url' => $context['current_action'],
		)
	);

	$context['action'] = $smcFunc['db_fetch_assoc']($request);

	$smcFunc['db_free_result']($request);

	// By any chance are we in a sub-action?
	if (!empty($_REQUEST['sa']))
	{
		$request = $smcFunc['db_query']('', '
			SELECT id_action, name, permissions_mode, action_type, header, body
			FROM {db_prefix}custom_actions
			WHERE url = {string:url}
				AND enabled = 1
				AND id_parent = {int:id_parent}',
			array(
				'id_parent' => $context['action']['id_action'],
				'url' => $_REQUEST['sa'],
			)
		);

		if ($smcFunc['db_num_rows']($request) != 0)
		{
			$sub = $smcFunc['db_fetch_assoc']($request);

			$smcFunc['db_free_result']($request);

			$context['action']['name'] = $sub['name'];
			// Do we have our own permissions?
			if ($sub['permissions_mode'] != 2)
			{
				$context['action']['id_action'] = $sub['id_action'];
				$context['action']['permissions_mode'] = $sub['permissions_mode'];
			}
			$context['action']['action_type'] = $sub['action_type'];
			$context['action']['header'] = $sub['header'];
			$context['action']['body'] = $sub['body'];
		}
	}

	// Are we even allowed to be here?
	if ($context['action']['permissions_mode'] == 1)
	{
		// Standard message, please.
		$txt['cannot_ca_' . $context['action']['id_action']] = '';
		isAllowedTo('ca_' . $context['action']['id_action']);
	}

	// Do this first to allow it to be overwritten by PHP source file code.
	$context['page_title'] = $context['action']['name'];

	switch ($context['action']['action_type'])
	{
	// Any HTML headers?
	case 0:
		$context['html_headers'] .= $context['action']['header'];
		break;
	// Do we need to parse any BBC?
	case 1:
		$context['action']['body'] = parse_bbc($context['action']['body']);
		break;
	// We have some more stuff to do for PHP actions.
	case 2:
		fixPHP($context['action']['header']);
		fixPHP($context['action']['body']);

		eval($context['action']['header']);
	}

	// Get the templates sorted out!
	loadTemplate('CustomAction');
	$context['sub_template'] = 'view_custom_action';
}

// Get rid of any <? or <?php at the start of code.
function fixPHP(&$code)
{
	$code = preg_replace('~^\s*<\?(php)?~', '', $code);
}

?>