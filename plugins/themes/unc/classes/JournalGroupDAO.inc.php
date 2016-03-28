<?php
import('classes.journal.JournalDAO');
import('classes.i18n.AppLocale');
require_once('ThemeUtils.inc.php');

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

	function &getGroups($groupField, $sortField, $groupFunctionName='groupRowsByGroupField') {
		$result =& $this->retrieve("SELECT gf.setting_value as group_field, j.* FROM journals j 
				JOIN journal_settings gf ON gf.journal_id = j.journal_id AND gf.setting_name = ? AND (gf.locale = '' OR gf.locale = ?)
				JOIN journal_settings sf ON sf.journal_id = j.journal_id AND sf.setting_name = ? AND (sf.locale = '' OR sf.locale = ?)
				ORDER BY gf.setting_value, sf.setting_value", [$groupField, AppLocale::getLocale(), $sortField, AppLocale::getLocale()]);
		
		$rows = $result->GetRows();
		

		$groups = call_user_func_array(array($this, $groupFunctionName), array(&$rows));
		
		$result->Close();
		unset($result);

		return $groups;
	}
	
	function &getGroupsByInitial($groupField) {
		return $this->getGroups($groupField, $groupField, 'groupRowsByInitial');
	}
	
	
	private function &groupRowsByGroupField(&$rows) {
		$groups = array();
		$currentValue = null;
		$currentGroup = null;
		foreach($rows as $row) {
			//Add a new group when setting_value changes
			if ($currentValue != $row['group_field']) {
				$currentValue = $row['group_field'];
				$groups[$currentValue] = array();
			}
			$groups[$currentValue][] = $this->_returnJournalFromRow($row);
		}
		return $groups;
	}
	
	private function &groupRowsByInitial(&$rows) {
		$groups = array();
		$currentValue = null;
		$currentGroup = null;
		foreach($rows as $row) {
			//Add a new group when setting_value changes
			$currentGroupInitial = mb_strtoupper(ThemeUtils::stripAccents(mb_substr($row['group_field'], 0, 1)));
			if ($currentValue != $currentGroupInitial) {
				$currentValue = $currentGroupInitial;
				$groups[$currentValue] = array();
			}
			$groups[$currentValue][] = $this->_returnJournalFromRow($row);
		}
		return $groups;
	}
}

?>