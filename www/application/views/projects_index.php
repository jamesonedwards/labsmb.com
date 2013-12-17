<?php require('includes/header.php'); ?>
            <div id="about_hero">
                <div id="logo_smaller"></div>
<?php require('includes/nav.php'); ?>
            </div>
            <div id='main_content'>
                <div id="projects_responsive_wrap">
                    <div id="main_left_col">
                        <!-- Begin Project Info -->
                        <div id="project_image" class="project_hero">
                        		<div id="project_hero_large_image"><img style="max-width: 100%;" src="<?=$project->large_image_url;?>"></div>
                        		
                            	<div id="project_hero_wrap">
                            		<div style="margin: 0 0 0 10%;">
                                    <?php if ($previewMode) echo '<div id="project_hero_name" style="color: red;">PREVIEW MODE</div>'; ?>
                                	<div id="project_hero_name"><p><?=$project->name;?></p></div>
                                	
                            		</div>
                                </div>
                        </div>
                        <div class="left_col_content">
                        <div class="large_blurb"><?=$project->intro;?></div>
                        	<div class="left_two_col"><span class="bios"><?=show_long_text($project->get_description_column(0));?></span></div>
                        	<div class="right_two_col"><span class="bios"><?=show_long_text($project->get_description_column(1));?></span></div>
                        </div>
                        <!-- End Project Info -->
                        
                    </div>
                    <div id="main_right_col">
                        
                        <div class="project_resources">
                        <?php if (strlen($referrer)) { ?>
                        	<div id="project_close">
	                        	<span class="closeX"><a href="<?=$referrer;?>"><img src="/images/close_x.png" width="100%" height="100%" border="0"></a></span>
                        	</div>
                        <?php } ?>
                        <?php if (count($project->get_resource_links_array())) { ?>
                            <span id="project_resources" class='project_resources_heading'>Project Resources</span>
                        	<br style="clear: both;" />
                        	<span style='font-size: .75em;'><?php echo join('<br />', $project->get_resource_links_array()); ?></span>
                        <?php } ?>
                        <?php if (strlen($project->video_url)) { ?>
                            <span id="project_video" class='project_resources_subheading'>Video</span>
                        	<div class="project_video_wrapper">
                                <iframe src="<?=$project->video_url;?>" width="500" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                        	</div>
                        <?php } ?>
                        <?php if (count($flickrImages)) { ?>
                            <span id="project_gallery" class='project_resources_subheading'>Gallery</span>
                        	<div class="project_resources_gallery">
                            <?php foreach ($flickrImages as $image) {
                        		echo '<div class="project_resources_gallery_thumb"><a class="fancybox_thumb" href="' . $image->media->b
                                    . '" target="_blank"><img src="' . $image->media->s . '" class="gallery_image_thumb fancybox.iframe"></a></div>' . PHP_EOL;
                            } ?>
                        	</div>
                        <?php } ?>
                        	<span id="project_gallery" class='project_resources_subheading'>Share</span>
                                <div style="width: 100%; margin-top: 10px; position: relative; float: left;">
	                            	<!-- AddThis Button BEGIN -->
	                                <div class="addthis_toolbox addthis_default_style ">
	                        			<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
	                        			<a class="addthis_button_preferred_2"></a>
	                        			<a class="addthis_button_preferred_3"></a>
	                        			<a class="addthis_button_preferred_4"></a>
	                        			<a class="addthis_button_compact"></a>
	                        			<a class="addthis_counter addthis_bubble_style"></a>
	                        		</div>
	                        		<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
	                        		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4df77683033f8d4e"></script>
	                        		<!-- AddThis Button END -->	                        		
	                        		
	                        	</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php require('includes/footer.php'); ?>