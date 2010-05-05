<?php
	
	class Extension_Collapse_Fields extends Extension {
	/*-------------------------------------------------------------------------
		Extension definition
	-------------------------------------------------------------------------*/
		
		public static $params = null;
		
		public function about() {
			return array(
				'name'			=> 'Field: Collapse Fields',
				'version'		=> '1.0.0',
				'release-date'	=> '2010-05-05',
				'author'		=> array(
					'name'			=> 'Guillem Lorman',
					'website'		=> 'http://www.bajoelcocotero.com',
					'email'			=> 'guillem@bajoelcocotero.com'
				),
				'description'	=> 'Add a link to collapse the folowing "n" fields in the publish page, making possible to the user to show or hide a group of fields.'
			);
		}
		
		public function uninstall() {
			$this->_Parent->Database->query("DROP TABLE `tbl_fields_collapse_fields`");
		}
		
		public function install() {
			$this->_Parent->Database->query("
				CREATE TABLE IF NOT EXISTS `tbl_fields_collapse_fields` (
					`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`field_id` INT(11) UNSIGNED NOT NULL,
					`num_fields` VARCHAR(255) DEFAULT NULL,					
					`collapse` ENUM('yes', 'no') DEFAULT 'no',
					PRIMARY KEY (`id`),
					KEY `field_id` (`field_id`)
				)
			");
			
			return true;
		}

		public function getSubscribedDelegates() {
			return array(
				array(
					'page'		=> '/publish/new/',
					'delegate'	=> 'EntryPostCreate',
					'callback'	=> 'compileBackendFields'
				),
				array(
					'page'		=> '/publish/edit/',
					'delegate'	=> 'EntryPostEdit',
					'callback'	=> 'compileBackendFields'
				),
				array(
					'page'		=> '/backend/',
					'delegate'	=> 'InitaliseAdminPageHead',
					'callback'	=> 'initaliseAdminPageHead'
				)
			);
		}
		
	/*-------------------------------------------------------------------------
		Fields:
	-------------------------------------------------------------------------*/
		
		public function registerField($field) {
			self::$fields[] = $field;
		}
		
		public function compileBackendFields($context) {
			foreach (self::$fields as $field) {
				$field->compile($context['entry']);
			}
		}
		
		public function compileFrontendFields($context) {
			foreach (self::$fields as $field) {
				$field->compile($context['entry']);
			}
		}

	/*-------------------------------------------------------------------------
		Delegates:
	-------------------------------------------------------------------------*/
		
		public function initaliseAdminPageHead($context) {
			$page = $context['parent']->Page;
						
			$page->addScriptToHead(URL . '/extensions/collapse_fields/assets/collapse_fields.js', 9200);
			$page->addStylesheetToHead(URL . '/extensions/collapse_fields/assets/collapse_fields.css', 'screen', 9200);
		}
	}
	
?>