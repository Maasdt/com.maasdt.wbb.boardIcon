<?php
namespace wbb\data\board\icon;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit board icons.
 * 
 * @author	Matthias Schmidt
 * @copyright	2014 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.icon
 * @subpackage	data.board.icon
 * @category	Burning Board
 */
class BoardIconEditor extends DatabaseObjectEditor {
	/**
	 * @see	\wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wbb\data\board\icon\BoardIcon';
}
