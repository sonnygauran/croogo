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
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title><?php echo $title_for_layout; ?> &raquo; <?php echo Configure::read('Site.title'); ?></title>
        <?php
        echo $this->Layout->meta();
        echo $this->Layout->feed();
        echo $this->Html->css(array(
            'reset',
            'theme',
        ));
        echo $this->Layout->js();
        echo $this->Html->script(array(
            'jquery/jquery.min',
            'jquery/jquery.hoverIntent.minified',
            'jquery/superfish',
            'jquery/supersubs',
            'libs/jquery.geo-1.0a4',
            'slides.min.jquery',
        ));
        echo $scripts_for_layout;
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#slides').slides({
                    preload: false,
                    effect: 'fade',
                    play: 5000,
                    pagination: false,
                    generatePagination: false,
                    generateNextPrev: false
                });
            });
	</script>
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <section id="container">
            <header class="banner clear shadow">    
                <h1 class="logo"><a href="<?= $this->webroot ?>">weather | philippines</a></h1>
                
                <div id="slides">
                    <div class="slides_container">
                        <img src="<?= $this->webroot ?>theme/weatherph/img/mm.png" alt="Meteomedia">
                        <img src="<?= $this->webroot ?>theme/weatherph/img/az.png" alt="Aboitiz Power" style="display: none;">
                        <img src="<?= $this->webroot ?>theme/weatherph/img/ub.png" alt="Union Bank" style="display: none;">
                    </div>
                </div>
                
                <div id="options">
                    <img src="<?= $this->webroot ?>theme/weatherph/img/flag.png" alt="Philippines" />
                    <form class="search">
                        <label for="search-field">Search:</label><input id="search-field" type="text" name="city" size="15" />
                        <img src="<?= $this->webroot ?>theme/weatherph/img/search.png" alt="" />
                    </form>
                    
                </div>
            </header> <!--BANNER-->

            <nav class="shadow">
                <ul>
                    <li><a href="<?= $this->webroot ?>">Home</a></li>
                    <li><a href="<?= $this->webroot ?>typhoon-preparedness">Typhoon Preparedness</a></li>
                    <li><a href="<?= $this->webroot ?>typhoon-glossary">Typhoon Glossary</a></li>
                    <li><a href="<?= $this->webroot ?>typhoon-climatology">Typhoon Climatology</a></li>
                    <li><a href="/about">About</a></li>
<!--                    <li><a href="#">Impressum</a></li>-->
                </ul>
            </nav>
<!--            
            <div class="severe-warning shadow">
                <p><strong>Alert:</strong> Typhoon Dador is approaching the NCR. No classes in all levels. Stay at home!</p>
            </div>
            -->
            <div id="sidebar">
                <div class="sponsored">
                    <h6>Platinum sponsors:</h6>
                    <ul>
                        <li><?php echo $this->Html->image('sponsor-sm.gif'); ?></li>
                        <li><?php echo $this->Html->image('sponsor-ngcp.png'); ?></li>
                        <li><?php echo $this->Html->image('sponsor-vistaland.png'); ?></li>
                        <li><?php echo $this->Html->image('sponsor-icts.jpg'); ?></li>
                        <li><?php echo $this->Html->image('sponsor-nac.png'); ?></li>
                        <li><?php echo $this->Html->image('sponsor-tyk-mod.png'); ?></li>
                    </ul>
                </div>
                
                <!-- AddThis Button BEGIN -->
                <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                <a class="addthis_button_facebook"></a>
                <a class="addthis_button_twitter"></a>
                <a class="addthis_button_linkedin"></a>
                <a class="addthis_button_email"></a>
                <a class="addthis_button_print"></a>
                </div>
                <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f94bc453ecffca4"></script>
                <!-- AddThis Button END -->

                <div class="ads">
                    <div class="promo">
                        <h4>Visit Boracay!</h4>
                        <img src="<?= $this->webroot ?>theme/weatherph/img/boracay.jpg" alt="Boracay!"/>
                        <p>Boracay is an island of the Philippines located approximately 315 km (196 mi) south of Manila.
							Boracay Island and its beaches have received awards numerous times.</p>
                    </div>

                    <div class="promo">
                        <h4>Discover Pamalican.</h4>
                        <img src="<?= $this->webroot ?>theme/weatherph/img/pamalican.jpg" alt="Pamalican!"/>
                        <p>Pamalican Island is a small island of the Cuyo Islands in the Sulu Sea.</p>
                    </div>
                </div>

            </div>
<?php
            echo $this->Layout->sessionFlash();
            echo $content_for_layout;
?>
            <footer>
                <small>&copy; 2012 Meteomedia A.G.</small>
            </footer>

        </section><!-- #container -->
    </body>
</html>
