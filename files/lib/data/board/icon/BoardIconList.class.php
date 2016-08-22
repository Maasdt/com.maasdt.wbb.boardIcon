<?php
namespace wbb\data\board\icon;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of board icons.
 * 
 * @author	Matthias Schmidt
 * @copyright	2014-2016 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.boardIcon
 * @subpackage	data.board.icon
 * @category	Burning Board
 *
 * @method	BoardIcon	current()
 * @method	BoardIcon[]	getObjects()
 * @method	BoardIcon|null	search($objectID)
 * @property	BoardIcon[]	$objects
 */
class BoardIconList extends DatabaseObjectList {}
