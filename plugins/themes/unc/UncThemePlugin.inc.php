<?php

/**
 * @brief "UNC" theme plugin
 */

import('classes.plugins.ThemePlugin');

class UncThemePlugin extends ThemePlugin {
	
	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	
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
		
		$this->hookTemplate('Templates::Common::Footer::PageFooter', 'unc-footer.tpl');
		
		$themeTemplateDir = Core::getBaseDir() . DIRECTORY_SEPARATOR . $this->getPluginPath() . '/templates';
		$this->templateManager = &$templateMgr;
		array_unshift($this->templateManager->template_dir, $themeTemplateDir);
		$this->addBootstrap($templateMgr);
		
		$templateMgr->addJavaScript($this->getPluginPath() .'/js/touchSwipe/jquery.touchSwipe.js');
		$templateMgr->addJavaScript($this->getPluginPath().'/js/slideshow.js');
		
		$this->registerJournalGroupDAO();
		
		$this->assignJournalsExtended();
		$this->assignJournalsByInstitution();
		$this->assignJournalsByCategory();
		$this->assignJournalsByInitial();
		
		
		if (($stylesheetFilename = $this->getStylesheetFilename()) != null) {			
			$path = Request::getBaseUrl() . '/' . $this->getPluginPath() . '/' . $stylesheetFilename;
			$this->templateManager->addStyleSheet($path);
		}
	}
	
 	function registerJournalGroupDAO() {
 		DAORegistry::registerDAO('JournalGroupDAO', $journalGroupDAO);
 		$this->import('classes.JournalGroupDAO');
		$this->import('classes.JournalMock');
 		$this->journalGroupDAO = new JournalGroupDAO($this->getName());

	}
	
	function assignJournalsExtended() {
		$journalsExtended =& $this->journalGroupDAO->getJournals(true);
	    $journalsExtended = $journalsExtended->toArray();
		$this->insertDarwinianaIntoJournalsExtended($journalsExtended);
		//Sort journals
		uasort($journalsExtended, function ($a, $b) {
			return strcmp($a->getLocalizedTitle(), $b->getLocalizedTitle());
		});
		$this->templateManager->assign('journals_extended', $journalsExtended);
	}

	function assignJournalsByCategory () {
		$categoryDao =& DAORegistry::getDAO('CategoryDAO');
		$cache =& $categoryDao->getCache();
		$this->insertDarwinianaIntoCategories($cache);
		
		// Sort by category name
		uasort($cache, create_function('$a, $b', '$catA = $a[\'category\']; $catB = $b[\'category\']; return strcasecmp($catA->getLocalizedName(), $catB->getLocalizedName());'));
		
		$this->templateManager->assign('journals_by_category', $cache);
		$this->templateManager->addStyleSheet('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,latin-ext');
	}
	
	function assignJournalsByInitial () {
		$journalsByInitial = $this->journalGroupDAO->getGroupsByInitial('title');
		$this->insertDarwinianaIntoGroup($journalsByInitial, 'R');
		$this->templateManager->assign('journals_by_initial', $journalsByInitial);
	}
	
	function assignJournalsByInstitution() {
		$journalsByInstitution = $this->journalGroupDAO->getGroups('publisherInstitution', 'title');
		$this->insertDarwinianaIntoGroup($journalsByInstitution, 'Instituto de Botánica Darwinion y Museo Botánico de Córdoba');
		$this->templateManager->assign('journals_by_institution', $journalsByInstitution);
	}
	
	function insertDarwinianaIntoJournalsExtended(&$journals) {
		//$journals = $this->templateManager->get_template_vars('journals_extended');
		$darwinianaImage =  Request::getBaseUrl() . DIRECTORY_SEPARATOR . $this->getPluginPath() . '/images/darwiniana.jpg';
		$journals[] = new JournalMock(array("journalThumbnail" => $darwinianaImage, "imageUrl" =>  $darwinianaImage));
	}
	
	function insertDarwinianaIntoCategories(&$cache) {
		$darwinianaCategoryName = 'Área ciencias naturales, básicas y aplicadas';
		foreach($cache as &$cachedCategory) {
			//Add a new group when setting_value changes
			if ($cachedCategory != null && $cachedCategory['category']  != null && 
					$cachedCategory['category']->getLocalizedName() == $darwinianaCategoryName) {
				
				$cachedCategory['journals'][] = new JournalMock();
				uasort($cachedCategory['journals'], function ($a, $b) {
					return strcmp($a->getLocalizedTitle(), $b->getLocalizedTitle());
				});
			}
		}
	}
	
	function insertDarwinianaIntoGroup(&$cache, $group) {
		//Add a new group when setting_value changes
		if (!array_key_exists($group, $cache)) {
			$cache[$group] = array();
			ksort($cache);
		}
		$cache[$group][] = new JournalMock();
		uasort($cache[$group], function ($a, $b) {
			return strcmp($a->getLocalizedTitle(), $b->getLocalizedTitle());
		});
	}
	

	function addBootstrap(&$templateMgr) {
		$templateMgr->addStyleSheet(Request::getBaseUrl() . '/' . $this->getPluginPath() . '/css/bootstrap.min.css');
		$templateMgr->addJavaScript($this->getPluginPath().'/js/jquery-1.12.1.min.js');
		$templateMgr->addJavaScript($this->getPluginPath().'/js/bootstrap.js');
	}
	
	function hookTemplate($hookName, $templateName, $stopPropagation = false) {
		(new TemplateHookCallback($hookName, $templateName, $stopPropagation))->register();	
	}
}

class TemplateHookCallback
{
	
	function TemplateHookCallback($hookName, $templateName, $stopPropagation) {
		$this->templateName = $templateName;
		$this->hookName = $hookName;
		$this->stopPropagation = $stopPropagation;
	}
	
	function register() {
		HookRegistry::register(
				$this->hookName,
				array(&$this, 'hookTemplate'));
	}
	
	function hookTemplate($hookName, $args) {
		$params =& $args[0];
		$smarty =& $args[1];
		$output =& $args[2];
		$output = $smarty->fetch($this->templateName);
		return $this->stopPropagation;
	}
}

?>
