{include file='header' pageTitle='wbb.acp.boardIcon.'|concat:$action}

<script data-relocate="true" src="{@$__wcf->getPath('wbb')}acp/js/WBB.ACP.BoardIcon.js"></script>
<script data-relocate="true">
	//<![CDATA[
	$(function() {
		WCF.Language.add('wcf.global.button.upload', '{lang}wcf.global.button.upload{/lang}');
		
		new WBB.ACP.BoardIcon.IconUpload({if $action == 'add'}0{else}{@$boardIcon->icon}{/if}, '{$tmpHash}');
	});
	//]]>
</script>

<header class="boxHeadline">
	<h1>{lang}wbb.acp.boardIcon.{$action}{/lang}</h1>
</header>

<p class="info">{lang}wbb.acp.boardIcon.info{/lang}</p>

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<div class="contentNavigation">
	<nav>
		<ul>
			{if $action == 'edit' && $boardIcons|count}
				<li class="dropdown">
					<a class="button dropdownToggle"><span class="icon icon16 icon-sort"></span> <span>{lang}wbb.acp.boardIcon.button.choose{/lang}</span></a>
					<div class="dropdownMenu">
						<ul class="scrollableDropdownMenu">
							{foreach from=$boardIcons item='editableBoardIcon'}
								<li{if $editableBoardIcon->iconID == $boardIcon->iconID} class="active"{/if}><a href="{link controller='BoardIconEdit' id=$editableBoardIcon application='wbb'}{/link}">{$editableBoardIcon->getTitle()}</a></li>
							{/foreach}
						</ul>
					</div>
				</li>
			{/if}
			
			<li><a href="{link controller='BoardIconList' application='wbb'}{/link}" class="button"><span class="icon icon16 icon-list"></span> <span>{lang}wbb.acp.menu.link.boardIcon.list{/lang}</span></a></li>
			
			{event name='contentNavigationButtons'}
		</ul>
	</nav>
</div>

<form method="post" action="{if $action == 'add'}{link controller='BoardIconAdd' application='wbb'}{/link}{else}{link controller='BoardIconEdit' object=$boardIcon application='wbb'}{/link}{/if}">
	<div class="container containerPadding marginTop">
		<fieldset>
			<legend>{lang}wcf.global.form.data{/lang}</legend>
			
			<dl{if $errorField == 'title'} class="formError"{/if}>
				<dt><label for="title">{lang}wcf.global.title{/lang}</label></dt>
				<dd>
					<input type="text" id="title" name="title" value="{$i18nPlainValues[title]}" required="required" autofocus="autofocus" class="long" />
					{if $errorField == 'title'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else}
								{lang}wbb.acp.boardIcon.title.error.{@$errorType}{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>
			{include file='multipleLanguageInputJavascript' elementIdentifier='title' forceSelection=false}
			
			<dl{if $errorField == 'icon'} class="formError"{/if}>
				<dt><label for="icon">{lang}wbb.acp.boardIcon.icon{/lang}</label></dt>
				<dd class="framed" id="boardIconContainer">
					<img src="{if $action == 'edit'}{@$boardIcon->getLink()}{/if}" alt="" id="boardIcon"{if $action == 'add'} style="display: none; max-width: 100%;"{/if} />
					<div id="uploadIcon"></div>
					{if $errorField == 'icon'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else}
								{lang}wbb.acp.boardIcon.icon.error.{$errorType}{/lang}
							{/if}
						</small>
					{/if}
					<small>{lang}wbb.acp.boardIcon.icon.description{/lang}</small>
				</dd>
			</dl>
			
			{event name='dataFields'}
		</fieldset>
		
		{event name='fieldsets'}
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		<input type="hidden" name="tmpHash" value="{$tmpHash}" />
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}
