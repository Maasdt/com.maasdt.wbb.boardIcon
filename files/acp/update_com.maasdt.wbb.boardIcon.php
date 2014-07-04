<?php
use wbb\system\board\BoardIconHandler;

/**
 * Rewrite board icon file after package update.
 * 
 * @author	Matthias Schmidt
 * @copyright	2014 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.icon
 * @category	Burning Board
 */
BoardIconHandler::getInstance()->writeStyleFile();
