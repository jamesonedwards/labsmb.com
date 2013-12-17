// This script controls the animation and AJAX calls for LABSmb.com
// Be sure to reference the latest version of JQuery
// This was written by a designer with rudamentary experience
// So be ready for some hacked code and long processes
// � 2013 LABSmb

            
	$.support.cors = true; // force cross-site scripting (as of jQuery 1.5)

    var vcenter = $(window).height();
    var hcenter = $(window).width();
    var heroWidth = $('#main_left_col').width();
    var heroHeight = (heroWidth * .30);
    //var upArrowL = $('#main_right_col').position();

$(document).ready(function() {console.log('documentReady');

                  
// As far as I know this is the best way to trigger specific functions
// for each page while including just one .js file
// First, be sure to set searchTriggered to 'true' if the Search page loads
// otherwise search will AJAX load itself into the DOM
// Most window specific variables and .length checks will be done here

	var vcenter = $(window).height();
    var hcenter = $(window).width();
    var heroWidth = $('#main_left_col').width();
    var heroHeight = (heroWidth * .30);
    var upArrowL = $('#main_right_col').position();
    


    $('#navigation').css({left:((hcenter / 2) - 73)})
    $('#logo_small').css({left:((hcenter / 2) - 110)})
    $('#logo_smaller').css({left:((hcenter / 2) - 73)})
    $('#labs_map').css({height:(vcenter / 2)})
    $('#map-canvas').css({height:(vcenter / 2)})
    $('#map-canvas').css({width: hcenter})
    
    if (('#about_responsive_wrap').length) {
    	upArrowL = $('#main_right_col').position();
    	$('.team_member_hero').css({height:heroHeight})
    		if (upArrowL == null) {
	    		return;
    		}
    		else {
    			$('#scroll_top').css({left:((upArrowL.left) + (hcenter * .05) + 26 )})
    		}
    }
    else {
	    return false;
    }
    

}); // END Document Ready

$(window).load(function() {console.log('windowLoaded');

// Here is where we will trigger all animations that fire after the page loads

	// IE 8 round features
	
	if( $('html').hasClass('ie8') ) { 
		console.log("IE 8 loded");
		$('.nav_item').corner('21px');
		$('.nav_item_selected').corner('21px');
		$('#scroll_top').corner('100px');
		$('.footer_social_button').corner('13px');
		
		if (hcenter <= 1230 && hcenter >= 1161) {
			$('#project_close').corner('100px');
		}
		else {
			if (hcenter <= 1160 || hcenter >= 720) {
				$('#project_close').corner('90px');
			}
		}
		

	};
	
	    if (hcenter <= 1450) {
		$('#scroll_top').css({display: 'none'})
		$('.contact_info').css( {position: 'relative' , top:0})
    }


	// 'X' Close button in Projects will take user back
	
	$('#project_close').click(function() {
		history.go(-1);
		console.log('back');
	});

	// Initialize map for About page

    function initializeMap() {
		var map_canvas = document.getElementById('map-canvas');
	    var map_options = {
			center: new google.maps.LatLng(40.751335, -74.006684),
		    zoom: 14,
		    mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		
		var map = new google.maps.Map(map_canvas, map_options)
		var marker = new google.maps.Marker({    
			position: new google.maps.LatLng(40.751337, -74.006685),    
			map: map,
			icon: "../images/labs-map-pin.png"
      	});
    }
    
    if ($('#about_responsive_wrap').length) {
    	initializeMap();
    }
    
      
	// Search AJAX Function
	
	timer = 0;
	function runSearch() {
	   	$('#results_wrap').empty();
	   	var searchQuery = $('input#field').val();
	   	searchQuery = searchQuery.replace(/\s/g,"%20");
	   	$.ajax({
		    type: "GET" ,
		    dataType: "json",
		    url: "/search/do_search/" + searchQuery ,
		    error: function(jqXHR,textStatus,errorThrown) {
			    alert(errorThrown);
			} ,
			success: function(data) {
				$('#results_wrap').html("<div id='search_content'><div id='search_results'><div id='search_results_number'><div id='search_results_number_print_center'></div></div><div id='results_go_here'></div>");
				$.each(data,function(object){ //first loop of do_search
					$('#search_results_number_print_center').html(data[object].count + " matching projects");
					if (data[object].count == 0) {
						$('#search_content').css({height: vcenter - 520});
						var zeroResults = true;
					}
					$.each(data[object],function(values){ //looping inside do_search
						$.each(data[object][values],function(entries){ //looping inside projects
							var searchResultID = data[object][values][entries].url_key
							searchResultID = searchResultID.replace(/\s/g, '');
							$("#results_go_here").append("<div id='" + searchResultID + "'" + " class='search_module'>");
							$("#" + searchResultID).append("<div class='search_module_image' >" + "<a style='text-decoration: none;' href='" + data[object][values][entries].url + "'><img src='" + data[object][values][entries].small_image_url + "' width='100%' border='0'></div>" + "<div class='search_module_title' >" + data[object][values][entries].name + "</div></a>" + "<div class='search_module_date' >" + data[object][values][entries].created + "</div>" + "<div class='search_module_desc' >" + data[object][values][entries].intro + "</div>" + "<a style='text-decoration: none;' href='" + data[object][values][entries].url + "'><div class='search_module_link' >" + "VIEW PROJECT>" + "</div></a>");
						});
						$("#search_results").append("</div></div>");
					});
				})
				var hcenter = $(window).width();
				$('#logo_smaller').css({left:((hcenter / 2) - 73)})
				$('#navigation').css({left:((hcenter / 2) - 73)})
			}
		});
			
	}
	
	// Load ALL Projects into Search window on page load
		
	if ($('#field').length) {
		$('#field').focus()
		//var searchQuery = $('input#field').val();
		runSearch();
	}
	
    
    $('#field').bind('keyup' , function(keyPressed) {
	    var fieldPressed = keyPressed.keyCode
	    
	    if (timer) {
        	clearTimeout(timer);
        }
	   
        $("#search_form").on('submit', function(test) { // Search if user hits submit
	        test.preventDefault();
	        runSearch();
	        history.pushState(null, 'Search', 'search'); // html5 prevent the URL from appending "?"
	        $("search_form").blur()
	    });	   
	   timer = setTimeout(runSearch, 400); // Search after user pauses typing
	});
	

	// If the user is on the Search page, turn OFF the AJAX call to load the
	// Search page when the user starts typing

	if ( $("#search").length ) {
    	var searchTriggered = true;
    	console.log('searchloaded');
    	}
    else {
    	window.searchTriggered = false;
    	console.log('searchNOTloaded');
    }
    
    
    // Search page structure
    
    if ( $("#home_hero").length ) {
    	$("#main_content").css({top: (vcenter - 178)})
    	$("#home_hero").css({height: (vcenter - 178)})
    	$('#logo_small').css({bottom:((vcenter - 178) / 2)})
	
    	setTimeout(function() {
			window.heroHeight = ((vcenter / 2) - 50);
			$('#home_hero').animate({height:window.heroHeight} , {duration: 500 , easing: 'easeInOutBack'})
			$('#logo_small').animate({bottom:window.heroHeight / 2} , {duration: 500 , easing: 'easeInOutBack'})
			$('#main_content').animate({top:window.heroHeight} , {duration: 500 , easing: 'easeInOutBack'})
		}, 1500)
    }
    else {
	    if ( $("#about_hero").length ) {
	    	$("#navigation").css({bottom:-13}) 
		    $("#logo_smaller").css({bottom:90})
	    }
    }
	



    // Initialize AJAX Loading Processes

    $.ajaxSetup ({  
        cache: false  
    });
    


    // Bind scroll targets to button clicks on the page
    // The destination MUST have the ID of Clicked div IS + "_hero"
    // So #Button would link to #Button_hero
	
	$('.team_member_image').click(function(callback) {
		var scrollDestination = ('#' + $(this).attr('id') + '_hero');
		console.log(scrollDestination);
		//callback(
		aboutScroll(scrollDestination);
		});
    

	// function for UP Arrow on Contact page
	
	$('#scroll_top').click(function(callback) {
   		// $("#scroll_top").animate({opacity: 0}, {duration: 300})
   		// $(".contact_info").animate({opacity: 0}, {duration: 300})
   			
   	$(document.body).animate({scrollTop: $('#labs_map').offset().top - 139}, 500 );

   	$.scrollTo($('#labs_map').offset().top-139 , 1000, {axis:'y' , easing:"easeOutSine" , onAfter: function moveItems() {
   		//$("#scroll_top").animate({opacity: 0}, {duration: 300})
   		//$('.contact_info').css({top:0})
   		//$(".contact_info").animate({opacity: 1}, {duration: 300})

    	}
    	});
    });


    // Load Fancy Box for Projects pages

	$(".fancybox_thumb").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: true,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
	
	
}); // END Window Load    



$(window).resize(function() {

//All functions that happen on Window Resizing

    var vcenter = $(window).height();
    var hcenter = $(window).width();
    var upArrowL = $('#main_right_col').position();

    $('#navigation').css({left:((hcenter / 2) - 73)})
    $('#logo_small').css({left:((hcenter / 2) - 110)})
    $('#logo_smaller').css({left:((hcenter / 2) - 73)})
    $('#labs_map').css({height:(vcenter / 2)})
    
    if ($('#map-canvas').length) {
    	$('#map-canvas').css({height:(vcenter / 2)})
    	$('#map-canvas').css({width: hcenter})
    }
    
    if (hcenter <= 1450) {
		$('#scroll_top').css({display: 'none'})
		$('.contact_info').css( {position: 'relative' , top:0})
    }
    else {
    	$('#scroll_top').css({display: 'block'})
    	if (upArrowL == null) {
	    	return;
    	}
    	else{
    		$('#scroll_top').css({left:((upArrowL.left) + (hcenter * .05) + 26 )})
    	}
    	$('.contact_info').css( {position: 'fixed' , top: 458 })
    }
    
    if ($('#project_hero_image').length) {
    	window.projectImageHeight = $('#project_hero_image').height();
        $('.project_hero').css({height:window.projectImageHeight})
    }
    
    // Position nav and logo elements vertically on homepage
    if ( $("#home_hero").length ) {
    	window.heroHeight = ((vcenter / 2) - 50);
		$('#home_hero').css({height:window.heroHeight})
		$('#logo_small').css({bottom:window.heroHeight / 2})
		$('#main_content').css({top:window.heroHeight})
    }
    else {
	    if ( $("#about_hero").length ) {
	    	$("#navigation").css({bottom:-13})
		    $("#logo_smaller").css({bottom:90})
	    }
    }
    

}); // END Window Resize


	// Automatically trigger the search page if the user starts typing
	// The URL that we'll be AJAX loading for the search page
	// We're going to save this for PHASE II

	// Listen to keys pressed on the keyboard

	// $(function(){
    //	$(document).bind('keyup', loadSearch);
    // });
    
    // If one of the keys pressed is a letter or a number, fade in the search page
	// This uses html5 and pushState() to change the URL of the browser
	// Store the letter that the user typed and inject those letters
	// into the search pane, then focus on the textfield
    
	function loadSearch(keyPressed) {
       
    	if (window.searchTriggered == false) {
        	var typed = keyPressed.keyCode
            
        	if (typed >= 48 && typed <= 90) {
    
            	window.searchTriggered = true;
            	var searchString = (String.fromCharCode(keyPressed.keyCode));
            
            	$.ajax({
            		type: "GET" ,
            		dataType: "text",
            		cache: true,
            		// processData:
            		url: "search" ,
            		error: function(jqXHR,textStatus,errorThrown) {
	            		//alert(errorThrown);
	            	} ,
	            	success: function(data) {
		            	history.pushState(null, 'Search', 'search');
		            	$('body').replaceWith(data);
		            	$("#search").animate({opacity: 1}, {duration: 300})
		            	//alert(searchString);
		            	$("#field").val(searchString);
		            	$("#field").focus();}
		            });
		        }
		    }
		    else {return true;}
	}



	// Function to scroll to any destination on the page given on an input defined above

	var aboutScroll = function(scrollDestination) {
    
		window.contactVertPosition = $(scrollDestination).position();
		console.log(window.contactVertPosition); 	
		$(".contact_info").animate({opacity: 0}, {duration: 300})
		$(".contact_info").css({top:(window.contactVertPosition.top + 276 + 'px')})
		$(document.body).animate({scrollTop: $(scrollDestination).offset().top - 139}, 1000 );
		$(window).scrollTo(($(scrollDestination).offset().top - 139) , 1500, {axis:'y' , easing:"easeInOutCirc" , onAfter: function moveItems() {
   			//$("#scroll_top").animate({opacity: 1}, {duration: 300})
   			$(".contact_info").animate({opacity: 1}, {duration: 300})

   			}
   		});
   		$(document.body).animate({scrollTop: $(scrollDestination).offset().top - 139}, 1000 );
   	}

   	$(window).scroll(function(){
    	var vcenter = $(window).height();
    	var hcenter = $(window).width();

        	if ($(window).scrollTop() > ((vcenter / 2) - 320)) {
            	var upArrowL = $('#main_right_col').position();
            	// $('#scroll_top').animate({opacity: 1} , {duration: 300})
            	// if ($(window).scrollTop() >= (vcenter / 2)) {
            	// console.log(upArrowL);
            
            	if (hcenter <= 1450) {
	            	$('#scroll_top').css({display: 'none'})
	            	$('.contact_info').css( {position: 'relative' , top:0})
	            }
	            else {
		            $('#scroll_top').css({display: 'block'})
		            $('#scroll_top').css({opacity: 1})
		            $('.contact_info').css( {position: 'fixed' , top: 458 })
		            $('.contact_info').css( {opacity: 1})
		        }
        
		        // $('.contact_info').css({position: 'fixed' , top: 416})

           }
           else {
	        	//$('#scroll_top').css({opacity: 0})
	        	$('.contact_info').css({position: 'static' , top: 0})
	       }
	});