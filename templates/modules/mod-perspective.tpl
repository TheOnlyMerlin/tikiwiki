{if !isset($tpl_module_title)}
	{eval var="{tr}Perspectives{/tr}" assign="tpl_module_title"}
{/if}
{if $prefs.feature_perspective eq 'y'}
	{tikimodule error=$module_params.error title=$tpl_module_title name="perspective" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle}
		<form method="get" action="tiki-switch_perspective.php">
			<select name="perspective">
				<option>{tr}Default{/tr}</option>
				{foreach from=$perspectives item=persp}
					<option value="{$persp.perspectiveId|escape}"{if $persp.perspectiveId eq $current_perspective} selected="selected"{/if}>{$persp.name|escape}</option>
				{/foreach}
			</select>
			<input type="submit" value="{tr}Go{/tr}"/>
		</form>
	{/tikimodule}
{/if}
