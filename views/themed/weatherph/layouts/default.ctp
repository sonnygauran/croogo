<?php
/**
 * Weatherph main layout
 *
 * @author Sonny Gauran <sgauran@meteomedia.com.ph>
 * @link http://www.weather.com.ph
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $title_for_layout; ?> &raquo; <?php echo Configure::read('Site.title'); ?></title>
    <?php
        echo $this->Layout->meta();
        echo $this->Layout->feed();
        echo $this->Html->css(array(
            'reset',
            '960',
            'theme',
        ));
        echo $this->Layout->js();
        echo $this->Html->script(array(
            'jquery/jquery.min',
            'jquery/jquery.hoverIntent.minified',
            'jquery/superfish',
            'jquery/supersubs',
            'theme',
            'libs/jquery.geo-1.0a4',
        ));
        echo $scripts_for_layout;
    ?>
</head>
<body>

<div id="wrapper">
        <header id="header" class="container_16">
            <div class="grid_16">
        		<h1 id="logo">Weather Philippines</h1>
                <h1 class="site-title"><?php echo $this->Html->link(Configure::read('Site.title'), '/'); ?></h1>
                <span class="site-tagline"><?php echo Configure::read('Site.tagline'); ?></span>
            </div>
            <div class="clear"></div>
        </header>

	<nav>
		<ul>
			<li>Home</li>
			<li>Detailed Forecasts</li>
			<li>About</li>
			<li>Check the weather in:
				<form id="search">
					<input type="text" size="12">
					<input type="submit" title="Go">
				</form>
			</li>
		</ul>
	</nav>

	<section id="container">

		<div class="content">
			<section id="layer">
				<?php echo $this->Html->image('overlay.png'); ?>
			</section>
			<section class="map">
				<div id="map"></div>
			</section>

			<div class="ad">
				<p>powered by:</p>
				<h4>Aboitiz</h4>
			</div>
			<div class="sidebar">
				<div class="forecastPane">
					<h4>Today's Weather</h4>
					<?php echo $this->Html->image('cloudy3.png'); ?>
					<ul>
						<li>Makati</li>
						<li>Cloudy</li>
						<li>32&deg;</li>
					</ul>
				</div>
				
				<h4>Select by region:</h4>
				<select name="philippine-regions">
					<option>Choose one…</option>

					<optgroup label="Luzon">
						<option data-region-id="NCR">NCR</option>
						<option data-region-id="CAR">CAR</option>
						<option data-region-id="I">Ilocos</option>
						<option data-region-id="II">Cagayan Valley</option>
						<option data-region-id="III">Central Luzon</option>
						<option data-region-id="IVa">CALABARZON</option>
						<option data-region-id="IVb">MIMAROPA</option>
						<option data-region-id="V">Bicol</option>
					</optgroup>

					<optgroup label="Visayas">
						<option data-region-id="VI">Western Visayas</option>
						<option data-region-id="VII">Central Visayas</option>
						<option data-region-id="VIII">Eastern Visayas</option>
					</optgroup>

					<optgroup label="Mindanao">
						<option data-region-id="IX">Zamboanga Peninsula</option>
						<option data-region-id="X">Northern Mindanao</option>
						<option data-region-id="XI">Davao</option>
						<option data-region-id="XII">SOCCSKSARGEN</option>
						<option data-region-id="XIII">CARAGA</option>
						<option data-region-id="ARMM">ARMM</option>
					</optgroup>
				</select>
				<span id="upak">UPAK!</span>
				<span id="reupak">REUPAK!</span>
				<div class="details">
					<dl>
						<dt>&nbsp;</dt>
						<dd>&nbsp;</dd>
					</dl>
				</div>
			</div><!--sidebar-->


		</div><!--content-->

		<section id="forecast">
			<section class="daily">

			<ul>
				<li class="major">
					<h4>Later</h4>
					<?= $this->Html->image('cloudy4_night.png') ?>
					<p>Cloudy</p>
				</li>
				<li>
					<h4>Tomorrow </h4>
					<?= $this->Html->image('cloudy1.png') ?>
					<p>Partly Cloudy</p>
				</li>
				<li>
					<h4>Friday</h4>
					<?= $this->Html->image('shower2.png') ?>
					<p>Light Showers</p>
				</li>
				<li>
					<h4>Saturday</h4>
					<?= $this->Html->image('shower3.png') ?>
					<p>Rainy</p>
				</li>
				<li>
					<h4>Sunday</h4>
					<?= $this->Html->image('snow4.png') ?>
					<p>Snow?</p>
				</li>
				<li class="minor">
					<h4>Sunday</h4>
					<?= $this->Html->image('snow4.png') ?>
					<p>Asa</p>
				</li>
				<li class="minor">
					<h4>Sunday</h4>
					<?= $this->Html->image('snow4.png') ?>
					<p>brad.</p>
				</li>
			</ul>				
			</section>
		</section><!-- .outerPane -->
	</section><!-- #container -->

	<footer>
		<p>Weather is the state of the atmosphere, to the degree that it is hot or cold, wet or dry, calm or stormy, clear or cloudy.[1] Most weather phenomena occur in the troposphere,[2][3] just below the stratosphere. Weather refers, generally, to day-to-day temperature and precipitation activity, whereas climate is the term for the average atmospheric conditions over longer periods of time.[4] When used without qualification, "weather" is understood to be the weather of Earth.</p>
		<small>© 2012 Meteomedia</small>
            <div class="container_16">
                <div class="grid_8 left">
                    Powered by <a href="http://www.croogo.org">Croogo</a>.
                </div>
                <div class="grid_8 right">
                    <a href="http://www.cakephp.org"><?php echo $this->Html->image('/img/cake.power.gif'); ?></a>
                </div>
                <div class="clear"></div>
            </div>
	</footer>

</div><!-- #wrapper -->

    </body>
</html>