<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?= $title_for_layout ?><?php if (strlen($title_for_layout) > 1): ?> | <?php echo Configure::read('Site.title'); ?><?php endif; // comment ?></title>
    <meta name="viewport" content="width=1000" />
    <?php
        echo (isset($og_image)) ? $this->Html->meta($og_image) :'';
        echo (isset($og_title)) ? $this->Html->meta($og_title) :'';
        echo (isset($og_description)) ? $this->Html->meta($og_description) :'';
        echo (isset($meta_for_description)) ? $meta_for_description :'';
        echo $this->Layout->meta();
        echo $this->Layout->feed();
        echo $this->Html->css('theme');
        echo $this->Layout->js();
    ?>
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="/mint/?js" type="text/javascript"></script>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4.4/leaflet.css" />
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4.4/leaflet.ie.css" />
    <![endif]-->
</head>
<body>
    <section id="container">
        <header class="banner shadow">
            <a href="<?= $this->webroot ?>">
                <img src="<?= $this->webroot ?>theme/weatherph/img/logo-wph.png" alt="Weather Philippines Foundation" class="logo">
            </a>
            <div class="founders">
                <img src="<?= $this->webroot ?>theme/weatherph/img/logo-meteomedia.png" alt="Meteomedia">
                <img src="<?= $this->webroot ?>theme/weatherph/img/logo-unionbank.png" alt="Union Bank">
                <img src="<?= $this->webroot ?>theme/weatherph/img/logo-aboitiz.png" alt="Aboitiz Power">
            </div>
            <span class="beta-tag">Beta</span>
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
                <li><a href="<?= $this->webroot ?>weathertv">Weather TV</a></li>
                <li><a href="<?= $this->webroot ?>webcam">Webcams</a></li>
                <li><a href="<?= $this->webroot ?>about">About</a></li>
                <li><a href="<?= $this->webroot ?>contact">Contact Us</a></li>
                <li>
                    <form class="search" action="/search" method="POST">
                        <!-- <input id="search-field" placeholder="Enter town/province name" type="text" name="terms" size="20" /> -->
                        <input type="text" id="search-field" placeholder="Enter city or province name..." id="LocationKeyword" name="terms" size="20" />
                        <input type="submit" value="Search"/>
                    </form>
                </li>
            </ul>
        </nav>

        <?php
        // If there is a "Mata ng Bagyo" post within the last 24 hours, show the following warning:
        if(isset($show_alert) && $show_alert):
        ?>

        <div class="severe-warning shadow">
            <strong>Announcement:</strong>

            <?php
            if ($severe_warning['Node']['excerpt'] == ''){
                    echo $text->excerpt(strip_tags(ucwords(strtolower($severe_warning['Node']['body']))), 'method', 100, '...' . $html->link('Read More', "/announcements/{$severe_warning['Node']['slug']}"));
                    //echo "  <a id='close-warning' href='#'>x</a>";
            ?>
            <?php }
                else {
                    echo $text->excerpt(strip_tags(ucwords(strtolower($severe_warning['Node']['excerpt']))), 'method', 100, '...' . $html->link('Read More', "/announcements/{$severe_warning['Node']['slug']}"));
                    echo "  <a id='close-warning' href='#'>x</a>";
                }
            ?>
        </div>
        <?php
        endif;
        ?>

        <?php
        echo $this->Layout->sessionFlash();
        echo $content_for_layout;
        ?>

        <div id="sidebar">
            <div class="sponsored">
                <h6>Platinum sponsors:</h6>
                <ul>
                    <li class="sponsor-nac-sumitomo"></li>
                    <li class="sponsor-icts" style="margin: 15px 0 10px 0;"></li>
                    <?//<li class="sponsor-ngcp" style="margin: 0 auto;"></li>?>
                    <li class="sponsor-vistaland"></li>
                    <?//<li class="sponsor-tyk" style="margin: 10px auto;"></li>?>
                    <li class="sponsor-sm"></li>
                </ul>
            </div>
            <div class="social">
                <h6>Follow us on Facebook and Twitter</h6>
                <a href ="https://www.facebook.com/weather.com.ph"><div class="fb"></div></a>
                <a href="http://twitter.com/weatherph"><div class="twitter"></div></a>
            </div>
            <div class="sponsored">
                <h6>Gold sponsors:</h6>

                <?php
                $images = array('quasha', 'ocean_adventure', 'syngenta', 'veco', 'snap', 'hedcor', 'dlpc');
                shuffle($images);
                $hat = array_rand($images, 7);
                ?>

                <div class="gold-sponsor-slides">
                    <div class="slides_container">
                        <img src="<?= $this->webroot ?>theme/weatherph/img/gold/<?= $images[$hat[0]] ?>.png">
                        <img src="<?= $this->webroot ?>theme/weatherph/img/gold/<?= $images[$hat[1]] ?>.png" style="display:none;">
                        <img src="<?= $this->webroot ?>theme/weatherph/img/gold/<?= $images[$hat[2]] ?>.png" style="display:none;">
                    </div>
                </div>

                <div class="gold-sponsor-slides">
                    <div class="slides_container">
                        <img src="<?= $this->webroot ?>theme/weatherph/img/gold/<?= $images[$hat[3]] ?>.png">
                        <img src="<?= $this->webroot ?>theme/weatherph/img/gold/<?= $images[$hat[4]] ?>.png" style="display:none;">
                        <img src="<?= $this->webroot ?>theme/weatherph/img/gold/<?= $images[$hat[5]] ?>.png" style="display:none;">
                        <img src="<?= $this->webroot ?>theme/weatherph/img/gold/<?= $images[$hat[6]] ?>.png" style="display:none;">
                    </div>
                </div>
            </div>

            <div class="sponsored">
                <a href="http://www.meteosphere.com/en/index.html">
                <h6>Download the app:</h6>
                <?php
                $img = array('meteosphere1', 'meteosphere2');
                shuffle($img);
                $random = array_rand($img, 2);
                ?>

                <div class="<?= $img[$random[0]] ?>"></div>
                </a>
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

        <footer>
            <small>&copy; 2012 Meteomedia Philippines</small>
        </footer>
    </section><!-- #container -->

    <!-- Google Analytics script -->
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-8697204-41']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
<?php
    echo $this->Html->script('jquery/jquery.min');

    if (($this->name == 'Weatherph') && ($this->action == 'index') || ($this->name == 'Search') && ($this->action == 'index')){
        echo '<script src="http://cdn.leafletjs.com/leaflet-0.4.4/leaflet.js"></script>';
    }

    echo $this->Html->script('slides.min.jquery');
?>
<script type="text/javascript">
    $(window).load(function(){
        $('.gold-sponsor-slides').slides({
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

        // $("#close-warning").click(function(){
        //     $('.severe-warning').fadeOut();
        // });
    });
</script>
<?= $scripts_for_layout ?>
</body>
</html>
