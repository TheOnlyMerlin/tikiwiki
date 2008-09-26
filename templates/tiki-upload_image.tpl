{* $Id$ *}

{title help="Image+Galleries"}{tr}Upload Image{/tr}{/title}

<div class="navbar">
  <span class="button2">
    {if $galleryId ne ''}
      <a href="{$galleryId:sefurl:gallery}">
    {else}
      <a href="tiki-galleries.php">
    {/if}
    {tr}Browse gallery{/tr}</a>
  </span>
  {if $prefs.feature_gal_batch eq "y" and $tiki_p_batch_upload_image_dir eq 'y'}
    {if $tiki_p_admin_galleries eq 'y' or ($user and $user eq $owner) or $public eq 'y'}
      <span class="button2">
        <a href="tiki-batch_upload.php{if $galleryId}?galleryId={$galleryId}{/if}">{tr}Directory batch{/tr}</a>
      </span>
    {/if}
  {/if}
</div>

{if $batchRes}
	<h2>{tr}Batch Upload Results{/tr}</h2>
	<table class="normal">
		<tr><th class="tableheading">{tr}Filename{/tr}</th><th class="tableheading">{tr}Status{/tr}</th><th class="tableheading">{tr}ID{/tr}</th><th class="tableheading">{tr}Image{/tr}</th></tr>
	{cycle values="odd,even" print=false}
	{section name=ix loop=$batchRes}
		<tr><td class="{cycle advance=false}">{$batchRes[ix].filename}</td>
		{if $batchRes[ix].msg}
			<td class="{cycle advance=true}" colspan="3">{icon _id=exclamation alt="{tr}Errors detected{/tr}" style="vertical-align:middle"} {$batchRes[ix].msg}</td>
		{else}
			<td class="{cycle advance=false}">{icon _id=accept alt="{tr}Upload successful!{/tr}" style="vertical-align:middle"}{tr}Upload successful!{/tr}</td><td class="{cycle advance=false}">{$batchRes[ix].imageId}</td><td class="{cycle}"><img src="{$url_show}?id={$batchRes[ix].imageId}&amp;thumb=1" alt="{$batchRes[ix].filename}" /></td>
		{/if}
		</tr>
	{/section}
	</table>
{/if}
{if $show eq 'y'}
	<h2>{tr}Upload successful!{/tr}</h2>
	<h3>{tr}The following image was successfully uploaded{/tr}:</h3>
	<div align="center">
	<img src="show_image.php?id={$imageId}" alt="{tr}Image ID{/tr}" /><br />
	<b>{tr}Thumbnail{/tr}:</b><br />
	<img src="show_image.php?id={$imageId}&amp;thumb=1" alt="{tr}Image ID thumb{/tr}" /><br /><br />
	<div class="wikitext">
	{tr}You can view this image in your browser using{/tr}: <a class="link" href="{$url_browse}?imageId={$imageId}">{$url_browse}?imageId={$imageId}</a><br /><br />
	{tr}You can include the image in an Wiki page using{/tr}:  <form><textarea rows="3" cols="60" style="width: 90%">{literal}{{/literal}img src=show_image.php?id={$imageId}{literal}}{/literal}</textarea></form>
	</div>
	</div>
{/if}

{if count($galleries) > 0}
	<div align="center">
	{if $batchRes or $show eq 'y'}<h2>Upload File</h2>{/if}
	<form enctype="multipart/form-data" action="tiki-upload_image.php" method="post">
	<table class="normal">
	<tr>
	<td class="formcolor">{tr}Image Name{/tr}:</td>
	<td class="formcolor">
	<input type="text" size ="50" name="name" /><br />{tr}or use filename{/tr}: <input type="checkbox" name="use_filename" />
	</td>
	</tr>
  {if $prefs.feature_maps eq 'y' && $geogallery eq 'y'}
  <tr><td class="formcolor">{tr}Latitude (WGS84/decimal degrees){/tr}:</td><td class="formcolor"><input type="text" name="lat" value="{$lat|escape}" /></td></tr>
  <tr><td class="formcolor">{tr}Longitude (WGS84/decimal degrees){/tr}:</td><td class="formcolor"><input type="text" name="lon" value="{$lon|escape}" /></td></tr>
  {/if}
	<tr><td class="formcolor">{tr}Image Description{/tr}:</td><td class="formcolor">
	<textarea rows="5" cols="50" name="description"></textarea></td></tr>
	{if $tiki_p_list_image_galleries eq 'y'}
	<tr><td class="formcolor">{tr}Gallery{/tr}:</td><td class="formcolor">
	<select name="galleryId">
	{section name=idx loop=$galleries}
	{if ($galleries[idx].individual eq 'n') or ($galleries[idx].individual_tiki_p_upload_images eq 'y')}
	<option  value="{$galleries[idx].galleryId|escape}" {if $galleries[idx].galleryId eq $galleryId}selected="selected"{/if}>{$galleries[idx].name}</option>
	{/if}
	{/section}
	</select>
	</td></tr>
	{else}
	<input type="hidden" name="galleryId" value="{$galleryId}" />
	{/if}
{include file=categorize.tpl}
	<tr class="formcolor">
	<td  class="formcolor" colspan="2"><b>{tr}Now enter the image URL{/tr}{tr} or upload a local image from your disk{/tr}</b></td></tr>
	<tr><td class="formcolor">URL:</td><td class="formcolor"><input size="50" type="text" name="url" /></td></tr>
	<tr>
	<td class="formcolor">{tr}Upload from disk{/tr} / {tr}Batch upload{/tr}:</td><td class="formcolor">
	<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
	<input size="50" name="userfile1" type="file" />
	</td></tr>
	<tr><td class="formcolor">{tr}Thumbnail (optional, overrides automatic thumbnail generation){/tr}:</td><td class="formcolor">
	<input name="userfile2" size ="50" type="file" />
	</td></tr>
	<tr><td class="formcolor">{tr}Upload from disk{/tr}:</td><td class="formcolor">
	<input name="userfile3" type="file" />
	<input name="userfile4" type="file" /><br />
	<input name="userfile5" type="file" />
	<input name="userfile6" type="file" /><br />
	<input name="userfile7" type="file" />
	<input name="userfile8" type="file" />
	</td></tr>
	{if $prefs.feature_antibot eq 'y' && $user eq ''}
		{include file="antibot.tpl"}
	{/if}
	<tr><td class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="upload" value="{tr}Upload{/tr}" /> <span class="rbox-data">{tr}Note: Maximum image size is limited to{/tr} {$max_img_upload_size|kbsize}</span></td></tr>
	</table>
	</form>
	</div>
{else}
	{icon _id=exclamation alt="{tr}Error{/tr}" style="vertical-align:middle;"} {tr}No gallery available.{/tr} {tr}You have to create a gallery first!{/tr}
	<p><a href="tiki-galleries.php?edit_mode=1&galleryId=0">{tr}Create New Gallery{/tr}</a></p>
{/if}


