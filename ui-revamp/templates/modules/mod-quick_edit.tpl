{* $Id$ *}

{if !isset($tpl_module_title)}{assign var=tpl_module_title value="{tr}$module_title{/tr}"}{/if}
{tikimodule error=$module_params.error title=$tpl_module_title name="quick_edit" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle}
<form method="get" action="{$qe_action|escape}">
{if $categId}<input type="hidden" name="categId" value="{$categId}" />{/if}
{if $templateId}<input type="hidden" name="templateId" value="{$templateId}" />{/if}
{if $mod_quickedit_heading}<div class="bod-data">{$mod_quickedit_heading}</div>{/if}
<input id="{$qefield}" type="text" size="{$size}" name="page" />
<input type="submit" name="quickedit" value="{$submit}" />
</form>
<script type="text/javascript">
{if $prefs.feature_mootools eq 'y'}
{literal}
window.addEvent('domready', function() {
	var o = new Autocompleter.Request.JSON('{/literal}{$qefield}{literal}', 'tiki-listpages.php?listonly', {
		'postVar': 'find'
	});
});
{/literal}
{/if}
</script>
{/tikimodule}