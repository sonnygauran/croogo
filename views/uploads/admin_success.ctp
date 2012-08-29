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
    <?= htmlentities("<video height='$height' width='$width'>") . "\n" ?>
        <?= htmlentities("<source href='$url.webm' />") . "\n"?>
        <?= htmlentities("<source href='$url.mp4' />") . "\n"?>
        <?= htmlentities("<source href='$url.m4v' />") . "\n"?>
    <?= htmlentities("</video>")?>

</pre>
</div>
