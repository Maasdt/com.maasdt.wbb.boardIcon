<?php
namespace wbb\acp\form;
use wbb\data\board\icon\BoardIcon;
use wbb\data\board\icon\BoardIconAction;
use wbb\data\board\icon\BoardIconList;
use wcf\data\package\PackageCache;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\language\I18nHandler;
use wcf\system\WCF;

/**
 * Shows the form to edit an existing board icon.
 * 
 * @author	Matthias Schmidt
 * @copyright	2014-2016 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.boardIcon
 * @subpackage	acp.form
 * @category	Burning Board
 */
class BoardIconEditForm extends BoardIconAddForm {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wbb.acp.menu.link.board';
	
	/**
	 * edited board icon
	 * @var	BoardIcon
	 */
	public $boardIcon;
	
	/**
	 * if of the edited board icon
	 * @var	integer
	 */
	public $boardIconID = 0;
	
	/**
	 * list of available board icons
	 * @var	BoardIconList
	 */
	public $boardIcons;
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		I18nHandler::getInstance()->assignVariables(!empty($_POST));
		
		WCF::getTPL()->assign([
			'action' => 'edit',
			'boardIcon' => $this->boardIcon,
			'boardIcons' => $this->boardIcons
		]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function readData() {
		$this->boardIcon = new BoardIcon($this->boardIconID);
		if (!$this->boardIcon->iconID) {
			throw new IllegalLinkException();
		}
		
		parent::readData();
		
		if (empty($_POST)) {
			I18nHandler::getInstance()->setOptions(
				'title',
				PackageCache::getInstance()->getPackageID('com.maasdt.wbb.boardIcon'),
				$this->boardIcon->title,
				'wbb.acp.boardIcon.title\d+'
			);
		}
		
		$this->boardIcons = new BoardIconList();
		$this->boardIcons->readObjects();
	}
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->boardIconID = intval($_REQUEST['id']);
	}
	
	/**
	 * @inheritDoc
	 */
	public function save() {
		AbstractForm::save();
		
		$title = 'wbb.acp.boardIcon.title.boardIcon'.$this->boardIcon->iconID;
		if (I18nHandler::getInstance()->isPlainValue('title')) {
			I18nHandler::getInstance()->remove($title);
			$title = I18nHandler::getInstance()->getValue('title');
		}
		else {
			I18nHandler::getInstance()->save(
				'title',
				$title,
				'wbb.acp.boardIcon',
				PackageCache::getInstance()->getPackageID('com.maasdt.wbb.boardIcon')
			);
		}
		
		$this->objectAction = new BoardIconAction([$this->boardIcon], 'update', [
			'data' => array_merge($this->additionalFields, [
				'title' => $title
			]),
			'tmpHash' => $this->tmpHash
		]);
		$this->objectAction->executeAction();
		
		$this->saved();
	}
	
	/**
	 * @inheritDoc
	 */
	protected function saved() {
		AbstractForm::saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @inheritDoc
	 */
	protected function validateIcon() {
		// does nothing
	}
}
