{* $Id$ *}
{strip}
{* params:
	mode: 'menu' or 'flags' (default=menu)
	prefix: Module title prefix text (default='Site Language:')
*}
{if !isset($tpl_module_title)}
	{if isset($module_params.prefix)}
		{assign var='prefix' value="{tr}`$module_params.prefix`{/tr}} {* TODO fix tr *}
	{else}
		{assign var='prefix' value="{tr}Site Language:{/tr}"}
	{/if}
	{section name=ix loop=$languages}
		{if $languages[ix].value eq $prefs.language and isset($languages[ix].name)}
			{assign var='name' value=$languages[ix].name|escape}
			{assign var=tpl_module_title value="$prefix&nbsp;$name"}
		{/if}
	{/section}
	{if !isset($tpl_module_title)}
		{assign var=tpl_module_title value="$prefix&nbsp;`$prefs.language`"}
	{/if}
{/if}
{tikimodule error=$module_params.error title=$tpl_module_title name="switch_lang" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle}
{if $prefs.feature_multilingual ne 'y'}
	{tr}This feature is disabled{/tr}
{elseif $prefs.change_language ne 'n' or $user eq ''}
{if !$module_params.mode or $module_params.mode eq 'menu'}
<form method="get" action="tiki-switch_lang.php" target="_self">
       <select name="language" size="1" onchange="this.form.submit();">
        {section name=ix loop=$languages}
        <option value="{$languages[ix].value|escape}"
          {if $prefs.language eq $languages[ix].value}selected="selected"{/if}>
          {$languages[ix].name}
        </option>
        {/section}
        </select>
</form>
{elseif $module_params.mode eq 'flags'}
	{section name=ix loop=$languages}
		{assign var='val' value=$languages[ix].value|escape}
		{assign var='name' value=$languages[ix].name|escape}
		{assign var='flag' value=$languages[ix].flag|escape}
		{assign var='class' value=$languages[ix].class|escape}
		{if $flag neq ''}
			{icon href="tiki-switch_lang.php?language=$val" alt="$name" title="$name" _id="img/flags/$flag.gif" _type="absolute_uri" height=11 class="icon $class" }
		{else}
			{button _text="$name" href="tiki-switch_lang.php?language=$val" _title="$name" _class="$class" }
		{/if}
	{/section}
{/if}
{else}
	{tr}Permission denied{/tr}
{/if}
{/tikimodule}
{/strip}