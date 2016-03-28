<?php

/**
 * @brief "UNC" theme plugin
 */

import('classes.plugins.ThemePlugin');

class UncThemePlugin extends ThemePlugin {
	
	private $journalGroupDAO;
	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	private $templateManager = null;
	
	function getName() {
		return 'UncThemePlugin';
	}

	function getDisplayName() {
		return 'Unc Theme';
	}

	function getDescription() {
		return 'Unc layout';
	}

	function getStylesheetFilename() {
		return 'css/unc.css';
	}

	function getLocaleFilename($locale) {
		return null; // No locale data
	}
	
	function activate(&$templateMgr) {
		$this->templateManager = &$templateMgr;
		$this->addBootstrap($templateMgr);
		$templateMgr->addJavaScript('/plugins/themes/unc/js/slideshow.js');	
		
		$this->registerJournalGroupDAO();

		$this->insertDarwinianaIntoJournals();
		$this->assignJournalsByInstitution();
		$this->assignJournalsByCategory();
		$this->assignJournalsByInitial();
		
		
		if (($stylesheetFilename = $this->getStylesheetFilename()) != null) {			
			$path = Request::getBaseUrl() . '/' . $this->getPluginPath() . '/' . $stylesheetFilename;
			$this->templateManager->addStyleSheet($path);
		}
	}
	
	private function insertDarwinianaIntoJournals() {
		//$journals = $this->templateManager->smartyGetValue('journals');
	}

 	private function registerJournalGroupDAO() {
 		DAORegistry::registerDAO('JournalGroupDAO', $journalGroupDAO);
 		$this->import('classes.JournalGroupDAO');
		$this->import('classes.JournalMock');
 		$this->journalGroupDAO = new JournalGroupDAO($this->getName());

	}
	
	private function assignJournalsByCategory () {
		$categoryDao =& DAORegistry::getDAO('CategoryDAO');
		$cache =& $categoryDao->getCache();
		$this->insertDarwinianaIntoCategories($cache);
		
		// Sort by category name
		uasort($cache, create_function('$a, $b', '$catA = $a[\'category\']; $catB = $b[\'category\']; return strcasecmp($catA->getLocalizedName(), $catB->getLocalizedName());'));
		
		$this->templateManager->assign('journals_by_category', $cache);
		$this->templateManager->addStyleSheet('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,latin-ext');
	}
	
	private function insertDarwinianaIntoCategories(&$cache) {
		$darwinianaCategoryName = 'Área Ciencias Naturales, Básicas y Aplicadas';
		foreach($cache as &$cachedCategory) {
			//Add a new group when setting_value changes
			if ($cachedCategory != null && $cachedCategory['category']  != null && $cachedCategory['category']->getLocalizedName() === $darwinianaCategoryName) {
				$cachedCategory['journals'][] = new JournalMock();
				uasort($cachedCategory['journals'], function ($a, $b) {
					return strcmp($a->getLocalizedTitle(), $b->getLocalizedTitle());
				});
			}
		}
	}
	
	private function assignJournalsByInitial () {
		$journalsByInitial = $this->journalGroupDAO->getGroupsByInitial('title');
		$this->insertDarwinianaIntoGroup($journalsByInitial, 'R');
		$this->templateManager->assign('journals_by_initial', $journalsByInitial);
	}
	
	private function assignJournalsByInstitution() {
		$journalsByInstitution = $this->journalGroupDAO->getGroups('publisherInstitution', 'title');
		$this->insertDarwinianaIntoGroup($journalsByInstitution, 'Instituto de Botánica Darwinion y Museo Botánico de Córdoba');
		$this->templateManager->assign('journals_by_institution', $journalsByInstitution);
	}
	
	private function insertDarwinianaIntoGroup(&$cache, $group) {
		//Add a new group when setting_value changes
		if ($cache[$group]  == null) {
			$cache[$group] = array();
			ksort($cache);
		}
		$cache[$group][] = new JournalMock();
		uasort($cachedCategory[$group], function ($a, $b) {
			return strcmp($a->getLocalizedTitle(), $b->getLocalizedTitle());
		});
	}
	

	function addBootstrap(&$templateMgr) {
		$templateMgr->addStyleSheet(Request::getBaseUrl() . '/' . $this->getPluginPath() . '/css/bootstrap.min.css');
		$templateMgr->addJavaScript($this->getPluginPath().'/js/jquery-1.12.1.min.js');
		$templateMgr->addJavaScript($this->getPluginPath().'/js/bootstrap.js');
	}
	
}

?>
