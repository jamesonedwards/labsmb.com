                <!-- Included NAV Module -->
                <!-- Link for page we're on needs class "nav_item_selected" -->
                <?php
                    // Determine which nav element should be marked as selected.
                    $homeSelected = ($page == 'home_index') ? '_selected' : '';
                    $searchSelected = ($page == 'search_index') ? '_selected' : '';
                    $tagsSelected = ($page == 'tags_index') ? '_selected' : '';
                    $infoSelected = ($page == 'info_index') ? '_selected' : '';
                    // This might not get used:
                    $projectsSelected = ($page == 'projects_index') ? '_selected' : '';
                ?>
                <div id="navigation">
                    <a href="/">
                    	<div id="first_nav_box" class="nav_item<?=$homeSelected;?>">
	                    	<img class="nav_label" src="/images/home_nav<?=$homeSelected;?>.png" width="42" height="42" border="0">
	                    	<img class="nav_label" src="/images/home_nav<?=$homeSelected;?>.png" width="42" height="42" border="0">
	                    </div>
                    </a>
                    <a href="/search">
                    	<div id="second_nav_box" class="nav_item<?=$searchSelected;?>">
                    		<img class="nav_label" src="/images/search_nav<?=$searchSelected;?>.png" width="42" height="42" border="0">
                    		<img class="nav_label" src="/images/search_nav<?=$searchSelected;?>.png" width="42" height="42" border="0">
                    	</div>
                    </a>
                    <?php /* Remove tags for now.
                    <a href="/tags">
                    	<div id="third_nav_box" class="nav_item<?=$tagsSelected;?>">
	                    	<img class="nav_label" src="/images/tags_nav<?=$tagsSelected;?>.png" width="42" height="42">
	                    	<img class="nav_label" src="/images/tags_nav<?=$tagsSelected;?>.png" width="42" height="42">
	                    </div>
                    </a>
                    */ ?>
                    <a href="/info">
                    	<div id="fourth_nav_box" class="nav_item<?=$infoSelected;?>">
	                    	<img class="nav_label" src="/images/info_nav<?=$infoSelected;?>.png" width="42" height="42" border="0">
	                    	<img class="nav_label" src="/images/info_nav<?=$infoSelected;?>.png" width="42" height="42" border="0">
	                    </div>
                    </a>
                </div>
                <!-- END Included NAV Module -->