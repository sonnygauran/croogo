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
    <title><?php echo Configure::read('Site.title'); ?></title>
    <meta name="viewport" content="width=device-width" />
    <?php
    echo $this->Layout->meta();
    echo $this->Layout->feed();
    echo $this->Html->css('theme');
    echo $this->Layout->js();
    echo $scripts_for_layout;
    ?>
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <section id="container">
        <header class="banner shadow">
            <div class="logo"><a href="<?= $this->webroot ?>"><?php echo $this->Html->image('logo.png'); ?></a></div>
            <!--<h1 class="logo"><a href="<?= $this->webroot ?>">weather | philippines</a></h1>-->
            <div id="slides">
                <div class="slides_container">
                    <img src="<?= $this->webroot ?>theme/weatherph/img/mm.png" alt="Meteomedia">
                    <img src="<?= $this->webroot ?>theme/weatherph/img/az.png" alt="Aboitiz Power" style="display: none;">
                    <img src="<?= $this->webroot ?>theme/weatherph/img/ub.png" alt="Union Bank" style="display: none;">
                </div>
            </div>
            <div id="options">
                <img src="<?= $this->webroot ?>theme/weatherph/img/flag.png" alt="Philippines" />
                <form class="search" action="/search" method="POST">
                    <label for="search-field">Search:&nbsp;</label><input id="search-field" type="text" name="terms" size="12" />
                    <img src="<?= $this->webroot ?>theme/weatherph/img/search.png" alt="" />
                </form>
            </div>
        </header> <!--BANNER-->

        <nav class="shadow cf">
            <ul class="dropdown">
                <li><a href="<?= $this->webroot ?>">Home</a></li>
                    <li>
                        <a href="#">Founders &#9663;</a>
                        <ul>
                            <li><a href="<?= $this->webroot ?>founders/meteomedia">MeteoMedia</a></li>
                            <li><a href="<?= $this->webroot ?>founders/aboitiz">Aboitiz</a></li>
                            <li><a href="<?= $this->webroot ?>founders/unionbank">UnionBank</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Dictionary &#9663;</a>
                        <ul>
                            <li><a href="<?= $this->webroot ?>dictionaries/english">English</a></li>
                            <li><a href="<?= $this->webroot ?>dictionaries/filipino">Filipino</a></li>
                        </ul>
                    </li>
                <li><a href="#">Payong Panahon</a></li>
                <li><a href="<?= $this->webroot ?>webcam">Webcams</a></li>
                <li><a href="<?= $this->webroot ?>about">About</a></li>
            </ul>
        </nav>

        <div class="severe-warning shadow">
            <p><strong>Alert:</strong> Typhoon Dador is approaching the NCR. No classes in all levels. Stay at home!</p>
        </div>

        <div id="sidebar">
            <div class="sponsored">
                <h6>Platinum sponsors:</h6>
                <ul>
                    <li><?php echo $this->Html->image('sponsor-nac.png'); ?></li>
                    <li><?php echo $this->Html->image('sponsor-icts.jpg'); ?></li>
                    <li><?php echo $this->Html->image('sponsor-ngcp.png'); ?></li>
                    <li><?php echo $this->Html->image('sponsor-vistaland.png'); ?></li>
                    <li><?php echo $this->Html->image('sponsor-tyk-mod.png'); ?></li>
                    <li><?php echo $this->Html->image('sponsor-sm.gif'); ?></li>
                </ul>
            </div>
            <div class="social">
                <h6>Follow us on Facebook and Twitter</h6>
                <a><?php echo $this->Html->image('facebook.png'); ?></a>
                <a href="http://twitter.com/weatherph"><?php echo $this->Html->image('twitter.png'); ?></a>
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
            <div class="promo">
                <a href="http://en.wikipedia.org/wiki/Boracay">
                <h4>Visit Boracay!</h4>
                <img src="<?= $this->webroot ?>theme/weatherph/img/boracay.jpg" alt="Boracay!"/>
                </a>
                <p>Boracay is an island of the Philippines located approximately 315 km (196 mi) south of Manila. Boracay Island and its beaches have received awards numerous times.</p>
            </div>
            <div class="promo">
                <a href="http://en.wikipedia.org/wiki/Pamalican">
                <h4>Discover Pamalican.</h4>
                <img src="<?= $this->webroot ?>theme/weatherph/img/pamalican.jpg" alt="Pamalican!"/>
                </a>
                <p>Pamalican Island is a small island of the Cuyo Islands in the Sulu Sea.</p>
            </div>
        </div><!--END SIDEBAR-->

        <?php
        echo $this->Layout->sessionFlash();
        echo $content_for_layout;
        ?>

        <footer>
            <small>&copy; 2012 Meteomedia A.G.</small>
        </footer>
    </section><!-- #container -->

<?php
    echo $this->Html->script('jquery/jquery.min');

    if (($this->name == 'Weatherph') && ($this->action == 'index') || ($this->name == 'Search') && ($this->action == 'index')){
        echo $this->Html->script('libs/jquery.geo-1.0a4.min');
    }
    
    if (($this->name == 'Weatherph') && ($this->action == 'index')){
        echo "<script type='text/javascript' src='" . $this->webroot . "weatherph/js/weatherph/index.js'></script>";
    } else if (($this->name == 'Search') && ($this->action == 'index')){           
        echo "<script type='text/javascript' src='" . $this->webroot . "weatherph/js/weatherph/results.js'></script>";
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
    });
</script>

</body>
</html>