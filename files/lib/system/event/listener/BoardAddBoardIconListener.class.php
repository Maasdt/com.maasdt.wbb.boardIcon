<?php
namespace wbb\system\event\listener;
use wbb\acp\form\BoardAddForm;
use wbb\acp\form\BoardEditForm;
use wbb\data\board\icon\BoardIcon;
use wbb\data\board\icon\BoardIconList;
use wbb\system\board\BoardIconHandler;
use wcf\system\event\IEventListener;
use wcf\system\exception\UserInputException;
use wcf\system\Regex;
use wcf\system\WCF;
use wcf\util\FontAwesomeIconUtil;
use wcf\util\StringUtil;

/**
 * Handles the board icons when creating/editing boards.
 * 
 * @author	Matthias Schmidt
 * @copyright	2014-2016 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.boardIcon
 * @subpackage	system.board
 * @category	Burning Board
 */
class BoardAddBoardIconListener implements IEventListener {
	/**
	 * name of the board icon
	 * @var	string
	 */
	protected $icon = '';
	
	/**
	 * color of the board icon
	 * @var	string
	 */
	protected $iconColor = 'rgba(0, 0, 0, 1)';
	
	/**
	 * data of the available icons used for the selection dialog
	 * @var	array
	 */
	protected $iconData = array();
	
	/**
	 * name of the board icon if the board contains unread threads
	 * @var	string
	 */
	protected $iconNew = '';
	
	/**
	 * color of the board icon if the board contains unread threads
	 * @var	string
	 */
	protected $iconNewColor = 'rgba(0, 0, 0, 1)';
	
	/**
	 * list of available icons
	 * @var	array
	 */
	protected $icons = null;
	
	/**
	 * indicates if a certain color is used for the board icon
	 * @var	integer
	 */
	protected $useIconColor = 0;
	
	/**
	 * indicates if a certain color is used for the board icon if the board
	 * contains unread threads
	 * @var	integer
	 */
	protected $useIconNewColor = 0;
	
	/**
	 * @see	\wcf\page\IPage::assignVariables()
	 */
	protected function assignVariables(BoardAddForm $eventObj) {
		WCF::getTPL()->assign(array(
			'icon' => $this->icon,
			'iconColor' => $this->iconColor ?: 'rgba(0, 0, 0, 1)',
			'iconData' => $this->iconData,
			'iconNew' => $this->iconNew,
			'iconNewColor' => $this->iconNewColor ?: 'rgba(0, 0, 0, 1)',
			'icons' => $this->icons,
			'useIconColor' => $this->useIconColor,
			'useIconNewColor' => $this->useIconNewColor
		));
	}
	
	/**
	 * @see	\wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (method_exists($this, $eventName)) {
			if ($this->icons === null) {
				$this->iconData = $this->icons = array();
				
				$boardIconList = new BoardIconList();
				$boardIconList->readObjects();
				if (count($boardIconList)) {
					$sortedBoardIcons = $boardIconList->getObjects();
					uasort($sortedBoardIcons, function(BoardIcon $boardIconA, BoardIcon $boardIconB) {
						return strcmp($boardIconA->getTitle(), $boardIconB->getTitle());
					});
					
					$boardIcons = array();
					foreach ($sortedBoardIcons as $boardIcon) {
						$this->icons['wbbBoardIcon'.$boardIcon->iconID] = $boardIcon->getTitle();
						$this->iconData['wbbBoardIcon'.$boardIcon->iconID] = array(
							'link' => $boardIcon->getLink(),
							'title' => $boardIcon->getTitle()
						);
					}
				}
				
				$this->icons = array_merge($this->icons, FontAwesomeIconUtil::getIcons());
				
				foreach (FontAwesomeIconUtil::getIcons() as $faIcon) {
					$this->iconData[$faIcon] = array(
						'title' => $faIcon
					);
				}
			}
			
			$this->$eventName($eventObj);
		}
	}
	
	/**
	 * @see	\wcf\form\IForm::readFormParameters()
	 */
	protected function readFormParameters(BoardAddForm $eventObj) {
		if (isset($_POST['icon'])) $this->icon = StringUtil::trim($_POST['icon']);
		if (isset($_POST['iconColor'])) $this->iconColor = StringUtil::trim($_POST['iconColor']);
		if (isset($_POST['iconNew'])) $this->iconNew = StringUtil::trim($_POST['iconNew']);
		if (isset($_POST['iconNewColor'])) $this->iconNewColor = StringUtil::trim($_POST['iconNewColor']);
		if (isset($_POST['useIconColor'])) $this->useIconColor = 1;
		if (isset($_POST['useIconNewColor'])) $this->useIconNewColor = 1;
	}
	
	/**
	 * @see	\wcf\page\IPage::readData()
	 */
	protected function readData(BoardEditForm $eventObj) {
		if (empty($_POST)) {
			$this->icon = $eventObj->board->icon;
			$this->iconColor = $eventObj->board->iconColor;
			$this->iconNew = $eventObj->board->iconNew;
			$this->iconNewColor = $eventObj->board->iconNewColor;
			$this->useIconColor = empty($eventObj->board->iconColor) ? 0 : 1;
			$this->useIconNewColor = empty($eventObj->board->iconNewColor) ? 0 : 1;
		}
	}
	
	/**
	 * @see	\wcf\form\IForm::save()
	 */
	protected function save(BoardAddForm $eventObj) {
		$eventObj->additionalFields = array_merge($eventObj->additionalFields, array(
			'icon' => $this->icon,
			'iconColor' => $this->iconColor,
			'iconNew' => $this->iconNew,
			'iconNewColor' => $this->iconNewColor
		));
	}
	
	/**
	 * @see	\wcf\form\IForm::save()
	 */
	protected function saved(BoardAddForm $eventObj) {
		if (!($eventObj instanceof BoardEditForm)) {
			$this->icon = '';
			$this->iconColor = 'rgba(0, 0, 0, 1)';
			$this->iconNew = '';
			$this->iconNewColor = 'rgba(0, 0, 0, 1)';
			$this->useIconColor = 0;
			$this->useIconNewColor = 0;
		}
		
		BoardIconHandler::getInstance()->writeStyleFile();
	}
	
	/**
	 * @see	\wcf\form\IForm::validate()
	 */
	protected function validate(BoardAddForm $eventObj) {
		if (!empty($this->icon) && !isset($this->iconData[$this->icon])) {
			throw new UserInputException('icon', 'notValid');
		}
		
		$regex = new Regex('rgba\(\d{1,3}, \d{1,3}, \d{1,3}, (1|1\.00?|0|0?\.[0-9]{1,2})\)');
		if ($this->useIconColor) {
			if (!$regex->match($this->iconColor)) {
				throw new UserInputException('iconColor', 'notValid');
			}
		}
		else {
			$this->iconColor = '';
		}
		
		if (!empty($this->iconNew) && !isset($this->iconData[$this->iconNew])) {
			throw new UserInputException('iconNew', 'notValid');
		}
		
		if ($this->useIconNewColor) {
			if (!$regex->match($this->iconNewColor)) {
				throw new UserInputException('iconNewColor', 'notValid');
			}
		}
		else {
			$this->iconNewColor = '';
		}
	}
}
