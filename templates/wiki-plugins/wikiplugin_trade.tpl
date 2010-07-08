<form method="post" action="#pluginTrade{$iPluginTrade}">
	<div id="pluginTradeDiv{$iPluginTrade}" class="pluginTradeDiv">
		{if !empty($wp_trade_title)}
			{$wp_trade_title}
		{/if}
		<input type="hidden" name="wp_trade_offset" value="{$wp_trade_offset|escape}"/>
		{if $wp_trade_other_user_set eq "n"}
			<input type="text" id="other_user{$wp_trade_offset|escape}" name="wp_trade_other_user" value="{$wp_trade_other_user.login|escape}"/> ({tr}separated by |{/tr})
		{/if}
		<input type="submit" value="{$wp_trade_action}"/>
	</div>
	{jq}
		$jq('#other_user{{$wp_trade_offset|escape}}').tiki("autocomplete", "username", {multiple: true, multipleSeparator: "|"});
	{/jq}
</form>
