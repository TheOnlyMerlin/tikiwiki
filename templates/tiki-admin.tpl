{* $Id$ *}

{title help="$helpUrl"}{$admintitle}{/title}

{if $prefs.sender_email eq ''}
	<div class="alert alert-info">
		{tr _0="tiki-admin.php?page=general&highlight=sender_email"}Your sender email is not set. You can set it <a href="%0" class="alert-link">here</a>{/tr}
	</div>
{/if}

{* Limit the wizard link to the home page, leave some screen space for the main content *}
{if ! $smarty.get.page}
{remarksbox type="note" title="{tr}Note{/tr}"}
    <div style="float:left">&nbsp;&nbsp;<a href="tiki-wizard_admin.php?&stepNr=0&url=tiki-admin.php"><img src="img/icons/wizard22x22.png"></a></div>&nbsp;&nbsp;{tr _0="tiki-wizard_admin.php?&stepNr=0&url=tiki-admin.php"}Use the <a href="%0" class="rbox-link" >Configuration Wizards</a> to more easily set up your site.{/tr}
{/remarksbox}
{/if}

<form method="post" action="">
	<fieldset>
		<legend>{tr}Preference Filters{/tr}</legend>
		{foreach from=$pref_filters key=name item=info}
			<label>
				<input type="checkbox" class="preffilter {$info.type|escape}" name="pref_filters[]" value="{$name|escape}" {if $info.selected}checked="checked"{/if}>
				{$info.label|escape}
			</label>
		{/foreach}

		<input type="submit" value="{tr}Set as my default{/tr}" class="btn btn-primary btn-xs">

		{if $prefs.connect_feature eq "y"}
			<label>
				<input type="checkbox" id="connect_feedback_cbx" {if !empty($connect_feedback_showing)}checked="checked"{/if}>
                    {tr}Provide Feedback{/tr}
                    <a href="http://doc.tiki.org/Connect" target="tikihelp" class="tikihelp" title="{tr}Provide Feedback:{/tr}
                        {tr}Once selected, some icon/s will be shown next to all features so that you can provide some on-site feedback about them{/tr}.
                        <br/><br/>
                        <ul>
                            <li>{tr}Icon for 'Like'{/tr} <img src=img/icons/connect_like.png></li>
<!--						<li>{tr}Icon for 'Fix me'{/tr} <img src=img/icons/connect_fix.png></li> -->
<!--						<li>{tr}Icon for 'What is this for?'{/tr} <img src=img/icons/connect_wtf.png></li> -->
                        </ul>
                        <br/>
                        {tr}Your votes will be sent when you connect with mother.tiki.org (currently only by clicking the 'Connect > <strong>Send Info</strong>' button){/tr}
                        <br/><br/>
                        {tr}Click to read more{/tr}
                    ">
                        <img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
                    </a>

			</label>
			{$headerlib->add_jsfile("lib/jquery_tiki/tiki-connect.js")}
		{/if}
	</fieldset>
</form>

{jq}
	var updateVisible = function() {
		var show = function (selector) {
			selector.show();
			selector.parents('fieldset:not(.tabcontent)').show();
			selector.closest('fieldset.tabcontent').addClass('filled');
		};
		var hide = function (selector) {
			selector.hide();
			/*selector.parents('fieldset:not(.tabcontent)').hide();*/
		};

		var filters = [];
		var prefs = $('.adminoptionbox.preference, .admbox').hide();
		prefs.parents('fieldset:not(.tabcontent)').hide();
		prefs.closest('fieldset.tabcontent').removeClass('filled');
		$('.preffilter').each(function () {
			var targets = $('.adminoptionbox.preference.' + $(this).val() + ',.admbox.' + $(this).val());
			if ($(this).is(':checked')) {
				filters.push($(this).val());
				show(targets);
			} else if ($(this).is('.negative:not(:checked)')) {
				hide(targets);
			}
		});

		show($('.adminoptionbox.preference.modified'))

		$('input[name="filters"]').val(filters.join(' '));
		$('.tabset .tabmark a').each(function () {
			var selector = 'fieldset.tabcontent.' + $(this).attr('href').substring(1);
			var content = $(this).closest('.tabset').find(selector);

			$(this).parent().toggle(content.is('.filled') || content.find('.preference').length === 0);
		});
	};

	updateVisible();
	$('.preffilter').change(updateVisible);
{/jq}

{if !isset($smarty.get.page) or $smarty.get.page != 'profiles'} {* We don't want on this page because it results in two search boxes *}
<form method="post" action="">
	{*remarksbox type="note" title="{tr}Development Notice{/tr}"}
		{tr}This search feature and the <a href="tiki-edit_perspective.php">perspectives GUI</a> need <a href="http://dev.tiki.org/Dynamic+Preferences">dev.tiki.org/Dynamic+Preferences</a>. If you search for something and it's not appearing, please help improve keywords/descriptions.{/tr}
	{/remarksbox*}
	<p>
		<label>{tr}Configuration search:{/tr} <input type="text" name="lm_criteria" value="{$lm_criteria|escape}"></label>
		<input type="submit" value="{tr}Search{/tr}" {if $indexNeedsRebuilding} class="tips" title="{tr}Configuration search{/tr}|{tr}Note: The search index needs rebuilding, this will take a few minutes.{/tr}"{/if} />
		<input type="hidden" name="filters">
	</p>
</form>
{if isset($csrferror)}
	{remarksbox type="error" title="{tr}Potential Cross-Site Request Forgery{/tr}"}
		{$csrferror}
	{/remarksbox}
{/if}
{if $lm_error}
	{remarksbox type="warning" title="{tr}Search error{/tr}"}{$lm_error}{/remarksbox}
{elseif $lm_searchresults}
<fieldset>
<legend>{tr}Preferences Search Results{/tr}</legend>
	<form method="post" action="">
		<div class="pref_search_results box">
			{foreach from=$lm_searchresults item=prefName}
				{preference name=$prefName get_pages="y"}
			{/foreach}
		</div>
		<input type="submit" value="{tr}Change{/tr}" class="clear">
		<input type="hidden" name="lm_criteria" value="{$lm_criteria|escape}">
		<input type="hidden" name="daconfirm" value="y">
		<input type="hidden" name="ticket" value="{$ticket|escape}">
	</form>
</fieldset>
{elseif $lm_criteria}
	{remarksbox type="note" title="{tr}No results{/tr}" icon="magnifier"}{tr}No preferences were found for your search query with your current choice of Preference Filters.{/tr}{if $prefs.unified_engine eq 'lucene'}{tr} Not what you expected? Try {/tr}<a class="rbox-link" href="tiki-admin.php?prefrebuild">{tr}rebuild{/tr}</a> {tr}the preferences search index.{/tr}{/if}{/remarksbox}
{/if}
{/if}

<div id="pageheader">
{* bother to display this only when breadcrumbs are on *}
{*
{if $prefs.feature_breadcrumbs eq 'y'}
    {breadcrumbs type="trail" loc="page" crumbs=$crumbs}
    {breadcrumbs type="pagetitle" loc="page" crumbs=$crumbs}
{/if}
*}
{if $db_requires_update}
	{remarksbox type="errors" title="{tr}Database Version Problem{/tr}"}
	{tr}Your database requires an update to match the current Tiki version. Please proceed to <a href="tiki-install.php">the installer</a>. Using Tiki with an incorrect database version usually provokes errors.{/tr}
	{tr}If you have shell (SSH) access, you can also use the following, on the command line, from the root of your Tiki installation:{/tr}
	<kbd>php console.php{if not empty($tikidomain)} --site={$tikidomain|replace:'/':''}{/if} database:update</kbd>
	{/remarksbox}
{/if}
{*{tr}{$description}{/tr}*}
</div>
{* Determines which page to include using "page" GET parameter. Default : list-sections
Add a value in first check when you create a new admin page. *}
{if in_array($adminpage, array("features", "general", "login", "wiki",
"gal", "fgal", "articles", "polls", "search", "blogs", "forums", "faqs",
"trackers", "webmail", "comments", "rss", "directory", "userfiles", "maps",
"metatags", "performance", "security", "wikiatt", "score", "community", "messages",
"calendar", "intertiki", "video", "freetags", 
"i18n", "wysiwyg", "copyright", "category", "module", "look", "textarea",
 "ads", "profiles", "semantic", "plugins", "webservices",
'sefurl', 'connect', 'metrics', 'payment', 'rating', 'socialnetworks', 'share', "workspace"))}
  {assign var="include" value=$smarty.get.page}
{else}
  {assign var="include" value="list_sections"}
{/if}
{if $include != "list_sections"}
  <div class="simplebox adminanchors clearfix" >{include file='admin/include_anchors.tpl'}</div>
{/if}

{if $upgrade_messages|count}
	{if $upgrade_messages|count eq 1}
		{$title="{tr}Upgrade Available{/tr}"}
	{else}
		{$title="{tr}Upgrades Available{/tr}"}
	{/if}
	{remarksbox type="note" title=$title icon="announce"}
		{foreach from=$upgrade_messages item=um}
			<p>{$um|escape}</p>
		{/foreach}
	{/remarksbox}
{/if}

{if $tikifeedback}
	{remarksbox type="note" title="{tr}Note{/tr}"}
		{cycle values="odd,even" print=false}
		{tr}The following list of changes has been applied:{/tr}
		<ul>
		{section name=n loop=$tikifeedback}
			<li class="{cycle}">
				<p>
			{if $tikifeedback[n].st eq 0}
				{icon _id=delete alt="{tr}Disabled{/tr}" style="vertical-align: middle"}
			{elseif $tikifeedback[n].st eq 1}
				{icon _id=accept alt="{tr}Enabled{/tr}" style="vertical-align: middle"}
			{elseif $tikifeedback[n].st eq 2}
				{icon _id=accept alt="{tr}Changed{/tr}" style="vertical-align: middle"}
			{elseif $tikifeedback[n].st eq 4}
				{icon _id=arrow_undo alt="{tr}Reset{/tr}" style="vertical-align: middle"}
			{else}
				{icon _id=information alt="{tr}Information{/tr}" style="vertical-align: middle"}
			{/if}
					{if $tikifeedback[n].st ne 3}{tr}Preference{/tr} {/if}<strong>{tr}{$tikifeedback[n].mes|stringfix}{/tr}</strong><br>
					{if $tikifeedback[n].st ne 3}(<em>{tr}Preference name:{/tr}</em> {$tikifeedback[n].name}){/if}
				</p>
			</li>
		{/section}
		</ul>
	{/remarksbox}
{/if}
{* seems to be unused? jonnyb: tiki5 
if $pagetop_msg}
	{remarksbox type="note" title="{tr}Note{/tr}"}
		{$pagetop_msg}
	{/remarksbox}
{/if*}

{include file="admin/include_$include.tpl"}

<br style="clear:both" />
{remarksbox type="tip" title="{tr}Crosslinks to other features and settings{/tr}"}

	{tr}Administration features:{/tr}<br>
	{* TODO: to be fixed {if $prefs.feature_debug_console eq 'y'} <a href="javascript:toggle("debugconsole")">{tr}(debug){/tr}</a> {/if} *}
	<a href="tiki-adminusers.php">{tr}Users{/tr}</a> 
	<a href="tiki-admingroups.php">{tr}Groups{/tr}</a> 
	<a href="tiki-admin_security.php">{tr}Security{/tr}</a> 
	<a href="tiki-admin_system.php">{tr}TikiCache/System{/tr}</a> 
	<a href="tiki-syslog.php">{tr}SysLogs{/tr}</a> 
	<a href="tiki-mods.php">{tr}Mods{/tr}</a>
	<hr>

	{tr}Transversal features{/tr} ({tr}which apply to more than one section{/tr}):<br>
	<a href="tiki-admin_notifications.php">{tr}Mail Notifications{/tr}</a> 
	<hr>

	{tr}Navigation features:{/tr}<br>
	<a href="tiki-admin_menus.php">{tr}Menus{/tr}</a> 
	<a href="tiki-admin_modules.php">{tr}Modules{/tr}</a>
	<hr>

	{tr}Text area features{/tr} ({tr}features you can use in all text areas, like wiki pages, blogs, articles, forums, etc{/tr}):<br>
	<a href="tiki-admin_cookies.php">{tr}Cookies{/tr}</a> 
	<a href="tiki-list_cache.php">{tr}External Pages Cache{/tr}</a> 
	<a href="tiki-admin_toolbars.php">{tr}Toolbars{/tr}</a> 
	<a href="tiki-admin_dsn.php">{tr}DSN{/tr}</a> 
	<a href="tiki-admin_external_wikis.php">{tr}External Wikis{/tr}</a> 
	<hr>

{/remarksbox}
