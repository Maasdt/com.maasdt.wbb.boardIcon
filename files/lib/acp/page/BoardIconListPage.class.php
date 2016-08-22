<?php
namespace wbb\acp\page;
use wcf\page\MultipleLinkPage;

/**
 * Shows all uploaded board icons.
 * 
 * @author	Matthias Schmidt
 * @copyright	2014-2016 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.boardIcon
 * @subpackage	acp.page
 * @category	Burning Board
 */
class BoardIconListPage extends MultipleLinkPage {
	/**
	 * @see	\wcf\page\AbstractPage::$activeMenuItem
	 */
	public $activeMenuItem = 'wbb.acp.menu.link.boardIcon.list';
	
	/**
	 * @see	\wcf\page\AbstractPage::$neededPermission
	 */
	public $neededPermission = array('admin.board.canManageBoardIcon');
	
	/**
	 * @see	\wcf\page\MultipleLinkPage::$objectListClassName
	 */
	public $objectListClassName = 'wbb\data\board\icon\BoardIconList';
}
