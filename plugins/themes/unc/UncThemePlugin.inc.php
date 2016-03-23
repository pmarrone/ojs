<?php

/**
 * @file plugins/themes/steel/SteelThemePlugin.inc.php
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SteelThemePlugin
 * @ingroup plugins_themes_steel
 *
 * @brief "Steel" theme plugin
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
		
		$this->registerJournalGroupDAO ();

		$this->assignJournalsByInstitution();
		$this->assignJournalsByCategory();
		$this->assignJournalsByInitial();
		
		
		if (($stylesheetFilename = $this->getStylesheetFilename()) != null) {			
			$path = Request::getBaseUrl() . '/' . $this->getPluginPath() . '/' . $stylesheetFilename;
			$templateMgr->addStyleSheet($path);
		}
	}

 	private function registerJournalGroupDAO() {
 		DAORegistry::registerDAO('JournalGroupDAO', $journalGroupDAO);
 		$this->import('classes.JournalGroupDAO');

 		$this->journalGroupDAO = new JournalGroupDAO($this->getName());

	}
	
	private function assignJournalsByCategory () {
		$categoryDao =& DAORegistry::getDAO('CategoryDAO');
		$cache =& $categoryDao->getCache();
		// Sort by category name
		uasort($cache, create_function('$a, $b', '$catA = $a[\'category\']; $catB = $b[\'category\']; return strcasecmp($catA->getLocalizedName(), $catB->getLocalizedName());'));
		$this->templateManager->assign('jounals_by_category', $cache);
		$this->templateManager->addStyleSheet('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,latin-ext');
	}
	
	private function assignJournalsByInitial () {
		$this->templateManager->assign('journals_by_initial', $this->journalGroupDAO->getGroupsByInitial('title'));
	}
	
	private function assignJournalsByInstitution() {
		$this->templateManager->assign('journals_by_institution', $this->journalGroupDAO->getGroups('publisherInstitution', 'title'));
	}

	function addBootstrap(&$templateMgr) {
		$templateMgr->addStyleSheet(Request::getBaseUrl() . '/' . $this->getPluginPath() . '/css/bootstrap.min.css');
		$templateMgr->addJavaScript($this->getPluginPath().'/js/jquery-1.12.1.min.js');
		$templateMgr->addJavaScript($this->getPluginPath().'/js/bootstrap.js');
	}
}

?>
