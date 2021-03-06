<?php
	
	if (!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');
	
	class FieldCollapse_Fields extends Field {
		protected static $ready = true;
		
		public function __construct() {
			parent::__construct();
			
			$this->_name = 'Collapse Fields';
			
			// Set defaults:
			// $this->set('show_column', 'no');
		}
		
		public function createTable() {
			$field_id = $this->get('id');
			
			return Symphony::Database()->query("
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
						
			// $this->appendShowColumnCheckbox($wrapper);
			
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
			
			Symphony::Database()->query("
				DELETE FROM
					`tbl_fields_{$handle}`
				WHERE
					`field_id` = '{$id}'
				LIMIT 1
			");
			
			return Symphony::Database()->insert($fields, "tbl_fields_{$handle}");
		}
		
	/*-------------------------------------------------------------------------
		Publish:
	-------------------------------------------------------------------------*/
		
		public function displayPublishPanel(&$wrapper, $data = null, $flagWithError = null, $prefix = null, $postfix = null) {
			$sortorder = $this->get('sortorder');
			$element_name = $this->get('element_name');
			$allow_override = null;
			
			$label = Widget::Label('',null,'subsection');

			$h4 = new XMLElement('h4');
			
			$anchor = Widget::Anchor(
				($this->get('collapse') == 'yes' ? '(+) ' : '(-) ').$this->get('label'),
				'#'.$this->get('num_fields'),
				null,
				'collapse_field '.($this->get('collapse') == 'yes' ? 'hide' : '')
			);
						
			$h4->appendChild($anchor);
			
			$label->appendChild($h4);
			$wrapper->appendChild($label);
		}		
		
	}