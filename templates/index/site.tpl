{**
 * templates/index/site.tpl
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
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

{if $intro}<div id="intro">{$intro|nl2br}</div>{/if}

<a name="journals"></a>

{if $useAlphalist}
	<p>{foreach from=$alphaList item=letter}<a href="{url searchInitial=$letter sort="title"}">{if $letter == $searchInitial}<strong>{$letter|escape}</strong>{else}{$letter|escape}{/if}</a> {/foreach}<a href="{url}">{if $searchInitial==''}<strong>{translate key="common.all"}</strong>{else}{translate key="common.all"}{/if}</a></p>
{/if}

	<div class="container-fluid">
		{assign var="itemsPerPage" value=6}
		{capture assign="journals_extended_count}{$journals_extended|@count}{/capture}	
		{assign var="pages"  value=$journals_extended_count/$itemsPerPage|ceil}
		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		  <!-- Indicators -->
		  <ol class="carousel-indicators">
			{section name=indicators loop=$pages}
				<li data-target="#carousel-example-generic" data-slide-to="{$smarty.section.indicators.index}" class="{if ($smarty.section.indicators.index==0)}active{/if}"></li>
			{/section}
		  </ol>
	  
		  <!-- Wrapper for slides -->
		  	<div class="journal-list-container carousel-inner">
		  		
				{foreach from=$journals_extended item=journal name=journals}
					{assign var="itKey" value=$smarty.foreach.journals.index}
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

					<div class="col-xs-6 col-md-4">
						<div class="panel panel-default journal-list-item">
							<div class="panel-body">
								
									{assign var="displayJournalThumbnail" value=$journal->getLocalizedSetting('journalThumbnail')}
									{if $displayJournalThumbnail && is_array($displayJournalThumbnail)}
										{assign var="thumbnail" value=$journalFilesPath|cat:$journal->getId()|cat:"/"|cat:$displayJournalThumbnail.uploadName}
									{elseif $journal->getLocalizedSetting('journalThumbnail')}
										{assign var="thumbnail" value=$journal->getLocalizedSetting('journalThumbnail')}
									{else}
										{assign var="thumbnail" value=null}
									{/if}
									
									{if $thumbnail}
									<div class="homepageImage">
										<a href="{$journal->getUrl()}">
											{assign var="altText" value=$journal->getLocalizedSetting('journalThumbnailAltText')}
											<img src="{$thumbnail}" {if $altText != ''}alt="{$altText|escape}"{else}alt="{translate key="common.pageHeaderLogo.altText"}"{/if} />										
										</a>
									</div>
									{/if}
								
								<div class="caption text-center">
									<a href="{$journal->getUrl()}">
										<h4>{$journal->getLocalizedTitle()|escape}</h4>
									</a>
									<p>{$journal->getSetting('publisherInstitution')}</p>
								</div>
							</div>
						</div>
					</div>
				{/foreach}
				{section name=fill-journals loop=$itemsPerPage-$journals_extended_count%$itemsPerPage}
					<div class="col-xs-6 col-md-4">
						<div class="panel panel-default journal-list-item">
							<div class="panel-body"></div>
						</div>
					</div>
				{/section}
				</div>
			</div>
		</div>
	</div>

	{if !journals_extended}
		{iterate from=journals item=journal}
			<div class="journal-list-item">
				{if $site->getSetting('showThumbnail')}
					{assign var="displayJournalThumbnail" value=$journal->getLocalizedSetting('journalThumbnail')}
					<div class="image-wrapper" style="clear:left;">
					{if $displayJournalThumbnail && is_array($displayJournalThumbnail)}
						{assign var="altText" value=$journal->getLocalizedSetting('journalThumbnailAltText')}
						<div class="homepageImage"><a href="{$journal->getUrl()}" class="action"><img src="{$journalFilesPath}{$journal->getId()}/{$displayJournalThumbnail.uploadName|escape:"url"}" {if $altText != ''}alt="{$altText|escape}"{else}alt="{translate key="common.pageHeaderLogo.altText"}"{/if} /></a></div>
					{/if}
					</div>
				{/if}
				{if $site->getSetting('showTitle')}
					<h3 class="title">{$journal->getLocalizedTitle()|escape}</h3>
				{/if}
				{if $site->getSetting('showDescription')}
					{if $journal->getLocalizedDescription()}
						<p>{$journal->getLocalizedDescription()|nl2br}</p>
					{/if}
				{/if}
				
				<p class="links"><a href="{$journal->getUrl()}" class="action action-view-journal">{translate key="site.journalView"}</a> | <a href="{$journal->getUrl() page="issue" op="current"}" class="action">{translate key="site.journalCurrent"}</a> | <a href="{$journal->getUrl() page="user" op="register"}" class="action">{translate key="site.journalRegister"}</a></p>
			</div>
		{/iterate}
	{/if}
</div>
{if $journals->wasEmpty()}
	{translate key="site.noJournals"}
{/if}



<div id="journalListPageInfo">{page_info iterator=$journals}</div>
<div id="journalListPageLinks">{page_links anchor="journals" name="journals" iterator=$journals}</div>

<div class="">
	<div class="">
		<ul class="nav nav-tabs nav-justified">
			<li class="tab active h5"><a data-toggle="tab" href="#journals_by_publisher">Revistas por editorial</a></li>
			<li class="h5"><a data-toggle="tab" href="#journals_by_category">Revistas por categoría</a></li>
			<li class="h5"><a data-toggle="tab" href="#journals_by_initial">Íncide alfabético</a></li>
		</ul>
		<div class="tab-content">
		<div id="journals_by_publisher" class="tab-pane fade in active">
			{foreach from=$journals_by_institution item=group key=institution}
				<div class="journal-group">
					<div class="h5">{$institution|default:"Sin categorizar"}</div>
					<div class="row">
					{foreach from=$group item=journal}
						<div class="col-md-6">
							<a href="{$journal->getUrl()}">{$journal->getLocalizedTitle()|escape}</a>
						</div>
					{/foreach}
					</div>
				</div>
			{/foreach}
		</div>
		<div id="journals_by_category" class="tab-pane fade">
			{foreach from=$journals_by_category item=categoryArray}
				<div class="journal-group">
					{assign var=category value=$categoryArray.category}
					{assign var=journalist value=$categoryArray.journal}
					<div class="h5">{$category->getLocalizedName()|escape}</div>
					<div class="row">
					{foreach from=$categoryArray.journals item=journal}
						<div class="col-md-6">
							<a href="{$journal->getUrl()}">{$journal->getLocalizedTitle()|escape}</a>
						</div>
					{/foreach}
					</div>
				</div>
			{/foreach}
		</div>
		<div id="journals_by_initial" class="tab-pane fade">
			{foreach from=$journals_by_initial item=group key=initial}
				<div class="journal-group">
					<div class="h5">{$initial|default:"Sin categorizar"}</div>
					<div class="row">
					{foreach from=$group item=journal}
						<div class="col-md-6">
							<a href="{$journal->getUrl()}">{$journal->getLocalizedTitle()|escape}</a>
						</div>
					{/foreach}
					</div>
				</div>
			{/foreach}
		</div>
	</div>
</div>

<div>

{if !journals_extended}
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
{/if}

{include file="common/footer.tpl"}

