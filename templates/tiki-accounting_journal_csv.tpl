{$quote}{tr}Id{/tr}{$quote}{$separator}{$quote}{tr}Date{/tr}{$quote}{$separator}{$quote}{tr}Description{/tr}{$quote}{$separator}{$quote}{tr}Deleted{/tr}{$quote}{$separator}{$quote}{tr}Currency{/tr}{$quote}{$separator}{$quote}{tr}Debit Account{/tr}{$quote}{$separator}{$quote}{tr}Debit Amount{/tr}{$quote}{$separator}{$quote}{tr}Debit Text{/tr}{$quote}{$separator}{$quote}{tr}Credit Account{/tr}{$quote}{$separator}{$quote}{tr}Credit Amount{/tr}{$quote}{$separator}{$quote}{tr}Credit Text{/tr}{$quote}{$eol}{foreach from=$journal item="j"}{$j.journalId}{$separator}{$j.journalDate|date_format:"%Y-%m-%d"}{$separator}{$quote}{$j.journalDescription|escape}{$quote}{$separator}{$quote}{if $j.journalCancelled==1}{tr}Yes{/tr}{else}{tr}No{/tr}{/if}{$quote}{$separator}{$quote}{$book.bookCurrency}{$quote}{$separator}{section name=posts loop=$j.maxcount}{assign var='i' value=$smarty.section.posts.iteration-1}{if !$smarty.section.posts.first}{$j.journalId}{$separator}{$separator}{$separator}{$separator}{$separator}{/if}{if $i<$j.debitcount}{$j.debit[$i].itemAccountId}{/if}{$separator}{if $i<$j.debitcount}{$j.debit[$i].itemAmount|currency}{/if}{$separator}{if $i<$j.debitcount}{$quote}{$j.debit[$i].itemText|escape}{$quote}{/if}{$separator}{if $i<$j.creditcount}{$j.credit[$i].itemAccountId}{/if}{$separator}{if $i<$j.creditcount}{$j.credit[$i].itemAmount|currency}{/if}{$separator}{if $i<$j.creditcount}{$quote}{$j.credit[$i].text|escape}{$quote}{/if}{$eol}{/section}{/foreach}
