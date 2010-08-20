var BASE = 'http://beta.alkalineapp.com/';

var ADMIN = 'admin/';
var IMAGES = 'images/';
var PHOTOS = 'photos/';

var task;
var progress;
var progress_step;

// SHOEBOX

function now(){
	var time = new Date();
	var hour = time.getHours();
	var minute = time.getMinutes();
	var second = time.getSeconds();
	var temp = "" + ((hour > 12) ? hour - 12 : hour);
	if(hour == 0){
		temp = "12";
	}
	temp += ((minute < 10) ? ":0" : ":") + minute;
	temp += ((second < 10) ? ":0" : ":") + second;
	temp += (hour >= 12) ? " P.M." : " A.M.";
	return temp;
}

var now = now();

function empty (mixed_var) {
    var key;
    
    if (mixed_var === "" ||
        mixed_var === 0 ||
        mixed_var === "0" ||
        mixed_var === null ||
        mixed_var === false ||
        typeof mixed_var === 'undefined'
    ){
        return true;
    }
 
    if (typeof mixed_var == 'object') {
        for (key in mixed_var) {
            return false;
        }
        return true;
    }
 
    return false;
}

function photoArray(input){
	if(empty(input)){
		updateProgress(100); return;
	}
	photo_count = input.length;
	progress = 0;
	progress_step = 100 / input.length;
	if(task == 'add-photos'){
		for(item in input){
			$.post(BASE + ADMIN + "tasks/" + task + ".php", { photo_file: input[item] }, function(data){ appendPhoto(data); updateProgress(); } );
		}
	}
	else if(task == 'rebuild-all'){
		for(item in input){
			$.post(BASE + ADMIN + "tasks/" + task + ".php", { photo_id: input[item] }, function(data){ updateMaintProgress(); } );
		}
	}
}

function updateMaintProgress(){
	progress += progress_step;
	progress_int = parseInt(progress);
	$("#progress").progressbar({ value: progress_int });
	if(progress > 99.9999999){
		$.post(BASE + ADMIN + "tasks/add-notification.php", { message: "Your photo library&#8217;s thumbnails have been rebuilt.", type: "success" }, function(data){ window.location = BASE + ADMIN + 'maintenance/'; } );
	}
}
function appendPhoto(photo){
	var photo = $.evalJSON(photo);
	photo_ids = $("#shoebox_photo_ids").val();
	photo_ids += photo.id + ',';
	$("#shoebox_photo_ids").attr("value", photo_ids);
	$("#shoebox_photos").append('<div id="photo-' + photo.id + '" class="id"><hr /><div class="span-3 center"><img src="' + BASE + PHOTOS + photo.id + '_sq.' + photo.ext + '" alt="" class="admin_thumb" /></div><div class="span-14 last"><p class="title"><input type="text" name="photo-' + photo.id + '-title" /></p><p class="description"><textarea name="photo-' + photo.id + '-description"></textarea></p><p class="tags"><img src="' + BASE + IMAGES + 'icons/tag.png" alt="" title="Tags" /><input type="text" id="photo-' + photo.id + '-tags" /></p><p class="publish"><img src="' + BASE + IMAGES + 'icons/publish.png" alt="" title="Publish date" /><input type="text" id="photo-' + photo.id + '-published" value="' + now + '" /></p><p class="geo"><img src="' + BASE + IMAGES + 'icons/geo.png" alt="" title="Geolocation" /><input type="text" id="photo-' + photo.id + '-geo" /></p><p class="delete"><a href="#" class="delete"><img src="' + BASE + IMAGES + 'icons/delete.png" alt="" title="Delete photo" /></a></p></div></div>');
}

function updateProgress(val){
	progress += progress_step;
	if(!empty(val)){ progress = val; }
	progress_int = parseInt(progress);
	$("#progress").progressbar({ value: progress_int });
	if(progress == 100){
		$("#progress").slideUp(1000);
		$("#shoebox_add").delay(1000).removeAttr("disabled");
	}
}

function checkCount(){
	if(count == 0){
		$('#shoebox_add').attr('disabled', 'disabled');
	}
}

function executeTask(){
	$.ajax({
		url: BASE + ADMIN + "tasks/" + task + ".php",
		cache: false,
		error: function(data){ alert(data); },
		dataType: "json",
		success: function(data){ photoArray(data); }
	});
}

$(document).ready(function(){
	// PRIMARY
	var page = $("h1").first().text();
	var page_re = /^(\w+).*/;
	page = page.replace(page_re, "$1");
	
	var tags = $("#photo_tags").text();
	if(empty(tags)){
		tags = new Array();
	}
	else{
		tags = $.evalJSON(tags);
	}
	updateTags();
	
	// PHOTO
	$("#photo_tag_add").click(function(){
		var tag = $("#photo_tag").val();
		tag = jQuery.trim(tag);
		if(tags.indexOf(tag) == -1){
			tags.push(tag);
			updateTags();
		}
		$("#photo_tag").val('');
		event.preventDefault();
	});
	
	$("#photo_tags a.tag").click(function(){
		var tag = $(this).contents().text();
		tag = jQuery.trim(tag);
		var index = tags.lastIndexOf(tag);
		if(index > -1){
			tags.splice(index, 1);
			updateTags();
		}
		$(this).fadeOut();
		event.preventDefault();
	});
	
	function updateTags(){
		var tags_html = tags.map(function(item) { return '<img src="' + BASE + IMAGES + '/icons/tag.png" alt="" /> <a href="#delete_tag" class="tag">' + item + '</a>'; });
		$("#photo_tags_input").val($.toJSON(tags));
		$("#photo_tags").html(tags_html.join(', '));
	}
	
	// PRIMARY - SHOW/HIDE PANELS
	$(".reveal").hide();
	var original = $("a.show").text();
	var re = /Show(.*)/;
	var modified = 'Hide' + original.replace(re, "$1");
	
	$("a.show").toggle(
		function(){
			$(this).siblings(".switch").html('&#9662;');
			$(this).text(modified);
			$(this).parent().siblings(".reveal").slideDown();
			event.preventDefault();
		},
		function(){
			$(this).siblings(".switch").html('&#9656;');
			$(this).text(original);
			$(this).parent().siblings(".reveal").slideUp();
			event.preventDefault();
		}
	);
	
	// PRIMARY - DATEPICKER
	
	$(".date").datepicker({
		showOn: 'button',
		buttonImage: BASE + IMAGES + '/icons/calendar.png',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		constrainInput: false,
		showAnim: null
	});
	
	// PRIMARY - NAVIGATION
	
	$("#primary ul#navigation li").hover(
		function(){
			$(this).siblings().each(function(index){
				bool = $(this).find("img").hasClass("hide");
				if(bool === true){
					$(this).find("img").hide();
				}
			});
			$(this).find("img").fadeIn(600);
			attr = $(this).attr("class");
			if(attr == "logout"){
				$(this).find("a").animate({"color": "#f02415"}, 600);
			}
		}, 
		function(){
			attr = $(this).find("img").attr("class");
			if(attr == 'hide'){
				$(this).find("img").hide();
			}
			attr = $(this).attr("class");
			if(attr == "logout"){
				$(this).find("a").stop();
				$(this).find("a").css("color", "#999");
			}
		}
	);
	
	// UPLOAD
	
	if(page == 'Upload'){
		var upload_count = 0;
		var upload_count_text;
		$("#progress").hide(0);
		$("#upload").html5_upload({
			url: BASE + ADMIN + 'upload/',
			sendBoundary: window.FormData || $.browser.mozilla,
			onStart: function(event, total) {
				if(total == 1){
					return confirm("You are about to upload 1 file. Are you sure?");
				}
				else{
					return confirm("You are about to upload " + total + " files. Are you sure?");
				}
			},
			setName: function(text) {
				$("#progress").slideDown(500);
			},
			setProgress: function(val) {
				$("#progress").progressbar({ value: Math.ceil(val*100) });
			},
			onFinishOne: function(event, response, name, number, total) {
				upload_count = upload_count + 1;
				if(upload_count == 1){
					upload_count_text = upload_count + ' file';
				}
				else{
					upload_count_text = upload_count + ' files';
				}
				$("#upload_count_text").text(upload_count_text);
				if(number == (total - 1)){
					$("#progress").slideUp(500);
				}
			}
		});
	}
	
	// MAINTENACE
	
	if(page == 'Maintenance'){
		$("#progress").hide(0);
		$("#tasks a").click(function(){
			task = $(this).attr("href").slice(1);
			executeTask();
			$("#tasks").slideUp(500);
			$("#progress").delay(500).slideDown(500);
			$("#progress").progressbar({ value: 0 });
			$.ajax({
				url: BASE + ADMIN + "tasks/" + task + ".php",
				cache: false,
				error: function(data){ alert(data); },
				dataType: "json",
				success: function(data){ photoArray(data); }
			});
			event.preventDefault();
		});
	}
	
	// SHOEBOX
	
	if(page == 'Shoebox'){
		task = 'add-photos';
		executeTask();
		
		$("#shoebox_add").attr("disabled", "disabled");
		$("#progress").progressbar({ value: 0 });
	}
	
	// PAGE (EDIT)
	
	if(page == 'Page'){
		var markup_row = $("#tr_page_markup");
		var markup = markup_row.find("select").attr("title");
		
		if(markup){
			markup_row.find("input").attr("checked", "checked");
			markup_row.find("option[value='" + markup + "']").attr("selected", "selected");
		}
	}
	
	// DASHBOARD
	if(page == 'Dashboard'){
		var statistics_views = $("#statistics_views").attr("title");
		statistics_views = $.evalJSON(statistics_views);
	
		var statistics_visitors = $("#statistics_visitors").attr("title");
		statistics_visitors = $.evalJSON(statistics_visitors);
	
		var stats = $.plot($("#statistics_holder"),[{
			label: "Page views",
			data: statistics_views,
			bars: { show: true, lineWidth: 15 },
			shadowSize: 10,
			hoverable: true,
			yaxis: 1
		},
		{
			label: "Unique visitors",
			data: statistics_visitors,
			bars: { show: true, lineWidth: 15 },
			shadowSize: 10,
			hoverable: true,
			yaxis: 1
		}],{
			legend: { show: true, backgroundOpacity: 0, labelBoxBorderColor: "#ddd", position: "nw", margin: 10 },
			colors: ["#0096db", "#8dc9e8"],
			xaxis: { mode: "time", tickLength: 0, autoscaleMargin: 0 },
			yaxis: { tickDecimals: 0 },
			grid: { color: "#777", borderColor: "#ccc", tickColor: "#eee", labelMargin: 10, hoverable: true, autoHighlight: true }
		});
	
		$.each(stats.getData()[0].data, function(i, el){
			var o = stats.pointOffset({x: el[0], y: el[1]});
			if(el[1] > 0){
			  $('<div class="point">' + el[1] + '</div>').css( {
			    position: 'absolute',
			    left: o.left - 12,
			    top: o.top - 20,
			  }).appendTo(stats.getPlaceholder());
			}
		});
	
		var time = new Date();
		var month = time.getMonth();
	
		if(month == 0){ month = 'Jan'; }
		if(month == 1){ month = 'Feb'; }
		if(month == 2){ month = 'Mar'; }
		if(month == 3){ month = 'Apr'; }
		if(month == 4){ month = 'May'; }
		if(month == 5){ month = 'Jun'; }
		if(month == 6){ month = 'Jul'; }
		if(month == 7){ month = 'Aug'; }
		if(month == 8){ month = 'Sep'; }
		if(month == 9){ month = 'Oct'; }
		if(month == 10){ month = 'Nov'; }
		if(month == 11){ month = 'Dec'; }

		var day = time.getDate();
	
		$(".tickLabel").each(function(index){
			var text = $(this).text();
			if(text == (month + ' ' + day)){
				$(this).text('Today').css('color', '#000');
			}
		});
	
		$(".tickLabels").css('font-size', '');
	}
	
	// STATISTICS
	
	if(page == 'Statistics'){
		var h_statistics_views = $("#h_views").attr("title");
		h_statistics_views = $.evalJSON(h_statistics_views);
	
		var h_statistics_visitors = $("#h_visitors").attr("title");
		h_statistics_visitors = $.evalJSON(h_statistics_visitors);
	
		var h_stats = $.plot($("#h_holder"),[{
			label: "Page views",
			data: h_statistics_views,
			bars: { show: true, lineWidth: 16 },
			shadowSize: 10,
			hoverable: true,
			yaxis: 1
		},
		{
			label: "Unique visitors",
			data: h_statistics_visitors,
			bars: { show: true, lineWidth: 16 },
			shadowSize: 10,
			hoverable: true,
			yaxis: 1
		}],{
			legend: { show: true, backgroundOpacity: 0, labelBoxBorderColor: "#ddd", position: "nw", margin: 10 },
			colors: ["#0096db", "#8dc9e8"],
			xaxis: { mode: "time", tickLength: 0, autoscaleMargin: 0, timeformat: "%h %p" },
			yaxis: { tickDecimals: 0 },
			grid: { color: "#777", borderColor: "#ccc", tickColor: "#eee", labelMargin: 10, hoverable: true, autoHighlight: true }
		});
		
		
	
		var d_statistics_views = $("#d_views").attr("title");
		d_statistics_views = $.evalJSON(d_statistics_views);
	
		var d_statistics_visitors = $("#d_visitors").attr("title");
		d_statistics_visitors = $.evalJSON(d_statistics_visitors);
	
		var d_stats = $.plot($("#d_holder"),[{
			label: "Page views",
			data: d_statistics_views,
			bars: { show: true, lineWidth: 15 },
			shadowSize: 10,
			hoverable: true,
			yaxis: 1
		},
		{
			label: "Unique visitors",
			data: d_statistics_visitors,
			bars: { show: true, lineWidth: 15 },
			shadowSize: 10,
			hoverable: true,
			yaxis: 1
		}],{
			legend: { show: true, backgroundOpacity: 0, labelBoxBorderColor: "#ddd", position: "nw", margin: 10 },
			colors: ["#0096db", "#8dc9e8"],
			xaxis: { mode: "time", tickLength: 0, autoscaleMargin: 0, minTickSize: [3, "day"] },
			yaxis: { tickDecimals: 0 },
			grid: { color: "#777", borderColor: "#ccc", tickColor: "#eee", labelMargin: 10, hoverable: true, autoHighlight: true }
		});
		
		var m_statistics_views = $("#m_views").attr("title");
		m_statistics_views = $.evalJSON(m_statistics_views);
	
		var m_statistics_visitors = $("#m_visitors").attr("title");
		m_statistics_visitors = $.evalJSON(m_statistics_visitors);
	
		var m_stats = $.plot($("#m_holder"),[{
			label: "Page views",
			data: m_statistics_views,
			bars: { show: true, lineWidth: 30 },
			shadowSize: 10,
			hoverable: true,
			yaxis: 1
		},
		{
			label: "Unique visitors",
			data: m_statistics_visitors,
			bars: { show: true, lineWidth: 30 },
			shadowSize: 10,
			hoverable: true,
			yaxis: 1
		}],{
			legend: { show: true, backgroundOpacity: 0, labelBoxBorderColor: "#ddd", position: "nw", margin: 10 },
			colors: ["#0096db", "#8dc9e8"],
			xaxis: { mode: "time", tickLength: 0, autoscaleMargin: 0 },
			yaxis: { tickDecimals: 0 },
			grid: { color: "#777", borderColor: "#ccc", tickColor: "#eee", labelMargin: 10, hoverable: true, autoHighlight: true }
		});
		
		$.each(h_stats.getData()[0].data, function(i, el){
			var o = h_stats.pointOffset({x: el[0], y: el[1]});
			if(el[1] > 0){
			  $('<div class="point">' + el[1] + '</div>').css( {
			    position: 'absolute',
			    left: o.left - 12,
			    top: o.top - 20,
			  }).appendTo(h_stats.getPlaceholder());
			}
		});
		
		$.each(d_stats.getData()[0].data, function(i, el){
			var o = d_stats.pointOffset({x: el[0], y: el[1]});
			if(el[1] > 0){
			  $('<div class="point">' + el[1] + '</div>').css( {
			    position: 'absolute',
			    left: o.left - 12,
			    top: o.top - 20,
			  }).appendTo(d_stats.getPlaceholder());
			}
		});
		
		$.each(m_stats.getData()[0].data, function(i, el){
			var o = m_stats.pointOffset({x: el[0], y: el[1]});
			if(el[1] > 0){
			  $('<div class="point">' + el[1] + '</div>').css( {
			    position: 'absolute',
			    left: o.left - 12,
			    top: o.top - 20,
			  }).appendTo(m_stats.getPlaceholder());
			}
		});
		
		$(".tickLabel").each(function(index){
			var text = $(this).text();
			if(text == ('12 am')){
				$(this).text('Midnight');
			}
			else if(text == ('12 pm')){
				$(this).text('Noon');
			}
		});
		
		var time = new Date();
		var month = time.getMonth();
	
		if(month == 0){ month = 'Jan'; }
		if(month == 1){ month = 'Feb'; }
		if(month == 2){ month = 'Mar'; }
		if(month == 3){ month = 'Apr'; }
		if(month == 4){ month = 'May'; }
		if(month == 5){ month = 'Jun'; }
		if(month == 6){ month = 'Jul'; }
		if(month == 7){ month = 'Aug'; }
		if(month == 8){ month = 'Sep'; }
		if(month == 9){ month = 'Oct'; }
		if(month == 10){ month = 'Nov'; }
		if(month == 11){ month = 'Dec'; }

		var day = time.getDate();
	
		$(".tickLabel").each(function(index){
			var text = $(this).text();
			if(text == (month + ' ' + day)){
				$(this).text('Today').css('color', '#000');
			}
		});
	
		$(".tickLabels").css('font-size', '');
		
	}
	
});