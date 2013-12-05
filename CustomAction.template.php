<?php
// Version: 3.0; CustomAction

function template_view_custom_action()
{
	global $context;
	
	switch ($context['action']['action_type'])
	{
	// HTML.
	case 0:
		echo $context['action']['body'];
		break;
	// BBC.
	case 1:
	echo '
	<div class="cat_bar">
				<h3 class="catbg"> ', $context['action']['name'],'</h3>			
			</div>
			<div class="windowbg2">
				<div class="content">', $context['action']['body'], '
				</div>
			</div><br />';
		
	//	echo $context['action']['body'];
		break;
	// PHP.
	case 2:
		eval($context['action']['body']);
		break;
	}
}

function template_show_custom_action()
{
	global $context, $txt;
	template_show_list('custom_actions');
}

function template_edit_custom_action()
{
	global $context, $txt, $scripturl;
	
	if ($context['action']['can_choose_type'] == 1)
		echo '
			<script language="JavaScript" type="text/javascript"><!-- // -->
				function updateInputBoxesType()
				{
					type = document.getElementById("type").value;
					document.getElementById("header_box").style.display = type != 0 ? "" : "none";
					document.getElementById("header_text").style.display = type == 1 ? "" : "none";
					document.getElementById("source_text").style.display = type == 2 ? "" : "none";
					document.getElementById("html_body_text").style.display = type == 1 ? "" : "none";
					document.getElementById("body_text").style.display = type == 0 ? "" : "none";
					document.getElementById("php_body_text").style.display = type == 2 ? "" : "none";
				}
			// ]', ']></script>';
	if ($context['action']['can_edit_groups'] == 1)
		echo '
			<script language="JavaScript" type="text/javascript"><!-- // -->
				function updateInputBoxesPerm()
				{
					permission_mode = document.getElementById("permissions_mode").value;
					document.getElementById("inline_permissions").style.display = permission_mode == 1 ? "" : "none";
				}
			// ]', ']></script>';

	echo '	
	<div id="admincenter">
		<form action="', $scripturl, '?action=ca_edit', $context['id_action'] ? ';id_action=' . $context['id_action'] : '', '" method="post" accept-charset="', $context['character_set'], '">
			<div class="cat_bar">
				<h3 class="catbg">
					',  $txt['custom_action_settings'], '
				</h3>
			</div>
		<div class="windowbg">
			<div class="content">
				<span><b>', $txt['custom_action_name'], ':</b>
					<input type="text" name="name" value="', $context['action']['name'], '" size="20" maxlength="255" /></span><br /><br />
					<span><b>', $txt['custom_action_url'], ':</b>
						<input type="text" name="url" value="', $context['action']['url'], '" size="20" maxlength="40" /><br /><br />
						<span class="smalltext">', $txt['custom_action_url_desc'], '</span>
					</span><br /><br />';
					if ($context['action']['can_choose_type'] == 1)
						echo '
							<span><b>', $txt['custom_action_type'], ':</b>
								<select name="type" id="type" onchange="updateInputBoxesType();">
									<option value="0" ', $context['action']['type'] == 0 ? 'selected="selected"' : '', '>', $txt['custom_action_type_0'], '</option>
									<option value="1" ', $context['action']['type'] == 1 ? 'selected="selected"' : '', '>', $txt['custom_action_type_1'], '</option>
									<option value="2" ', $context['action']['type'] == 2 ? 'selected="selected"' : '', '>', $txt['custom_action_type_2'], '</option>
								</select>
							</span><br /><br />';
					if ($context['action']['can_edit_groups'] == 1)
						echo '
							<span><b>', $txt['custom_action_permissions_mode'], ':</b>
								<select name="permissions_mode" id="permissions_mode" onchange="updateInputBoxesPerm();">
									<option value="0" ', $context['action']['permissions_mode'] == 0 ? 'selected="selected"' : '', '>', $txt['custom_action_permissions_mode_0'], '</option>
									<option value="1" ', $context['action']['permissions_mode'] == 1 ? 'selected="selected"' : '', '>', $txt['custom_action_permissions_mode_1'], '</option>', $context['id_parent'] ? '
									<option value="2" ' . ($context['action']['permissions_mode'] == 2 ? 'selected="selected"' : '') . '>' . $txt['custom_action_permissions_mode_2'] . '</option>' : '', '
								</select>
							</span><br /><br />
							<div id="inline_permissions" style="display:none">
								', theme_inline_permissions('ca_' . ($context['id_action'] ? $context['id_action'] : 'temp')), '
							</div>';
					else
						echo '
							<span>', $txt['custom_action_only_admin_change_permissions'], '</b></span><br /><br />';
					if (!$context['id_parent'])
						echo '
							<span><b>' . $txt['custom_action_menu'] . ':</b>
								<input type="checkbox" name="menu" ' . ($context['action']['menu'] ? 'checked="checked"' : '') . ' class="check" />
							</span><br /><br />';
					echo '
					<span><b>', $txt['custom_action_enabled'], ':</b>
					<input type="checkbox" name="enabled" ', $context['action']['enabled'] ? 'checked="checked"' : '', ' class="check" /></span>
					<hr class="hrcolor clear" />
			<b>', $txt['custom_action_settings_code'], '</b>
			<hr class="hrcolor clear" />';
			if ($context['action']['can_choose_type'] == 1)
				echo '
					<span id="header_box">
						<span id="header_text">
						<b>', $txt['custom_action_header'], ':</b>
							<span class="smalltext">', $txt['custom_action_header_desc'], '</span>
							<textarea name="header" rows="10" cols="60">', $context['action']['header'], '</textarea>
						</span>
						<span id="source_text">
							<b>', $txt['custom_action_source'], ':</b>
							<span class="smalltext">', $txt['custom_action_source_desc'], '</span>
							<textarea name="header" rows="10" cols="60">', $context['action']['header'], '</textarea>
						</span>
					</span>';
			echo '
				<span id="body_text">
						<b>', $txt['custom_action_body'], ':</b>
						<span class="smalltext">', $txt['custom_action_body_desc'], '</span>
				</span>';
			if ($context['action']['can_choose_type'] == 1)
				echo '				
					<span id="html_body_text">
							<b>', $txt['custom_action_body_html'], ':</b>
							<span class="smalltext">', $txt['custom_action_body_html_desc'], '</span>
					</span>
					<span id="php_body_text">
							<b>', $txt['custom_action_body_php'], ':</b>
							<span class="smalltext">', $txt['custom_action_body_php_desc'], '</span>
					</span>';
			echo '			
					<textarea name="body" rows="20" cols="60">', $context['action']['body'], '</textarea><br /><br />
					<input type="hidden" name="', $context['admin-cae_token_var'], '" value="', $context['admin-cae_token'], '" />
					<input type="hidden" name="', $context['admin-mp_token_var'], '" value="', $context['admin-mp_token'], '" />
					<input type="submit" name="save" value="', $txt['save'], '" />';

	if ($context['id_action'] && isset($context['action']['can_delete']))
		echo '
					<input type="submit" name="delete" value="', $txt['delete'], '" onclick="return confirm(\'', $txt['custom_action_delete_sure'], '\');" />';

	echo '
				', $context['id_parent'] ? '
		<input type="hidden" name="id_parent" value="' . $context['id_parent'] . '" />' : '', '
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input type="hidden" name="id_author" value="', (!empty($context['user']['id']) ? $context['user']['id'] : -1), '" />
		</div>
	</div>
	</form>
	</div>';

	// Get the javascript bits right!
	if ($context['action']['can_choose_type'] == 1)
	echo '
		<script language="JavaScript" type="text/javascript"><!-- // -->
			updateInputBoxesType();
		// ]', ']></script>';
	if ($context['action']['can_edit_groups'] == 1)
		echo '	
			<script language="JavaScript" type="text/javascript"><!-- // -->
				updateInputBoxesPerm();
			// ]', ']></script>';
	
}

?>