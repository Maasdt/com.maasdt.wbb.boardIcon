<script data-relocate="true" src="{@$__wcf->getPath()}js/WCF.ColorPicker.js?v={@$__wcfVersion}"></script>
<script data-relocate="true" src="{@$__wcf->getPath()}js/WCF.Icon.FontAwesome.js?v={@$__wcfVersion}"></script>
<script data-relocate="true" src="{@$__wcf->getPath('wbb')}acp/js/WBB.ACP.BoardIcon.js?v={@$__wcfVersion}"></script>
<script data-relocate="true">
	//<![CDATA[
	$(function() {
		WCF.Language.addObject({
			'wcf.icon.fontAwesome.iconSelection': '{lang}wcf.icon.fontAwesome.iconSelection{/lang}',
			'wcf.icon.fontAwesome.selectIcon': '{lang}wcf.icon.fontAwesome.selectIcon{/lang}',
			'wcf.style.colorPicker': '{lang}wcf.style.colorPicker{/lang}',
			'wcf.style.colorPicker.button.apply': '{lang}wcf.style.colorPicker.button.apply{/lang}',
			'wcf.style.colorPicker.current': '{lang}wcf.style.colorPicker.current{/lang}',
			'wcf.style.colorPicker.new': '{lang}wcf.style.colorPicker.new{/lang}'
		});
		
		$('#boardTypeContainer input[type=radio]').change(function(event) {
			toggleIconContainer($(event.currentTarget).val());
		});
		
		function toggleIconContainer(boardType) {
			boardType = parseInt(boardType);
			
			// check if board is a category
			switch (boardType) {
				case 0:
					$('#iconContainer').show();
					
					if (!/wbbBoardIcon(\d+)/.test($('#iconNew').val())) {
						$('.jsBoardNewIconElement').show();
					}
				break;
				
				case 1:
					$('#iconContainer').hide();
					
					if (!/wbbBoardIcon(\d+)/.test($('#iconNew').val())) {
						$('.jsBoardNewIconElement').show();
					}
				break;
				
				case 2:
					$('#iconContainer').show();
					$('.jsBoardNewIconElement').hide();
				break;
			}
		}
		
		toggleIconContainer({@$boardType});
		
		var $colorPicker = new WCF.ColorPicker('.jsColorPicker');
		
		$('#useIconColor').change(function(event) {
			toggleColorPicker($(event.currentTarget).prop('checked'), $('#iconColorContainer'));
		});
		$('#useIconNewColor').change(function(event) {
			toggleColorPicker($(event.currentTarget).prop('checked'), $('#iconNewColorContainer'));
		});
		
		function toggleColorPicker(useColor, colorPickerContainer) {
			if (useColor) {
				colorPickerContainer.removeClass('disabled');
				colorPickerContainer.find('.jsColorPicker').click($.proxy($colorPicker._open, $colorPicker)).css({
					cursor: 'pointer',
					opacity: 1
				});
			}
			else {
				colorPickerContainer.addClass('disabled');
				colorPickerContainer.find('.jsColorPicker').unbind(WCF.ColorPicker._open).css({
					cursor: 'default',
					opacity: 0.5
				});
			}
		}
		
		toggleColorPicker({@$useIconColor}, $('#iconColorContainer'));
		toggleColorPicker({@$useIconNewColor}, $('#iconNewColorContainer'));
		
		// hide color form elements if custom icon is selected
		$('#icon').change(function() {
			if (/wbbBoardIcon(\d+)/.test($(this).val())) {
				$('#useIconColorContainer, #iconColorContainer').hide();
			}
			else {
				$('#useIconColorContainer, #iconColorContainer').show();
			}
		})
		
		$('#iconNew').change(function() {
			if (/wbbBoardIcon(\d+)/.test($(this).val())) {
				$('#useIconNewColorContainer, #iconNewColorContainer').hide();
			}
			else if ($('input[name=boardType]:checked').val() != 2) { // external links
				$('#useIconNewColorContainer, #iconNewColorContainer').show();
			}
		})
		
		$('#icon, #iconNew').change();
		
		// prepare board icon selection list
		var $icons = [ ];
		{foreach from=$iconData key='__iconIdentifier' item='__iconData'}
			$icons.push({
				icon: '{$__iconIdentifier}',
				{if $__iconData[link]|isset}
					link: '{$__iconData[link]|encodeJS}',
				{/if}
				title: '{$__iconData[title]|encodeJS}'
			});
		{/foreach}
		
		new WBB.ACP.BoardIcon.IconList($icons, '.jsFontAwesomeIconListButton');
	});
	//]]>
</script>

<fieldset id="iconContainer">
	<legend>{lang}wbb.acp.board.icons{/lang}</legend>
	
	<dl{if $errorField == 'icon'} class="formError"{/if}>
		<dt><label for="icon">{lang}wbb.acp.board.icon{/lang}</label></dt>
		<dd>
			<select name="icon" id="icon">
				<option value=""{if !$icon} selected="selected"{/if}>{lang}wbb.acp.board.icon.defaultIcon{/lang}</option>
				{htmlOptions options=$icons selected=$icon}
			</select>
			{if $errorField == 'icon'}
				<small class="innerError">
					{lang}wbb.acp.board.icon.error.{@$errorType}{/lang}
				</small>
			{/if}
			<span class="icon icon16 icon-th pointer jsFontAwesomeIconListButton jsTooltip" data-select="icon" title="{lang}wcf.icon.fontAwesome.iconSelection{/lang}"></span>
			<small>{lang}wbb.acp.board.icon.description{/lang}</small>
		</dd>
	</dl>
	
	<dl id="useIconColorContainer">
		<dt></dt>
		<dd><label><input type="checkbox" id="useIconColor" name="useIconColor"{if $useIconColor} checked="checked"{/if} /> {lang}wbb.acp.board.useIconColor{/lang}</label></dd>
	</dl>
	
	<dl id="iconColorContainer"{if $errorField == 'iconColor'} class="formError"{/if}>
		<dt><label for="iconColor">{lang}wbb.acp.board.iconColor{/lang}</label></dt>
		<dd>
			<figure>
				<div class="colorPreview"><div class="jsColorPicker" style="background-color: {$iconColor}" data-color="{$iconColor}" data-store="iconColor"></div></div>
				<input type="hidden" id="iconColor" name="iconColor" value="{$iconColor}" />
			</figure>
			{if $errorField == 'iconColor'}
				<small class="innerError">
					{lang}wbb.acp.board.iconColor.error.{@$errorType}{/lang}
				</small>
			{/if}
		</dd>
	</dl>
	
	<dl class="jsBoardNewIconElement{if $errorField == 'iconNew'} formError{/if}">
		<dt><label for="iconNew">{lang}wbb.acp.board.iconNew{/lang}</label></dt>
		<dd>
			<select name="iconNew" id="iconNew">
				<option value=""{if !$icon} selected="selected"{/if}>{lang}wbb.acp.board.icon.defaultIcon{/lang}</option>
				{htmlOptions options=$icons selected=$iconNew}
			</select>
			<span class="icon icon16 icon-th pointer jsFontAwesomeIconListButton jsTooltip" data-select="iconNew" title="{lang}wcf.icon.fontAwesome.iconSelection{/lang}"></span>
			{if $errorField == 'iconNew'}
				<small class="innerError">
					{lang}wbb.acp.board.iconNew.error.{@$errorType}{/lang}
				</small>
			{/if}
			<small>{lang}wbb.acp.board.iconNew.description{/lang}</small>
		</dd>
	</dl>
	
	<dl id="useIconNewColorContainer" class="jsBoardNewIconElement">
		<dt></dt>
		<dd><label><input type="checkbox" id="useIconNewColor" name="useIconNewColor"{if $useIconNewColor} checked="checked"{/if} /> {lang}wbb.acp.board.useIconNewColor{/lang}</label></dd>
	</dl>
	
	<dl id="iconNewColorContainer" class="jsBoardNewIconElement{if $errorField == 'iconNewColor'} formError{/if}">
		<dt><label for="iconNewColor">{lang}wbb.acp.board.iconNewColor{/lang}</label></dt>
		<dd>
			<figure>
				<div class="colorPreview"><div class="jsColorPicker" style="background-color: {$iconNewColor}" data-color="{$iconNewColor}" data-store="iconNewColor"></div></div>
				<input type="hidden" id="iconNewColor" name="iconNewColor" value="{$iconNewColor}" />
			</figure>
			{if $errorField == 'iconNewColor'}
				<small class="innerError">
					{lang}wbb.acp.board.iconNewColor.error.{@$errorType}{/lang}
				</small>
			{/if}
		</dd>
	</dl>
</fieldset>
