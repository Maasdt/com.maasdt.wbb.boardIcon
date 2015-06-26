<?php
namespace wbb\data\board\icon;
use wbb\data\WBBDatabaseObject;
use wcf\data\ILinkableObject;
use wcf\system\request\IRouteController;
use wcf\system\WCF;
use wcf\util\FileUtil;

/**
 * Represents an uploaded icon which can be used as board icon.
 * 
 * @author	Tim Duesterhus, Matthias Schmidt
 * @copyright	2014-2015 Maasdt, wbbaddons
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.boardIcon
 * @subpackage	data.board.icon
 * @category	Burning Board
 */
class BoardIcon extends WBBDatabaseObject implements ILinkableObject, IRouteController {
	/**
	 * @see	\wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'iconID';
	
	/**
	 * @see	\wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'board_icon';
	
	/**
	 * @see	\wcf\data\ILinkableObject::getLink()
	 */
	public function getLink() {
		return FileUtil::removeTrailingSlash(FileUtil::getRelativePath(WCF_DIR.'style/', $this->getLocation()));
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
	 * @see	\wcf\data\ITitledObject::getTitle()
	 */
	public function getTitle() {
		return WCF::getLanguage()->get($this->title);
	}
}
