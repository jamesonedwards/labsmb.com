<?php require('includes/admin_header.php'); ?>
<?php require('includes/admin_show_errors.php'); ?>

<div><a href="/admin">Back to Project List</a></div>
<div>
    <?=form_open_multipart('admin/save_project', null, array('id' => $project->id, 'created' => $project->created));?>
        <div>
            Project Name: <?=form_input('name', $project->name);?>
        </div>
        <div>
            URL Key: <?=form_input('url_key', $project->url_key);?>
        </div>
        <div>
            Tags (comma-separated): <?=form_input('tags', $project->tags);?>
        </div>
        <div>
            Flickr Photo Set ID: <?=form_input('flickr_photo_set_id', $project->flickr_photo_set_id);?>
        </div>
        <div>
            Video video URL: <?=form_input('video_url', $project->video_url);?>
        </div>
        <div>
            Project Video Screenshot URL: <?=form_input('video_screenshot_url', $project->video_screenshot_url);?>
        </div>
        <div>
            Quote: <?=form_input('quote', $project->quote);?>
        </div>
        <div>
            Intro text: <?=form_textarea('intro', $project->intro);?>
        </div>
        <div>
            Description text (separate columns with "<?=Project::DESCRIPTION_COLUMN_SEPARATOR;?>"): <?=form_textarea('description', $project->description);?>
        </div>
        <div>
            Small image (for home page):<br>
            <img src="<?=$project->small_image_url . '?' . time();?>" alt=""><br>
            <?=form_upload('small_image_url', $project->small_image_url);?>
        </div>
        <div>
            Large image (for project page):<br>
            <img src="<?=$project->large_image_url . '?' . time();?>" alt=""><br>
            <?=form_upload('large_image_url', $project->large_image_url);?>
        </div>
        <div>
            Resource links (one per line, in the format: [URL], [text]): <?=form_textarea('resource_links', $project->resource_links);?>
        </div>
        <div>
            Enabled?: <?=form_checkbox('enabled', 'enabled', $project->enabled);?>
        </div>
        <div>
            Date Created: <?=$project->created;?>
        </div>
        <div>
            Date Modified: <?=$project->updated;?>
        </div>
        <div>
            <?=form_submit('btn_submit', $saveBtnText);?>
        </div>
    <?=form_close();?>
</div>
<hr>
<div>
    <?=form_open('admin/delete_project', null, array('id' => $project->id));?>
        <div>
            <?=form_submit('btn_delete', 'Delete Project');?>
        </div>
    <?=form_close();?>
</div>
<?php require('includes/admin_footer.php'); ?>