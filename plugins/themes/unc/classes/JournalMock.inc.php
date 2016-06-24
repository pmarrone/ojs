<?php

/**
 * @defgroup journal
 */

/**
 * @file classes/journal/Journal.inc.php
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class Journal
 * @ingroup journal
 * @see JournalDAO
 *
 * @brief Describes basic journal properties.
 */

class JournalMock extends Journal {
	
	private $settings;

	
	/**
	 * Constructor.
	 */
	function JournalMock($settings = array()) {
		parent::DataObject();
		
		$this->settings = array(
				'url' => 'http://www.ojs.darwin.edu.ar/',
				'imageUrl' => 'http://www.ojs.darwin.edu.ar/public/journals/2/cover_issue_35_es_ES.jpg',
				'description' => 'Darwiniana, nueva serie, es una publicación científica botánica, semestral, 
							propiedad de las siguientes instituciones: Museo Botánico de Córdoba, Instituto de 
							Botánica Darwinion, Instituto Multidisciplinario de Biología Vegetal, Universidad Nacional de 
							Córdoba y Academia Nacional de Ciencias Exactas, Físicas y Naturales. Publica trabajos científicos 
							originales y revisiones sobre diferentes áreas de la Botánica con excepción de los artículos de índole 
							agronómico y estrictamente aplicados (de transferencia directa). Presenta versión en línea (ISSN 1850-1699) y 
							versión impresa (ISSN 0011-6793).',
				'title' => 'Revista Darwiniana, nueva serie',
				'primaryLocale' => 'es',
				'initials' => 'D',
				'journalThumbnail' => 'http://www.ojs.darwin.edu.ar/public/journals/2/cover_issue_35_es_ES.jpg'
		);
		$this->settings = array_merge($this->settings, $settings); 
	}

	/**
	 * Get the base URL to the journal.
	 * @return string
	 */
	function getUrl() {
		return $this->settings['url'];
	}

	/**
	 * Return the primary locale of this journal.
	 * @return string
	 */
	function getPrimaryLocale() {
		return $this->settings['primaryLocale'];
	}

	/**
	 * Set the primary locale of this journal.
	 * @param $locale string
	 */
	function setPrimaryLocale($primaryLocale) {
		return $this->setData('primaryLocale', $primaryLocale);
	}

	/**
	 * Return associative array of all locales supported by the journal.
	 * These locales are used to provide a language toggle on the journal-specific pages.
	 * @return array
	 */
	function &getSupportedLocaleNames() {
		return array('es');
	}

	/**
	 * Return associative array of all locales supported by forms of the journal.
	 * These locales are used to provide a language toggle on the journal-specific pages.
	 * @return array
	 */
	function &getSupportedFormLocaleNames() {
		return array('es');
	}

	/**
	 * Return associative array of all locales supported for the submissions.
	 * These locales are used to provide a language toggle on the submission setp1 and the galley edit page.
	 * @return array
	 */
	function &getSupportedSubmissionLocaleNames() {
		return array('es');
	}

	/**
	 * Get "localized" journal page title (if applicable).
	 * param $home boolean get homepage title
	 * @return string
	 */
	function getLocalizedPageHeaderTitle($home = false) {
		return $this->settings['title'];
	}

	function getJournalPageHeaderTitle($home = false) {
		return $this->settings['title'];
	}

	/**
	 * Get "localized" journal page logo (if applicable).
	 * param $home boolean get homepage logo
	 * @return string
	 */
	function getLocalizedPageHeaderLogo($home = false) {
		return $this->settings['imageUrl'];
	}

	function getJournalPageHeaderLogo($home = false) {
		return $this->settings['imageUrl'];
	}

	/**
	 * Get localized favicon
	 * @return string
	 */
	function getLocalizedFavicon() {
		return null;
	}

	//
	// Get/set methods
	//

	/**
	 * Get the localized title of the journal.
	 * @param $preferredLocale string
	 * @return string
	 */
	function getLocalizedTitle($preferredLocale = null) {
		return $this->settings['title'];
	}

	function getJournalTitle() {
		return $this->settings['title'];
	}

	/**
	 * Get title of journal
	 * @param $locale string
	 * @return string
	 */
	function getTitle($locale) {
		return $this->settings['title'];
	}

	/**
	 * Get localized initials of journal
	 * @return string
	 */
	function getLocalizedInitials() {
		return $this->settings['initials'];
	}

	function getJournalInitials() {
		if (Config::getVar('debug', 'deprecation_warnings')) trigger_error('Deprecated function.');
		return $this->getLocalizedInitials();
	}

	/**
	 * Get the initials of the journal.
	 * @param $locale string
	 * @return string
	 */
	function getInitials($locale) {
		return $this->getSetting('initials', $locale);
	}

	/**
	 * Get enabled flag of journal
	 * @return int
	 */
	function getEnabled() {
		return $this->getData('enabled');
	}

	/**
	 * Set enabled flag of journal
	 * @param $enabled int
	 */
	function setEnabled($enabled) {
		return $this->setData('enabled',$enabled);
	}

	/**
	 * Get ID of journal.
	 * @return int
	 */
	function getJournalId() {
		if (Config::getVar('debug', 'deprecation_warnings')) trigger_error('Deprecated function.');
		return $this->getId();
	}

	/**
	 * Set ID of journal.
	 * @param $journalId int
	 */
	function setJournalId($journalId) {
		if (Config::getVar('debug', 'deprecation_warnings')) trigger_error('Deprecated function.');
		return $this->setId($journalId);
	}

	/**
	 * Get the localized description of the journal.
	 * @return string
	 */
	function getLocalizedDescription() {
		return $this->getDescription(AppLocale::getLocale());
	}

	function getJournalDescription() {
		if (Config::getVar('debug', 'deprecation_warnings')) trigger_error('Deprecated function.');
		return $this->getLocalizedDescription();
	}
	/**
	 * Get description of journal.
	 * @param $locale string
	 * @return string
	 */
	function getDescription($locale) {
		return $this->settings['description'];
	}

	/**
	 * Get path to journal (in URL).
	 * @return string
	 */
	function getPath() {
		return $this->settings['url'];
	}

	/**
	 * Set path to journal (in URL).
	 * @param $path string
	 */
	function setPath($path) {
		return $this->setData('path', $path);
	}

	/**
	 * Get sequence of journal in site table of contents.
	 * @return float
	 */
	function getSequence() {
		return $this->getData('sequence');
	}

	/**
	 * Set sequence of journal in site table of contents.
	 * @param $sequence float
	 */
	function setSequence($sequence) {
		return $this->setData('sequence', $sequence);
	}

	/**
	 * Retrieve array of journal settings.
	 * @return array
	 */
	function &getSettings() {
		return $this->settings;
	}

	/**
	 * Retrieve a localized setting.
	 * @param $name string
	 * @param $preferredLocale string
	 * @return mixed
	 */
	function &getLocalizedSetting($name, $preferredLocale = null) {
		return $this->settings[$name];
	}

	/**
	 * Retrieve a journal setting value.
	 * @param $name string
	 * @param $locale string
	 * @return mixed
	 */
	function &getSetting($name, $locale = null) {
		return $this->settings[$name];
	}
}
?>
