<?php
import('classes.journal.JournalDAO');

class JournalGroupDAO extends JournalDAO {
	/** @var $parentPluginName string Name of parent plugin */
	var $parentPluginName;

	/**
	 * Constructor
	 */
	function JournalGroupDAO($parentPluginName){
		$this->parentPluginName = $parentPluginName;
		parent::DAO();
	}

	function &getGroups($groupField, $sortField) {
		$result =& $this->retrieve(
				"SELECT js.setting_value, j.* FROM journals j 
				JOIN journal_settings js ON js.journal_id = j.journal_id 
				JOIN journal_settings sf ON sf.journal = j.journal_id 
				ORDER BY ?, ?", $groupField, $sortField);
		$rows = $result.GetRows();
		
		$currentValue = null;
		$currentGroup = null;
		$groups = array();
				
		foreach($rows as $row) {
			//Add a new group when setting_value changes
			if ($currentValue != $row['setting_value']) {
				$currentValue = $row['setting_value'];
				$currentGroup = array();
				$groups[$currentValue] = $currentGroup; 
			}
			$currentGroup[] = $this->_returnJournalFromRow($row);
		}
		
		$result->Close();
		unset($result);

		return $groups;
	}
}

?>