<?php require('includes/header.php'); ?>
            <div id="home_hero">
                <div id="logo_small"></div>
<?php require('includes/nav.php'); ?>
            </div>
            <div id='main_content'>
                <div style="margin: 0 auto; width: 80%; min-width: 320px; height: auto;">
                    <div id="main_left_col">
                        <div class="large_blurb">LABSmb is a small, multi-disciplinary team of problem solvers, thinkers and innovators, tasked with developing solutions by observing emerging trends, technologies, or cultural shifts that will impact how people will and want to work, live and play.</div>
                        <div id="home_first_three_col">
                            <div id="latest_proj_heading"><span style="margin: 0 0 0 11px;">LATEST PROJECTS</span></div>

                            <?php foreach ($projects as $project) { ?>
                                <!-- Begin Project Module -->
                                <div class="content_module">
                                    <a href="/projects/<?=$project->url_key;?>" style="text-decoration: none"><div class="content_module_image"><img src="<?=$project->small_image_url;?>" width="100%" border="0"></div>
                                    <div class="content_module_title"><?=$project->name;?></div></a>
                                    <div class="content_module_date"><?=Home::format_project_date($project->created);?></div>
                                    <div class="content_module_desc"><?=$project->intro;?></div>
                                    <a href="/projects/<?=$project->url_key;?>" style="text-decoration: none"><div class="content_module_link">VIEW PROJECT></div></a>
                                </div>
                                <!-- End Project Module -->
                            <?php } ?>
                    
                        </div>
                        <div class="home_three_col">
                            <div id="going_on_heading"><span style="margin: 0 0 0 11px;">WHAT'S GOING ON?</span></div>
                            <!-- Begin Tumblr Module -->
                            <?php foreach ($tumblrPhotoAndVideoPosts as $post) { ?>
                                <div class="content_module">
                                <?php if ($post['type'] == 'photo') { ?>
                                    <a href="<?=$post['url'];?>" style="text-decoration: none" target="_blank"><div class="content_module_image"><img src="<?=$post->{'photo-url'}[2];?>" width="100%" border="0"></div></a>
                                    <div class="content_module_date"><?=Home::format_tumblr_date((string)$post['unix-timestamp']);?></div>
                                    <div class="content_module_desc"><?=$post->{'photo-caption'};?></div>
                                    <a href="<?=$post['url'];?>" style="text-decoration: none" target="_blank"><div class="content_module_link">VIEW POST></div></a>
                                <?php } elseif ($post['type'] == 'video') { ?>
                                	<div class="content_module_video_wrap">
                                    		<a href="<?=$post['url'];?>" style="text-decoration: none" target="_blank"><div class="content_module_video"><?=$post->{'video-player'}[2];?></div></a>
                                	</div>
                                    <div class="content_module_date"><?=Home::format_tumblr_date((string)$post['unix-timestamp']);?></div>
                                    <div class="content_module_desc"><?=show_long_text_truncated($post->{'video-caption'}, 50);?></div>
                                    <a href="<?=$post['url'];?>" style="text-decoration: none" target="_blank"><div class="content_module_link">VIEW POST></div></a>
                                <?php } ?>
                                </div>
                            <?php } ?>
                            <!-- End Tumblr Module -->
                        </div>
                        <div class="home_three_col" id="inspiration_col">
                            <div id="pinterest_heading"><span style="margin: 0 0 0 11px;">INSPIRATION</span></div>
                            <!-- Begin Pinterest Module -->
                            <?php if ($showPinterest) { ?>
                                <?php foreach ($pinterestPins as $pin) { ?>
                                    <div class="content_module">
                                        <a href="<?=$pin['url'];?>" style="text-decoration: none" target="_blank"><div class="content_module_image"><img src="<?=$pin['img'];?>" width="100%" border="0"></div></a>
                                        <div class="content_module_date"><?=Home::format_pinterest_date($pin['pubDate']);?></div>
                                        <div class="content_module_desc"><?=$pin['text'];?></div>
                                        <a href="<?=$pin['url'];?>" style="text-decoration: none" target="_blank"><div class="content_module_link">VIEW PIN></div></a>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <?php foreach ($tumblrTextPosts[0] as $post) { ?>
                                    <div class="content_module">
                                        <a href="<?=$post['url'];?>" style="text-decoration: none" target="_blank"><div class="content_module_image"><?=$post->{'regular-body'};?></div></a>
                                        <div class="content_module_date"><?=Home::format_tumblr_date((string)$post['unix-timestamp']);?></div>
                                        <a href="<?=$post['url'];?>" style="text-decoration: none" target="_blank"><div class="content_module_link">VIEW POST></div></a>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <!-- End Pinterest Module -->
                        </div>
                    </div>
                    <div id="main_right_col_home">
                        <div id="latest_tweet"><div id='tweet_date'><?=$tweetDate;?></div><?=$tweetText;?><a href="http://twitter.com/labsmb" style="text-decoration: none" target="_blank"><div id='twitter_link'>SEE MORE TWEETS></div></a></div>
                        <div class="contact_info_rel"><span class='labs_heading'>mcgarrybowen LABS</span><br style="clear: both;" /><span style='font-size: .75em;'>601 West 26th Street<br />Suite 1150<br />New York<br />NY 10001<br /><span style='display: block; margin: 32px 0 0 0;'>t: 646.231.5086</span><span style='display: block; margin: 15px 0 0 0;'>e: <a href='mailto:labs@mcgarrybowen.com'>labs@mcgarrybowen.com</a></span></div>
                    </div>
                </div>
            </div>
        </div>
<?php require('includes/footer.php'); ?>