<?php require('includes/header.php'); ?>
            <div id="search">
	            <div id="search_function_container">
	            	<div id="search_navLogo_band">
	            		<div id="search_logo_band">
	            			<div id="logo_smaller"></div>
	            		</div>
	            			<?php require('includes/nav.php'); ?>
	            	</div>
	            	
	            	<div id="search_field_band">
	            		<div id="search_field_box">
		            		<form id="search_form"><input id="field" type="text" placeholder="search" autocomplete="off" value="<?=$lastSearch;?>" /></form>
	            		</div>
	            	</div>
	            
	            </div>
	           <div id="results_wrap"></div>
	            <!-- Begin Search Results Section -->
	            <!-- Turn this OFF if there are no Results -->
	            

	            
	        </div>

<?php require('includes/footer.php'); ?>
