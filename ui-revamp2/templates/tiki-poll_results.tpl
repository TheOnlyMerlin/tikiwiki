{* $Id$ *}

{title help="polls" admpage="polls"}{tr}Poll Results{/tr}{/title}

<div class="navbar">
	{button href="tiki-old_polls.php" _text="{tr}Polls{/tr}"}
	{button href="tiki-poll_results.php" _text="{tr}Top Voted Polls{/tr}"}
	{if $tiki_p_admin_polls eq 'y'}
		{if empty($pollId)}{button href="tiki-admin_polls.php" _text="{tr}Admin Polls{/tr}"}{else}{button href="tiki-admin_polls.php?pollId=$pollId" _text="{tr}Edit Poll{/tr}"}{/if}
	{/if}
</div>

<form method="post" action="{$smarty.server.PHP_SELF}"  class="findtable">
{if !empty($sort_mode)}<input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />{/if}
{if !empty($pollId)}<input type="hidden" name="pollId" value="{$pollId|escape}" />{/if}
{if !empty($list)}<input type="hidden" name="list" value="{$list|escape}" />{/if}
{if !empty($offset)}<input type="hidden" name="list" value="{$offset|escape}" />{/if}
{if empty($pollId) and !isset($list_votes)}
	<label>
		{if empty($what)}{tr}Find the poll{/tr}{else}{tr}{$what}{/tr}{/if}
		<input type="text" name="find" value="{$find|escape}" />
	</label>
	<label>
		{tr}Number of top voted polls to show{/tr}
		<input type="text" name="maxRecords" value="{$maxRecords|escape}" size="3" />
	</label>
	<br />
{/if}
<label>
	<input type="radio" name="which_date" value="between"{if $which_date eq 'between'} checked="checked"{/if} />{tr}Vote range displayed{/tr}: 
</label>
<label>
	{tr}Start{/tr}: {html_select_date prefix="from_" time="$vote_from_date" start_year="$start_year"}
</label>
<label>
	{tr}End{/tr}: {html_select_date prefix="to_" time="$vote_to_date" start_year="$start_year"}
</label>
<br />
{if empty($pollId) or $poll_info.voteConsiderationSpan > 0}
	<label>
		<input type="radio" name="which_date" value="all"{if $which_date eq 'all'} checked="checked"{/if} />
		{tr}All votes with no span consideration{/tr}
	</label>
	<br />
	<label>
		<input type="radio" name="which_date" value="consideration"{if $which_date eq 'consideration' or $which_date eq ''} checked="checked"{/if} />
		{tr}All votes with span consideration{/tr}
	</label>
{else}
	<label>
		<input type="radio" name="which_date" value="all"{if $which_date eq 'all' or $which_date eq ''} checked="checked"{/if} />
		{tr}All votes{/tr}
	</label>		
{/if}
<br />
<input type="submit" name="search" value="{tr}Find{/tr}" />
</form>

{section name=x loop=$poll_info_arr}
<h2><a href="tiki-poll_results.php?pollId={$poll_info_arr[x].pollId}{if !empty($list_votes)}&amp;list=y{/if}">{$poll_info_arr[x].title|escape}</a></h2>
{if $poll_info_arr[x].from or $poll_info_arr[x].to}
	<div class="description">
	{if $poll_info_arr[x].from}{$poll_info_arr[x].from|tiki_short_date}{else}{$poll_info_arr[x].publishDate|tiki_short_date}{/if}
	- {if $poll_info_arr[x].to}{$poll_info_arr[x].to|tiki_short_date}{else}{tr}Today{/tr}{/if}
	</div>
{/if}
{if $tiki_p_view_poll_voters eq 'y' && $poll_info_arr[x].votes > 0}
	<div class="navbar">
		{assign var=thispoll_info_arr value=$poll_info_arr[x].pollId}
		{button href="?list=y&amp;pollId=$thispoll_info_arr" _text="{tr}List Votes{/tr}" _auto_args="$auto_args"}
	</div>
{/if}

{*----------------------------------- Results *}
<div class="pollresults">
{cycle values="even,odd" print=false}
<table class="pollresults">
{section name=ix loop=$poll_info_arr[x].options}
<tr><td class="pollr {cycle advance=false}">
{if $smarty.section.x.total > 1}<a href="tiki-poll_results.php?{if !empty($scoresort_desc)}scoresort_asc{else}scoresort_desc{/if}={$smarty.section.ix.index}">{/if}
{$poll_info_arr[x].options[ix].title|escape}
{if $smarty.section.x.total > 1}</a>{/if}

</td>
    <td class="pollr {cycle}"><img src="img/leftbar.gif" alt="&lt;" /><img src="img/mainbar.gif" alt="-" height="14" width="{$poll_info_arr[x].options[ix].width}" /><img src="img/rightbar.gif" alt="&gt;" />  {$poll_info_arr[x].options[ix].percent}% ({$poll_info_arr[x].options[ix].votes})
    </td>
    </tr>
{/section}
</table>
<br />
{tr}Total{/tr}: {$poll_info_arr[x].votes} {tr}votes{/tr}<br /><br />
{if isset($poll_info_arr[x].total) and $poll_info_arr[x].total > 0}{tr}Average:{/tr} {math equation="x/y" x=$poll_info_arr[x].total y=$poll_info_arr[x].votes format="%.2f"}{/if}
<br />
</div>
{/section}

{*---------------------------List Votes *}
{if isset($list_votes)}
<h2>{tr}List Votes{/tr}</h2>
<div align="center">
<table class="findtable">
<tr><td class="findtable">{tr}Find{/tr}</td>
   <td class="findtable">
   <form method="get" action="tiki-poll_results.php">
     <input type="text" name="find" value="{$find|escape}" />
     <input type="submit" value="{tr}Find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
	 <input type="hidden" name="pollId" value="{$pollId|escape}" />
	 <input type="hidden" name="list" value="y" />
	 {if $vote_from_date}<input type="hidden" name="vote_from_date" value="{$vote_from_date|escape} /">{/if}
	 {if $vote_to_date}<input type="hidden" name="vote_to_date" value="{$vote_to_date|escape}" />{/if}
	 {if $which_date}<input type="hidden" name="which_date" value="{$which_date|escape}" />{/if}
	 {if $maxRecords}<input type="hidden" name="maxRecords" value="{$maxRecords|escape}" />{/if}
   </form>
   </td>
</tr>
</table>
</div>
<table class="normal">
<tr>
	<th>{self_link _sort_arg='sort_mode' _sort_field='user'}{tr}User{/tr}{/self_link}</th>
	<th>{self_link _sort_arg='sort_mode' _sort_field='ip'}{tr}IP{/tr}{/self_link}</th>
	<th>{self_link _sort_arg='sort_mode' _sort_field='title'}{tr}Option{/tr}{/self_link}</th>
	<th>{self_link _sort_arg='sort_mode' _sort_field='time'}{tr}Date{/tr}{/self_link}</th>
</tr>
{cycle values="odd,even" print=false}
{section name=ix loop=$list_votes}
<tr>
	<td class="{cycle advance=false}">{$list_votes[ix].user|userlink}</td>
	<td class="{cycle advance=false}">{$list_votes[ix].ip|escape}</td>
	<td class="{cycle advance=false}">{$list_votes[ix].title|escape}</td>
	<td class="{cycle}">{$list_votes[ix].time|tiki_short_date}</td>
</tr>
{sectionelse}
<tr>
	<td colspan="4">{tr}No records found{/tr}</td>
</tr>
{/section}
</table>
{pagination_links cant=$cant_pages step=$prefs.maxRecords offset=$offset }{/pagination_links}
{/if}

{*---------------------- comments *}
{if $prefs.feature_poll_comments == 'y' && !empty($pollId)
  && (($tiki_p_read_comments  == 'y'
    && $comments_cant != 0)
  ||  $tiki_p_post_comments  == 'y'
  ||  $tiki_p_edit_comments  == 'y')
}
  <div id="page-bar">
  	   {include file=comments_button.tpl}
  </div>
  {include file=comments.tpl}
{/if}