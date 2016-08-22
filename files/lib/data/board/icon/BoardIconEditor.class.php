<?php
namespace wbb\data\board\icon;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit board icons.
 * 
 * @author	Matthias Schmidt
 * @copyright	2014-2016 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.icon
 * @subpackage	data.board.icon
 * @category	Burning Board
 *
 * @method static	BoardIcon	create(array $parameters = [])
 * @method		BoardIcon	getDecoratedObject()
 * @mixin		BoardIcon
 */
class BoardIconEditor extends DatabaseObjectEditor {
	/**
	 * @inheritDoc
	 */
	protected static $baseClass = BoardIcon::class;
}
