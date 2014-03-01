<?php
namespace wbb\acp\form;
use wbb\data\board\icon\BoardIcon;
use wbb\data\board\icon\BoardIconAction;
use wbb\data\board\icon\BoardIconEditor;
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
 * @copyright	2014 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.boardIcon
 * @subpackage	acp.form
 * @category	Burning Board
 */
class BoardIconEditForm extends BoardIconAddForm {
	/**
	 * @see	\wcf\page\AbstractPage::$activeMenuItem
	 */
	public $activeMenuItem = 'wbb.acp.menu.link.board';
	
	/**
	 * edited board icon
	 * @var	wbb\data\board\icon\BoardIcon
	 */
	public $boardIcon = null;
	
	/**
	 * if of the edited board icon
	 * @var	integer
	 */
	public $boardIconID = 0;
	
	/**
	 * list of available board icons
	 * @var	\wbb\data\board\icon\BoardIconList
	 */
	public $boardIcons = null;
	
	/**
	 * @see	\wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		I18nHandler::getInstance()->assignVariables(!empty($_POST));
		
		WCF::getTPL()->assign(array(
			'action' => 'edit',
			'boardIcon' => $this->boardIcon,
			'boardIcons' => $this->boardIcons
		));
	}
	
	/**
	 * @see	\wcf\page\IPage::readData()
	 */
	public function readData() {
		$this->boardIcon = new BoardIcon($this->boardIconID);
		if (!$this->boardIcon->iconID) {
			throw new IllegalLinkException();
		}
		
		parent::readData();
		
		if (empty($_POST)) {
			I18nHandler::getInstance()->setOptions('title', PackageCache::getInstance()->getPackageID('com.maasdt.wbb.boardIcon'), $this->boardIcon->title, 'wbb.acp.boardIcon.title\d+');
		}
		
		$this->boardIcons = new BoardIconList();
		$this->boardIcons->readObjects();
	}
	
	/**
	 * @see	\wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->boardIconID = intval($_REQUEST['id']);
	}
	
	/**
	 * @see	\wcf\form\IForm::save()
	 */
	public function save() {
		AbstractForm::save();
		
		$title = 'wbb.acp.boardIcon.title.boardIcon'.$this->boardIcon->iconID;
		if (I18nHandler::getInstance()->isPlainValue('title')) {
			I18nHandler::getInstance()->remove($title);
			$title = I18nHandler::getInstance()->getValue('title');
		}
		else {
			I18nHandler::getInstance()->save('title', $title, 'wbb.acp.boardIcon', PackageCache::getInstance()->getPackageID('com.maasdt.wbb.boardIcon'));
		}
		
		$this->objectAction = new BoardIconAction(array($this->boardIcon), 'update', array(
			'data' => array(
				'title' => $title
			),
			'tmpHash' => $this->tmpHash
		));
		$this->objectAction->executeAction();
		
		$this->saved();
	}
	
	/**
	 * @see	\wcf\form\AbstractForm::saved()
	 */
	protected function saved() {
		AbstractForm::saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see	\wbb\acp\form\BoardIconAddForm::validateIcon()
	 */
	protected function validateIcon() {
		// does nothing
	}
}
