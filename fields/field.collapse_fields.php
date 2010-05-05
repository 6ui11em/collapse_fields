<?php
	
	if (!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');
	
	class FieldCollapse_Fields extends Field {
		protected static $ready = true;
		
		public function __construct(&$parent) {
			parent::__construct($parent);
			
			$this->_name = 'Collapse Fields';
			$this->_driver = $this->_engine->ExtensionManager->create('collapse_fields');
			
			// Set defaults:
			$this->set('show_column', 'no');
		}
		
		public function createTable() {
			$field_id = $this->get('id');
			
			return $this->_engine->Database->query("
				CREATE TABLE IF NOT EXISTS `tbl_entries_data_{$field_id}` (
					`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`entry_id` INT(11) UNSIGNED NOT NULL,
					`value` TEXT DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `entry_id` (`entry_id`),
					FULLTEXT KEY `value` (`value`)
				)
			");
		}
		
	/*-------------------------------------------------------------------------
		Settings:
	-------------------------------------------------------------------------*/
		
		public function displaySettingsPanel(&$wrapper, $errors = null) {
			parent::displaySettingsPanel($wrapper, $errors);
			
			$order = $this->get('sortorder');
						
			$label = Widget::Label('Number of fields');
			$label->appendChild(Widget::Input(
				"fields[{$order}][num_fields]",
				$this->get('num_fields')
			));			
			$help = new XMLElement('p', 'Indicate the number of fields to collapse.');
			$help->setAttribute('class', 'help');
			$wrapper->appendChild($label);
			
			$label = Widget::Label();
			$input = Widget::Input("fields[{$order}][collapse]", 'yes', 'checkbox');
			if ($this->get('collapse') == 'yes') $input->setAttribute('checked', 'checked');
			$label->setValue($input->generate() . ' Hide fields by default');
			$wrapper->appendChild($label);
						
			$this->appendShowColumnCheckbox($wrapper);
			
		}
		
		public function commit() {
			if (!parent::commit()) return false;
			
			$id = $this->get('id');
			$handle = $this->handle();
			
			if ($id === false) return false;
			
			$fields = array(
				'field_id'			=> $id,
				'num_fields'		=> $this->get('num_fields'),
				'collapse'		=> $this->get('collapse')
			);
			
			$this->Database->query("
				DELETE FROM
					`tbl_fields_{$handle}`
				WHERE
					`field_id` = '{$id}'
				LIMIT 1
			");
			
			return $this->Database->insert($fields, "tbl_fields_{$handle}");
		}
		
	/*-------------------------------------------------------------------------
		Publish:
	-------------------------------------------------------------------------*/
		
		public function displayPublishPanel(&$wrapper, $data = null, $flagWithError = null, $prefix = null, $postfix = null) {
			$sortorder = $this->get('sortorder');
			$element_name = $this->get('element_name');
			$allow_override = null;
			
			$label = Widget::Label('');
			$span = new XMLElement('span');
			
			$anchor = Widget::Anchor(
				($this->get('collapse') == 'yes' ? '(+) ' : '(-) ').$this->get('label'),
				'#'.$this->get('num_fields'),
				null,
				'collapse_field '.($this->get('collapse') == 'yes' ? 'hide' : '')
			);
						
			$span->appendChild($anchor);
			
			$label->appendChild($span);
			$wrapper->appendChild($label);
		}		
		
	}