<?php
echo $this->Html->script('AnyChart.js');

?>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/069069/wind/3h');
        chart.write();
    //]]>
</script>

<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/069069/temperature/3h');
        chart.write();
    //]]>
</script>

<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/069069/humidity/3h');
        chart.write();
    //]]>
</script>

<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/069069/precipitation/6h');
        chart.write();
    //]]>
</script>

<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/069069/airpressure/3h');
        chart.write();
    //]]>
</script>

<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedForecast/069069/globalradiation/3h');
        chart.write();
    //]]>
</script>
