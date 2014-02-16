/**
 * @author	Matthias Schmidt
 * @copyright	2014 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.boardIcon
 */

/**
 * Initializes WBB.ACP.BoardIcon namespace.
 */
WBB.ACP.BoardIcon = { };

if (WCF.Icon && WCF.Icon.FontAwesome && WCF.Icon.FontAwesome.IconList) {
	WBB.ACP.BoardIcon.IconList = WCF.Icon.FontAwesome.IconList.extend({
		/**
		 * @see	WCF.Icon.FontAwesome.IconList._createListItems()
		 */
		_createListItems: function() {
			for (var $index in this._icons) {
				var $iconName = this._icons[$index].title;
				
				var $icon = $('<span class="icon icon32 icon-' + $iconName + '" data-icon-name="' + $iconName + '" />');
				
				$('<li><div class="box32">' + $icon + ' <p>' + $iconName + '</p></div></li>').appendTo(this._iconListPrototype);
			}
		}
	});
}

/**
 * Handles uploading a board icon.
 */
WBB.ACP.BoardIcon.IconUpload = WCF.Upload.extend({
	/**
	 * upload button
	 * @var	jQuery
	 */
	_button: null,
	
	/**
	 * uploaded icon element
	 * @var	jQuery
	 */
	_icon: null,
	
	/**
	 * id of the board icon the uploaded icon file belongs to
	 * @var	integer
	 */
	_boardIconID: 0,
	
	/**
	 * temporary hash to identify uploaded icon files
	 * @var	string
	 */
	_tmpHash: '',
	
	/**
	 * @see	WCF.Upload.init()
	 */
	init: function(boardIconID, tmpHash) {
		this._boardIconID = parseInt(boardIconID) || 0;
		this._tmpHash = tmpHash;
		
		this._button = $('#uploadIcon');
		this._icon = $('#boardIcon');
		
		this._super(this._button, undefined, 'wbb\\data\\board\\icon\\BoardIconAction');
	},
	
	/**
	 * Returns element displaying the error message.
	 * 
	 * @return	jQuery
	 */
	_getInnerErrorElement: function() {
		var $span = this._button.next('.innerError');
		if (!$span.length) {
			$span = $('<small class="innerError" />').insertAfter(this._button);
		}
		
		return $span;
	},
	
	/**
	 * @see	WCF.Upload._getParameters()
	 */
	_getParameters: function() {
		return {
			boardIconID: this._boardIconID,
			tmpHash: this._tmpHash
		};
	},
	
	/**
	 * @see	WCF.Upload._initFile()
	 */
	_initFile: function(file) {
		return this._icon;
	},
	
	/**
	 * @see	WCF.Upload._success()
	 */
	_success: function(uploadID, data) {
		if (data.returnValues.url) {
			this._icon.attr('src', data.returnValues.url).show();
			
			this._button.next('.innerError').remove();
			new WCF.System.Notification().show();
		}
		else if (data.returnValues.errorMessage) {
			this._getInnerErrorElement().text(data.returnValues.errorMessage);
		}
	}
});
