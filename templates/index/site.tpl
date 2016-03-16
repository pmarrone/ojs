{**
 * templates/index/site.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Site index.
 *
 *}
{strip}
{if $siteTitle}
	{assign var="pageTitleTranslated" value=$siteTitle}
{/if}
{include file="common/header.tpl"}
{/strip}

<br />

{if $intro}<div id="intro">{$intro|nl2br}</div>{/if}

<a name="journals"></a>

{if $useAlphalist}
	<p>{foreach from=$alphaList item=letter}<a href="{url searchInitial=$letter sort="title"}">{if $letter == $searchInitial}<strong>{$letter|escape}</strong>{else}{$letter|escape}{/if}</a> {/foreach}<a href="{url}">{if $searchInitial==''}<strong>{translate key="common.all"}</strong>{else}{translate key="common.all"}{/if}</a></p>
{/if}

	<div class="container-fluid">
		
		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		  <!-- Indicators -->
		  <ol class="carousel-indicators">
		    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
		    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
		    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
		    		    <li data-target="#carousel-example-generic" data-slide-to="4"></li>
		    <li data-target="#carousel-example-generic" data-slide-to="4"></li>
		    		    <li data-target="#carousel-example-generic" data-slide-to="5"></li>
		    <li data-target="#carousel-example-generic" data-slide-to="6"></li>
		    		    <li data-target="#carousel-example-generic" data-slide-to="7"></li>
		    <li data-target="#carousel-example-generic" data-slide-to="8"></li>
		    		    <li data-target="#carousel-example-generic" data-slide-to="9"></li>
		  </ol>
		
		  <!-- Wrapper for slides -->
		  
		  	<div class="journal-list-container carousel-inner">
				{assign var="itemsPerPage" value=8}
				{iterate from=journals item=journal index=itKey}
					{if ($itKey) is div by $itemsPerPage} 				
						{if ($itKey != 0)}
							<!-- Close last group -->
								</div>
							</div>
						{/if}
						<!-- Start a new journal list group -->
						<div class="item {if ($itKey == 0)} active {/if}">
							<div class="row journal-list-group">
					{/if}
					<div class="col-xs-3">
						<div class="panel panel-default journal-list-item">
							<div class="panel-body">
								<a href="{url journal=$journal->getPath()}">
									<div class="homepageImage">
										{assign var="displayJournalThumbnail" value=$journal->getLocalizedSetting('journalThumbnail')}
										<img src="{$journalFilesPath}{$journal->getId()}/{$displayJournalThumbnail.uploadName|escape:"url"}" {if $altText != ''}alt="{$altText|escape}"{else}alt="{translate key="common.pageHeaderLogo.altText"}"{/if} class="img-responsive"/>
									</div>
								</a>
								<div class="caption text-center">
									<a href="{url journal=$journal->getPath()}">
										<h4>{$journal->getLocalizedTitle()|escape}</h4>
									</a>
									<p>{$journal->getSetting('publisherInstitution')}</p>
								</div>
							</div>
						</div>
					</div>
				{/iterate}
				</div>
			</div>
		</div>
	</div>


	{iterate from=journals item=journal}
		<div class="journal-list-item">
			{if $site->getSetting('showThumbnail')}
				{assign var="displayJournalThumbnail" value=$journal->getLocalizedSetting('journalThumbnail')}
				<div class="image-wrapper" style="clear:left;">
				{if $displayJournalThumbnail && is_array($displayJournalThumbnail)}
					{assign var="altText" value=$journal->getLocalizedSetting('journalThumbnailAltText')}
					<div class="homepageImage"><a href="{url journal=$journal->getPath()}" class="action"><img src="{$journalFilesPath}{$journal->getId()}/{$displayJournalThumbnail.uploadName|escape:"url"}" {if $altText != ''}alt="{$altText|escape}"{else}alt="{translate key="common.pageHeaderLogo.altText"}"{/if} /></a></div>
				{/if}
				</div>
			{/if}
			{if $site->getSetting('showTitle')}
				<h3 class="title">{$journal->getLocalizedTitle()|escape}</h3>
			{/if}
			{if $site->getSetting('showDescription')}
				{if $journal->getLocalizedDescription()}
			<div class="journalDescription" id="journalDescription-{$journal->getId()|escape}">
				{$journal->getLocalizedDescription()|nl2br}
			</div>
				{/if}
			{/if}
			
			<p class="links"><a href="{url journal=$journal->getPath()}" class="action action-view-journal">{translate key="site.journalView"}</a> | <a href="{url journal=$journal->getPath() page="issue" op="current"}" class="action">{translate key="site.journalCurrent"}</a> | <a href="{url journal=$journal->getPath() page="user" op="register"}" class="action">{translate key="site.journalRegister"}</a></p>
		</div>
	{/iterate}
</div>
{if $journals->wasEmpty()}
	{translate key="site.noJournals"}
{/if}



<div id="journalListPageInfo">{page_info iterator=$journals}</div>
<div id="journalListPageLinks">{page_links anchor="journals" name="journals" iterator=$journals}</div>
<div>
<!-- agregado -->
<div style="clear:left;">
<div class="homepageImage">
<a class="action" href="http://www.ojs.darwin.edu.ar">
<img alt="Logotipo de la cabecera de la página" src="http://www.ojs.darwin.edu.ar/public/journals/2/cover_issue_35_es_ES.jpg" width="100" height="141">
</a>
</div>
</div>
<h3>Revista Darwiniana, nueva serie</h3>
<p>Darwiniana, nueva serie, es una publicación científica botánica, semestral, propiedad de las siguientes instituciones: Museo Botánico de Córdoba, Instituto de Botánica Darwinion, Instituto Multidisciplinario de Biología Vegetal, Universidad Nacional de Córdoba y Academia Nacional de Ciencias Exactas, Físicas y Naturales. Publica trabajos científicos originales y revisiones sobre diferentes áreas de la Botánica con excepción de los artículos de índole agronómico y estrictamente aplicados (de transferencia directa). Presenta versión en línea (ISSN 1850-1699) y versión impresa (ISSN 0011-6793).</p>
<p>
<a class="action" href="http://www.ojs.darwin.edu.ar/">Ver revista</a>
|
<a class="action" href="http://www.ojs.darwin.edu.ar/index.php/darwiniana/issue/current">Número actual</a>
|
<a class="action" href="http://www.ojs.darwin.edu.ar/index.php/darwiniana/user/register">Registrarse</a>
</p>
</div>
{foreach from=$journals_by_institution item=group key=institution}
	<div class="h5">{$institution|default:"Sin categorizar"}</div>
	<div class="row">
	{foreach from=$group item=journal}
		<div class="col-md-6">
			<a href="{url journal=$journal->getPath()}">{$journal->getLocalizedTitle()|escape}</a>
		</div>
	{/foreach}
	</div>
{/foreach}
{include file="common/footer.tpl"}

