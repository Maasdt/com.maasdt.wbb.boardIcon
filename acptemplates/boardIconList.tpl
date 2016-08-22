{include file='header' pageTitle='wbb.acp.boardIcon.list'}

<script data-relocate="true">
	//<![CDATA[
	$(function() {
		new WCF.Action.Delete('wbb\\data\\board\\icon\\BoardIconAction', '.jsBoardIconRow');
		
		var options = { };
		{if $pages > 1}
			options.refreshPage = true;
			{if $pages == $pageNo}
				options.updatePageNumber = -1;
			{/if}
		{else}
			options.emptyMessage = '{lang}wcf.global.noItems{/lang}';
		{/if}
		
		new WCF.Table.EmptyTableHandler($('#boardIconTableContainer'), 'jsBoardIconRow', options);
	});
	//]]>
</script>

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wbb.acp.boardIcon.list{/lang}</h1>
	</div>
	
	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='BoardIconAdd' application='wbb'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}wbb.acp.boardIcon.add{/lang}</span></a></li>
			
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{hascontent}
	<div class="paginationTop">
		{content}
			{pages print=true assign='pagesLinks' controller='BoardIconList' link="pageNo=%d" application='wbb'}
		{/content}
	</div>
{/hascontent}

{if $objects|count}
	<div id="boardIconTableContainer" class="section tabularBox">
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnBoardIconID" colspan="2"><span>{lang}wcf.global.objectID{/lang}</span></th>
					<th class="columnTitle columnBoardIconIdentifier active ASC" colspan="2"><span>{lang}wcf.global.title{/lang}</spam></th>
					
					{event name='columnHeads'}
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item='boardIcon'}
					<tr class="jsBoardIconRow">
						<td class="columnIcon">
							<a href="{link controller='BoardIconEdit' object=$boardIcon application='wbb'}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 icon-pencil"></span></a>
							<span class="icon icon16 icon-remove jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$boardIcon->iconID}" data-confirm-message="{lang}wbb.acp.boardIcon.delete.confirmMessage{/lang}"></span>
							
							{event name='rowButtons'}
						</td>
						<td class="columnID">{@$boardIcon->iconID}</td>
						<td class="columnIcon"><p class="framed"><img src="{@$boardIcon->getLink()}" style="height: 24px; width: 24px;" /></p></td>
						<td class="columnTitle columnBoardIconIdentifier"><a href="{link controller='BoardIconEdit' object=$boardIcon application='wbb'}{/link}">{$boardIcon->getTitle()}</a></td>
						
						{event name='columns'}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
	
	<footer class="contentFooter">
		{hascontent}
			<div class="paginationBottom">
				{content}{@$pagesLinks}{/content}
			</div>
		{/hascontent}
		
		<nav class="contentFooterNavigation">
			<ul>
				<li><a href="{link controller='BoardIconAdd' application='wbb'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}wbb.acp.boardIcon.add{/lang}</span></a></li>
				
				{event name='contentFooterNavigation'}
			</ul>
		</nav>
	</footer>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}
