{* $Id$ *}

<div class="adminWizardIconleft"><img src="img/icons/large/wizard_admin48x48.png" alt="{tr}Admin Wizard{/tr}" title="{tr}Admin Wizard{/tr}" /></div><div class="adminWizardIconright"><img src="img/icons/large/users48x48.png" alt="{tr}Set up your User & Community features{/tr}"></div>
{tr}Configure general user & community features and settings related to sharing and social networks{/tr}.
<div class="adminWizardContent">
<fieldset>
	<legend>{tr}User Features{/tr}</legend>
		<table style="width:100%"><tr><td style="width:48%">
			{preference name=feature_mytiki}
			{preference name=feature_messages}
		</td><td style="width:4%">&nbsp;
		</td><td style="width:48%">
			<div class="adminWizardIconright"><img src="img/icons/large/user.png" width="32" alt="{tr}Users{/tr}"></div>
			{preference name=feature_userPreferences}
			{preference name=feature_wizard_user}
		</td></tr></table>	
		<br/>
		<em>{tr}To set up the <strong>User Watches</strong> and their associated settings, visit the page to {/tr} <a href="tiki-wizard_admin.php?&stepNr=15&url={$homepageUrl}">{tr}Set up Main features{/tr}</a></em>
</fieldset>
<fieldset>
	<legend>{tr}Community General Settings{/tr}</legend>
		<table style="width:100%"><tr><td style="width:48%">
			{preference name=users_prefs_allowMsgs}
			{preference name=feature_community_mouseover}
			<div class="adminoptionboxchild" id="feature_community_mouseover_childcontainer">				
				{preference name=feature_community_mouseover_name}
				{preference name=feature_community_mouseover_gender}
				{preference name=feature_community_mouseover_picture}
				{preference name=feature_community_mouseover_score}
				{preference name=feature_community_mouseover_country}
				{preference name=feature_community_mouseover_email}
				{preference name=feature_community_mouseover_lastlogin}
				{preference name=feature_community_mouseover_distance}
			</div>
			{preference name=users_prefs_show_mouseover_user_info}
			{preference name=feature_contact}
			<div class="adminoptionboxchild" id="feature_contact_childcontainer">				
				{preference name=contact_anon}
				{preference name=contact_priority_onoff}
			</div>
		</td><td style="width:4%">&nbsp;
		</td><td style="width:48%">
			<div class="adminWizardIconright"><img src="img/icons/large/users.png" alt="{tr}Community{/tr}"></div>
			{preference name=users_prefs_user_information}
			{preference name=users_prefs_mailCharset}
			<div class="adminoptionbox preference clearfix all"></div>
			{preference name=user_show_realnames}
			<div class="adminoptionboxchild" id="user_show_realnames_childcontainer">				
				{preference name=user_selector_realnames_messu}
				{preference name=user_selector_realnames_tracker}
			</div>
		</td></tr></table>	
		<br>
		<em>{tr}See also{/tr} {tr}Community{/tr} <a href="tiki-admin.php?page=community&amp;cookietab=1" target="_blank">{tr}admin panel{/tr}</a> & <a href="https://doc.tiki.org/Community" target="_blank">{tr}in doc.tiki.org{/tr}</a></em>
</fieldset>
	<fieldset>
		<legend>{tr}Sharing & Networking{/tr}</legend>
		<table style="width:100%"><tr><td style="width:24%">
			{preference name=feature_share}
			<br>
			<em>{tr}See also{/tr} {tr}Share{/tr} <a href="tiki-admin.php?page=share" target="_blank">{tr}admin panel{/tr}</a> & <a href="https://doc.tiki.org/Share" target="_blank">{tr}in doc.tiki.org{/tr}</a></em>.
		</td><td style="width:2%">&nbsp;
		</td><td style="width:43%">
			{preference name=feature_friends}
			<div class="adminoptionboxchild" id="feature_friends_childcontainer">
			{preference name=social_network_type}
			</div>
			<br>
			<em>{tr}See also{/tr} "{tr}Community{/tr} <a href="tiki-admin.php?page=community&cookietab=2" target="_blank">{tr}admin panel{/tr}</a> > {tr}Social Network{/tr} > {tr}Friendship and Followers{/tr}" & <a href="https://doc.tiki.org/Friendship+Network" target="_blank">{tr}in doc.tiki.org{/tr}</a></em>.
		</td><td style="width:1%">&nbsp;
		</td><td style="width:30%">
			<div class="adminWizardIconright"><img src="img/icons/large/socialnetworks.png" alt="{tr}Social networks{/tr}"></div>
			{preference name=feature_socialnetworks}
			<br>
			<em>{tr}See also{/tr} {tr}Social networks{/tr} <a href="tiki-admin.php?page=socialnetworks&amp;cookietab=1" target="_blank">{tr}admin panel{/tr}</a> & <a href="https://doc.tiki.org/Social+Networks" target="_blank">{tr}in doc.tiki.org{/tr}</a></em>
		</td></tr></table>
	</fieldset>

</div>
