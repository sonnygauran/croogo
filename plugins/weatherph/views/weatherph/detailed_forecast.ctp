<?php echo $this->Html->script('AnyChart.js'); ?>
<p>Detailed Forecast (MOS)</p>
<p>Temperature</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 654;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $stationID; ?>/temperature/3h');
        chart.write();
    //]]>
</script>
<p>Wind</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 654;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $stationID; ?>/wind/3h');
        chart.write();
    //]]>
</script>
<p>Wind Direction</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 654;
        chart.height = 50;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $stationID; ?>/winddir/6h');
        chart.write();
    //]]>
</script>
<p>Humidity</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 654;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $stationID; ?>/humidity/3h');
        chart.write();
    //]]>
</script>
<p>Precipitation</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 654;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $stationID; ?>/precipitation/6h');
        chart.write();
    //]]>
</script>
<p>Air Pressure</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 654;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $stationID; ?>/airpressure/6h');
        chart.write();
    //]]>
</script>
<p>Global Radiation</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 654;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $stationID; ?>/globalradiation/3h');
        chart.write();
    //]]>
</script>
<p>Sunshine</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 654;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedForecast/<?= $stationID; ?>/sunshine/1m');
        chart.write();
    //]]>
</script>
