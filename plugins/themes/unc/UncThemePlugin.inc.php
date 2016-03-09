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
		return 'unc.css';
	}

	function getLocaleFilename($locale) {
		return null; // No locale data
	}
	
	function activate(&$templateMgr) {
		$this->addBootstrap($templateMgr);
		$additionalHeadData = $templateMgr->get_template_vars('additionalHeadData');
		$additionalHeadData .= '<script type="text/javascript" src="' . Request::getBaseUrl() . '/plugins/themes/unc/js/slideshow.js"></script>' . "\n";
		$templateMgr->assign('additionalHeadData', $additionalHeadData);
		
		
		
		if (($stylesheetFilename = $this->getStylesheetFilename()) != null) {
			$path = Request::getBaseUrl() . '/' . $this->getPluginPath() . '/' . $stylesheetFilename;
			
			$templateMgr->addStyleSheet($path);
		}
	}
	
	function addBootstrap(&$templateMgr) {
		$templateMgr->addStyleSheet(Request::getBaseUrl() . '/' . $this->getPluginPath() . '/' . 'bootstrap.min.css');
		$additionalHeadData = $templateMgr->get_template_vars('additionalHeadData');
		$additionalHeadData .= '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>' . "\n";
		$additionalHeadData .= '<script type="text/javascript" src="' . Request::getBaseUrl() . '/plugins/themes/unc/js/bootstrap.js"></script>' . "\n";
		$templateMgr->assign('additionalHeadData', $additionalHeadData);
	}
}

?>
