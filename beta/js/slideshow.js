var height;
var width;
var margin;
var padding;
var photo;
var photo_first;
var photo_last;

function nextPhoto(){
	photo.removeClass('selected');
	photo = photo.next();
	if(photo.length == 0){
		photo = photo_first;
	}
	photo.addClass('selected');
	imgDim();
	event.preventDefault();
}

function prevPhoto(){
	photo.removeClass('selected');
	photo = photo.prev();
	if(photo.length == 0){
		photo = photo_last;
	}
	photo.addClass('selected');
	imgDim();
	event.preventDefault();
}

function imgDim(){
	height = photo.height();
	width = photo.width();
	margin = (670 / 2) - (height / 2);
	photo.children('img').css('margin-top', margin + 'px');
	padding = '305px';
	$('#controls #next').css('padding-top', padding);
	$('#controls #prev').css('padding-top', padding);
}

$(document).ready(function(){
	photo_first = $('ul#slides li').first();
	photo_last = $('ul#slides li').last();
	photo = photo_first;
	
	photo.addClass('selected');
	
	$('#controls a').fadeTo('slow', 0);
	
	$('#controls a').hover(
		function(){
			$(this).fadeTo('slow', 1);
		},
		function(){
			$(this).fadeTo('slow', 0);
		}
	);
	
	$('a#next').click(function(){
		nextPhoto();
	});
	
	$('a#prev').click(function(){
		prevPhoto();
	});
	
	$(document).keydown(function(event){
		if(event.keyCode == '37'){
			nextPhoto();
		}
		if(event.keyCode == '38'){
			photoSelect(0);
			event.preventDefault();
		}
		if(event.keyCode == '39'){
			prevPhoto();
		}
		if(event.keyCode == '40'){
			photoSelect(0);
			event.preventDefault();
		}
	});
});

$(window).load(function(){
	imgDim();
});