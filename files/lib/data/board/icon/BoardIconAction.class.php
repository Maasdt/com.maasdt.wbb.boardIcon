<?php
namespace wbb\data\board\icon;
use wbb\system\board\BoardIconHandler;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\UserInputException;
use wcf\system\upload\DefaultUploadFileValidationStrategy;
use wcf\system\WCF;

/**
 * Executes board-icon related actions.
 * 
 * @author	Matthias Schmidt
 * @copyright	2014-2016 Maasdt
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	com.maasdt.wbb.boardIcon
 * @subpackage	data.board.icon
 * @category	Burning Board
 */
class BoardIconAction extends AbstractDatabaseObjectAction {
	/**
	 * board icon the uploaded icon file belongs to
	 * @var	wbb\data\board\icon\BoardIcon
	 */
	protected $boardIcon = null;
	
	/**
	 * @see	\wcf\data\AbstractDatabaseObjectAction::$permissionsDelete
	 */
	protected $permissionsDelete = array('admin.board.canManageBoardIcon');
	
	/**
	 * @see	\wcf\data\AbstractDatabaseObjectAction::$requireACP
	 */
	protected $requireACP = array('delete', 'update', 'upload');
	
	/**
	 * @see	\wcf\data\AbstractDatabaseObjectAction::create()
	 */
	public function create() {
		$this->parameters['data']['fileExtension'] = WCF::getSession()->getVar('wbbBoardIcon-'.$this->parameters['tmpHash']);
		$fileLocation = WBB_DIR.'icon/board/tmp/'.$this->parameters['tmpHash'].'.'.$this->parameters['data']['fileExtension'];
		
		$this->parameters['data']['fileHash'] = sha1_file($fileLocation);
		$this->parameters['data']['filesize'] = filesize($fileLocation);
		
		$boardIcon = parent::create();
		
		// move file to final position
		rename($fileLocation, $boardIcon->getLocation());
		WCF::getSession()->unregister('wbbBoardIcon-'.$this->parameters['tmpHash']);
		
		return $boardIcon;
	}
	
	/**
	 * @see	\wcf\data\IDeleteAction::delete()
	 */
	public function delete() {
		$returnValue = parent::delete();
		
		// delete files
		foreach ($this->objects as $boardIcon) {
			@unlink($boardIcon->getLocation());
		}
		
		BoardIconHandler::getInstance()->writeStyleFile();
		
		return $returnValue;
	}
	
	/**
	 * Validates the 'upload' action.
	 */
	public function validateUpload() {
		// validate permissions
		WCF::getSession()->checkPermissions($this->permissionsDelete);
		
		$this->readInteger('boardIconID', true);
		$this->readString('tmpHash');
		
		if ($this->parameters['boardIconID']) {
			$this->boardIcon = new BoardIcon($this->parameters['boardIconID']);
			if (!$this->boardIcon->iconID) {
				throw new UserInputException('boardIconID');
			}
		}
		
		if (count($this->parameters['__files']->getFiles()) != 1) {
			throw new UserInputException('files');
		}
		
		// validate file
		$this->parameters['__files']->validateFiles(new DefaultUploadFileValidationStrategy(PHP_INT_MAX, array('gif', 'jpg', 'jpeg', 'png')));
	}
	
	/**
	 * Uploads a board icon.
	 * 
	 * @return	array<string>
	 */
	public function upload() {
		$files = $this->parameters['__files']->getFiles();
		$file = $files[0];
		
		$errorType = $file->getValidationErrorType();
		if (!$errorType) {
			$imageData = $file->getImageData();
			if ($imageData === null) {
				$errorType = 'noImage';
			}
			else if ($imageData['height'] < 32) {
				$errorType = 'minHeight';
			}
			else if ($imageData['width'] < 32) {
				$errorType = 'minWidth';
			}
			else {
				if ($this->boardIcon) {
					$fileHash = sha1_file($file->getLocation());
					$newFileLocation = WBB_DIR.'icon/board/'.$this->boardIcon->iconID.'-'.$fileHash.'.'.$file->getFileExtension();
					if (@copy($file->getLocation(), $newFileLocation)) {
						@unlink($file->getLocation());
						
						$boardIconEditor = new BoardIconEditor($this->boardIcon);
						$boardIconEditor->update(array(
							'fileExtension' => $file->getFileExtension(),
							'fileHash' => $fileHash,
							'filesize' => filesize($newFileLocation)
						));
						
						BoardIconHandler::getInstance()->writeStyleFile();
						
						$this->boardIcon = new BoardIcon($this->boardIcon->iconID);
						
						return array(
							'url' => $this->boardIcon->getLink()
						);
					}
					else {
						$errorType = 'uploadFailed';
					}
				}
				else if (@copy($file->getLocation(), WBB_DIR.'icon/board/tmp/'.$this->parameters['tmpHash'].'.'.$file->getFileExtension())) {
					@unlink($fileLocation);
					
					WCF::getSession()->register('wbbBoardIcon-'.$this->parameters['tmpHash'], $file->getFileExtension());
					
					return array(
						'url' => WCF::getPath('wbb').'icon/board/tmp/'.$this->parameters['tmpHash'].'.'.$file->getFileExtension()
					);
				}
				else {
					$errorType = 'uploadFailed';
				}
			}
		}
		
		return array(
			'errorMessage' => WCF::getLanguage()->get('wbb.acp.boardIcon.icon.error.'.$errorType)
		);
	}
}
