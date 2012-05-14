<?php echo $this->Html->script('AnyChart.js'); ?>

<p>Temperature</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $stationID; ?>/temperature/3h');
        chart.write();
    //]]>
</script>
<p>Wind</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $stationID; ?>/wind/3h');
        chart.write();
    //]]>
</script>
<p>Wind Direction</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 50;
        chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $stationID; ?>/winddir/6h');
        chart.write();
    //]]>
</script>
<p>Humidity</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $stationID; ?>/humidity/3h');
        chart.write();
    //]]>
</script>
<p>Precipitation</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $stationID; ?>/precipitation/6h');
        chart.write();
    //]]>
</script>
<p>Air Pressure</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $stationID; ?>/airpressure/6h');
        chart.write();
    //]]>
</script>
<p>Global Radiation</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $stationID; ?>/globalradiation/3h');
        chart.write();
    //]]>
</script>
<p>Sunshine</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/<?= $stationID; ?>/sunshine/1m');
        chart.write();
    //]]>
</script>
