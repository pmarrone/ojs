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
		$result =& $this->retrieve("SELECT gf.setting_value as group_field, j.* FROM journals j 
				JOIN journal_settings gf ON gf.journal_id = j.journal_id AND gf.setting_name = ?
				JOIN journal_settings sf ON sf.journal_id = j.journal_id AND sf.setting_name = ? AND sf.locale = ?
				ORDER BY gf.setting_value, sf.setting_value", [$groupField, $sortField, AppLocale::getLocale()]);
		
		$rows = $result->GetRows();
		
		$currentValue = null;
		$currentGroup = null;
		$groups = array();
				
		foreach($rows as $row) {
			//Add a new group when setting_value changes
			if ($currentValue != $row['group_field']) {
				$currentValue = $row['group_field'];
				$groups[$currentValue] = array(); 
			}
			$groups[$currentValue][] = $this->_returnJournalFromRow($row);
		}
		
		$result->Close();
		unset($result);

		return $groups;
	}
}

?>