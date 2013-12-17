<?php require('includes/admin_header.php'); ?>
<?php require('includes/admin_show_message.php'); ?>

<div><a href="/admin/view_project/create">Create New Project</a></div>
<div>
    <?php foreach ($projects as $project) {
        echo '<div>' . $project->name . ' [<a href="/admin/view_project/' . $project->id . '">Edit</a>] ';
        echo '[<a href="/projects/' . $project->url_key . '/-/preview" target="_blank">Preview</a>] ';
        
        if ($project->enabled)
            echo '[<a href="/projects/' . $project->url_key . '" target="_blank">View</a>]';
        else
            echo '[View] (enable project to view)';
        echo '</div>' . PHP_EOL;
    } ?>
</div>
<?php require('includes/admin_footer.php'); ?>