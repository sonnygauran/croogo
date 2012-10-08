<?php
/**
 * Weatherph Frontpage
 *
 * @author Martin de Lima <mdelima@meteomedia.com.ph>
 * @link http://www.weather.com.ph
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo Configure::read('Site.title'); ?>
        <?php if (strlen($title_for_layout) > 1): ?>
            &nbsp;|&nbsp;<?= $title_for_layout ?>
        <?php endif; // comment ?>
    </title>

<!--    <title><?php echo ($title_for_layout).' | ' . Configure::read('Site.title'); ?></title>
    -->
    <meta name="viewport" content="width=device-width" />
    
    <?php
    echo $meta_for_description;
    echo $this->Layout->meta();
    echo $this->Layout->feed();
    echo $this->Html->css('theme');
    echo $this->Layout->js();
    ?>
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4.4/leaflet.css" />
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4.4/leaflet.ie.css" />
    <![endif]-->
</head>
<body>
    <section id="container">
        <header class="banner shadow">
            <a href="<?= $this->webroot ?>"><div class="logo"></div></a>
            <div id="slides">
                <div class="slides_container">
                    <img src="<?= $this->webroot ?>theme/weatherph/img/mm.png" alt="Meteomedia">
                    <img src="<?= $this->webroot ?>theme/weatherph/img/az.png" alt="Aboitiz Power" style="display: none;">
                    <img src="<?= $this->webroot ?>theme/weatherph/img/ub.png" alt="Union Bank" style="display: none;">
                </div>
            </div>
            <div id="options">
                <div class="flag"></div>
                <form class="search" action="/search" method="POST">
                    <label for="search-field">Search:&nbsp;</label><input id="search-field" type="text" name="terms" size="12" />
                    <input type="submit" value="Search" class="search-icon"></div>
                </form>
            </div>
        </header> <!--BANNER-->

        <nav class="shadow cf">
            <ul class="dropdown">
                <li><a href="<?= $this->webroot ?>">Home</a></li>
                <li>
                    <a href="#">Dictionary &#9663;</a>
                    <ul>
                        <li><a href="<?= $this->webroot ?>dictionaries/english">English</a></li>
                        <li><a href="<?= $this->webroot ?>dictionaries/filipino">Filipino</a></li>
                    </ul>
                </li>
                <li><a href="<?= $this->webroot ?>news">News</a></li>
                <!-- <li><a href="<?= $this->webroot ?>announcements">Mata ng Bagyo</a></li> -->
                <li><a href="<?= $this->webroot ?>weathertv">Weather TV</a></li>
                <li><a href="<?= $this->webroot ?>webcam">Webcams</a></li>
                <li><a href="<?= $this->webroot ?>about">About</a></li>
            </ul>
        </nav>

        <?php
        // If there is a "Mata ng Bagyo" post within the last 24 hours, show the following warning:
        if(isset($show_alert) && $show_alert):
        ?>
          
        <div class="severe-warning shadow">
            <strong>Announcement:</strong> <?= $severe_warning['Node']['excerpt'] ?> 
             
            <a href="/announcements/<?= $severe_warning['Node']['slug']?>">Read More</a> </p>
            <a id="close-warning" href="#">x</a>
        </div>
        <?php
        endif;
        ?>

        <div id="sidebar">
            <div class="sponsored">
                <h6>Platinum sponsors:</h6>
                <ul>
                    <li class="sponsor-nac"></li>
                    <li class="sponsor-icts"></li>
                    <li class="sponsor-ngcp"></li>
                    <li class="sponsor-vistaland"></li>
                    <li class="sponsor-tyk"></li>
                    <li class="sponsor-sm"></li>
                </ul>
            </div>
            <div class="social">
                <h6>Follow us on Facebook and Twitter</h6>
                <a href ="https://www.facebook.com/weather.com.ph"><div class="fb"></div></a>
                <a href="http://twitter.com/weatherph"><div class="twitter"></div></a>
            </div>
            <div class="sponsored">
                <?php
                $images = array(
                    'quasha.jpeg',
                    'ocean_adventure.png',
                    'syngenta.png',
                );

                $hat = rand(1, count($images)) - 1;

                ?>
                <h6>Gold sponsors:</h6>
                <ul>
                    <li><?php echo $this->Html->image($images[$hat], array('width' => '120px')); ?></li>
                </ul>
            </div>
        <?php if ($tourism_links): ?>
            <?php $excerpt1 = substr ($tourism_links[0]['Node']['excerpt'], 0, 100)?>
            <?php $excerpt2 = substr ($tourism_links[1]['Node']['excerpt'], 0, 100)?>
            <div class="promo">
                <a href="<?= $this->webroot ?>visit/<?= $tourism_links[0]['Node']['slug'] ?>">
                <h4>Places to see: <?= $tourism_links[0]['Node']['title'] ?></h4>
                <img src="<?= $this->webroot ?>theme/weatherph/img/tourism_thumbnails/<?= $tourism_links[0]['Node']['slug'] ?>.png" alt="<?= $tourism_links[0]['Node']['title'] ?>">
                </a>
                <p><?= $excerpt1 ?>&#8230;</p>
                <a href="<?= $this->webroot ?>visit/<?= $tourism_links[0]['Node']['slug'] ?>">
                    <div class="tourism-btn"><strong>See more</strong></div>
                </a>
            </div>

            <div class="promo">
                <a href="<?= $this->webroot ?>visit/<?= $tourism_links[1]['Node']['slug'] ?>">
                <h4>Discover <?= $tourism_links[1]['Node']['title'] ?>.</h4>
                <img src="<?= $this->webroot ?>theme/weatherph/img/tourism_thumbnails/<?= $tourism_links[1]['Node']['slug'] ?>.png" alt="<?= $tourism_links[1]['Node']['title'] ?>">
                </a>
                <p><?= $excerpt2 ?>&#8230;</p>
                <a href="<?= $this->webroot ?>visit/<?= $tourism_links[1]['Node']['slug'] ?>">
                    <div class="tourism-btn"><strong>See more</strong></div>
                </a>
            </div>
        <?php endif; // tourism links ?>
        </div><!--END SIDEBAR-->

        <?php
        echo $this->Layout->sessionFlash();
        echo $content_for_layout;
        ?>

        <footer>
            <small>&copy; 2012 Meteomedia Philippines</small>
        </footer>
    </section><!-- #container -->

<?php
/*
Google Analytics script

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-8697204-38']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
*/

    echo $this->Html->script('jquery/jquery.min');

    if (($this->name == 'Weatherph') && ($this->action == 'index') || ($this->name == 'Search') && ($this->action == 'index')){
        echo '<script src="http://cdn.leafletjs.com/leaflet-0.4.4/leaflet.js"></script>';
    }

    echo $this->Html->script('slides.min.jquery');
?>

<script type="text/javascript">
    $(window).load(function(){
        $('#slides').slides({
            preload: false,
            effect: 'fade',
            play: 5000,
            pagination: false,
            generatePagination: false,
            generateNextPrev: false
        });

        $("nav li").click(function(){
            window.location=$(this).find("a").attr("href"); return false;
        });

        $("#close-warning").click(function(){
            $('.severe-warning').fadeOut();
        });
    });
</script>
<?= $scripts_for_layout ?>
</body>
</html>
