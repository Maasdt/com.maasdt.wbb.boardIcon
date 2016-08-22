<?php
namespace wbb\acp\page;
use wbb\data\board\icon\BoardIconList;
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
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wbb.acp.menu.link.boardIcon.list';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermission = ['admin.board.canManageBoardIcon'];
	
	/**
	 * @inheritDoc
	 */
	public $objectListClassName = BoardIconList::class;
}
