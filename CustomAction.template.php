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
?>