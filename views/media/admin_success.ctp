<style>
    div.form{
        font-size: 16px;
    }
    
    div.form > pre{
        background-color: #eee; 
        margin-top: 20px; 
        padding: 20px 10px; 
        letter-spacing: -1px
    }
</style>
<div class="form">
    <h1>Paste this code into your blog to import your video</h1>

    <pre>
    <?= htmlentities("<video height='$height' width='$width' controls>") . "\n" ?>
        <?= htmlentities("<source src='$url.webm' type='video/x-m4v;' />") . "\n"?>
        <?= htmlentities("<source src='$url.mp4' type='video/mp4;'/>") . "\n"?>
        <?= htmlentities("<source src='$url.m4v' type='video/webm;'/>") . "\n"?>
    <?= htmlentities("</video>")?>

</pre>
</div>
