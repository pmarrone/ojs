(function($){
	var isAnimating = false;
	var sto;
	$.fn.navegadorRevistas = function(opts){
		var defaults = {
			insertIn:".navegacion",
			grupos:0,
			metodos:$.fn.navegadorRevistas.metodos
		};
		var options = $.extend(defaults, opts);
		return this.each(function(){
			var obj = this;
			options.grupos = $('.contenedor-grupo-revistas').size();
			$('<div/>', {"class": 'navegacion-portadas'}).appendTo($(obj));
			if(options.grupos > 1){
				$('.navegacion-portadas').append('<ul/>')
				for(var i = 0; i < options.grupos; i++){
					$('<li>', 
						{
							"class": 'numero' + i,
							id: i,
							click: function(){
								clearTimeout(sto);
								if(isAnimating == false){
									$(obj).data("activo", {valor:$(this).attr('id')});
									options.metodos.navegar($(this).attr('id'));
								}
							},
							html: '<a href="#pagina-' + (i + 1) + '">' + (i + 1) + '</a>'
						}
					).appendTo('.navegacion-portadas ul').find('a').click(function(e){
						e.preventDefault();
					});
				}
			}
			$(obj).data("activo", {valor: 0});
			$(obj).data("grupos", {valor: options.grupos});
			$.fn.navegadorRevistas.metodos.activarEnlaceNavegacion(0);
			/* options.metodos.auto(); */
		});
	}
	$.fn.navegadorRevistas.metodos = {
			navegar: function(id){
				isAnimating = true;
				var position = 724 * $('.navegacion-portadas ul li:eq(' + id + ')').attr('id');
				$.fn.navegadorRevistas.metodos.activarEnlaceNavegacion(id);
				$('.contenedor-revistas').animate({
					left: [-position + "px"]
				}, {
					duration: 300,
					specialEasing: {
						left: 'linear'
					},
					complete: function(){
						isAnimating = false;
						var activo = $('.revistas-publicadas-principal').data("activo").valor;
						var activoInc = (activo + 1) % $('.revistas-publicadas-principal').data("grupos").valor;
						$('.revistas-publicadas-principal').data("activo", {valor:activoInc});
						/* $.fn.navegadorRevistas.metodos.auto(); */
					}
				});
			},
			activarEnlaceNavegacion: function(id){
				$('.navegacion-portadas ul li:eq(' + id + ')').addClass('activo').siblings().removeClass('activo');
			},
			auto: function(){
				sto = setTimeout(
					function(){
						$.fn.navegadorRevistas.metodos.navegar($('.revistas-publicadas-principal').data("activo").valor)
					}, 8000
				);
			}
		}
})(jQuery);

var $jq1 = jQuery.noConflict(true);
(function slideshow($) {
	$.fn.slideshow = function () {
		
		console.log("HEY!");
		console.log(this);
		console.log(this.size());
		this.each(function () {
			console.log(this);
			var element = $(this);
			var imageWrapper = element.find('.image-wrapper');
			var title = element.find('.title');
			var actionViewJournal = element.find('.action-view-journal');
			
			var thumbnail = document.createElement('div');
			thumbnail.className = "thumbnail";
			
			
			console.log(arguments);
			console.log(imageWrapper, title. actionViewJournal);
			
		});
	};
	
	$(document).ready(function () {
		$('.journal-list-item').slideshow();
	})
})($jq1);
