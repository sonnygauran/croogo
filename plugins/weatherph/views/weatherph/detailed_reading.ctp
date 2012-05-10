<?php
echo $this->Html->script('AnyChart.js');

?>

<center>
<p> Detailed Reading </p>
<br>

<p> WIND </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedReading/067710/wind/3h/20120507/20120508');
        chart.write();
    //]]>
</script>


<p> TEMPERATURE </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedReading/067710/temperature/3h/20120507');
        chart.write();
    //]]>
</script>


<p> HUMIDITY </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedReading/067710/humidity/3h/20120507');
        chart.write();
    //]]>
</script>

<p> PRECIPITATION </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedReading/067710/precipitation/6h/20120507');
        chart.write();
    //]]>
</script>


<p> AIR PRESSURE </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedReading/067710/airpressure/3h/20120507');
        chart.write();
    //]]>
</script>


<p> GLOBAL RADIATION </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('http://weatherph/swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('http://weatherph/getDetailedReading/067710/globalradiation/3h/20120507');
        chart.write();
    //]]>
</script>

</center>