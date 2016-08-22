<?php
namespace wbb\data\board\icon;
use wcf\data\DatabaseObject;
use wcf\data\ILinkableObject;
use wcf\system\request\IRouteController;
use wcf\system\WCF;

/**
 * Represents an uploaded icon which can be used as board icon.
 * 
 * @author	Tim Duesterhus, Matthias Schmidt
 * @copyright	2014-2016 Maasdt, wbbaddons
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.boardIcon
 * @subpackage	data.board.icon
 * @category	Burning Board
 * 
 * @property-read	integer		$iconID			unique id of the board icon
 * @property-read	string		$title			title of the board icon or name of language item which contains the title
 * @property-read	string		$fileExtension		extension of the physical board icon file
 * @property-read	string		$fileHash		hash of the physical board icon file
 * @property-read	integer		$filesize		size of the physical board icon file
 */
class BoardIcon extends DatabaseObject implements ILinkableObject, IRouteController {
	/**
	 * @inheritDoc
	 */
	public function getLink() {
		return WCF::getPath('wbb').'icon/board/'.$this->iconID.'-'.$this->fileHash.'.'.$this->fileExtension;
	}
	
	/**
	 * Returns the physical location of the file.
	 * 
	 * @return	string
	 */
	public function getLocation() {
		return WBB_DIR.'icon/board/'.$this->iconID.'-'.$this->fileHash.'.'.$this->fileExtension;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getTitle() {
		return WCF::getLanguage()->get($this->title);
	}
}
