<?php echo $this->Html->script('AnyChart.js'); ?>

<center>
<p> Detailed Reading </p>
<br>

<p> WIND </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedReading/<?= $set['stationID']; ?>/wind/3h/<?= $set['startDate']; ?>/<?= $set['endDate']; ?>');
        chart.write();
    //]]>
</script>


<p> TEMPERATURE </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedReading/<?= $set['stationID']; ?>/temperature/3h/<?= $set['startDate']; ?>/<?= $set['endDate']; ?>');
        chart.write();
    //]]>
</script>


<p> HUMIDITY </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedReading/<?= $set['stationID']; ?>/humidity/3h/<?= $set['startDate']; ?>/<?= $set['endDate']; ?>');
        chart.write();
    //]]>
</script>

<p> PRECIPITATION </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedReading/<?= $set['stationID']; ?>/precipitation/6h/<?= $set['startDate']; ?>/<?= $set['endDate']; ?>');
        chart.write();
    //]]>
</script>


<p> AIR PRESSURE </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedReading/<?= $set['stationID']; ?>/airpressure/3h/<?= $set['startDate']; ?>/<?= $set['endDate']; ?>');
        chart.write();
    //]]>
</script>


<p> GLOBAL RADIATION </p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedReading/<?= $set['stationID']; ?>/globalradiation/3h/<?= $set['startDate']; ?>/<?= $set['endDate']; ?>');
        chart.write();
    //]]>
</script>

<p>SUNSHINE</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 150;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedReading/<?= $set['stationID']; ?>/sunshine/1m/<?= $set['startDate']; ?>/<?= $set['endDate']; ?>');
        chart.write();
    //]]>
</script>

<p>WIND DIRECTION</p>
<script type="text/javascript" language="javascript">
    //<![CDATA[
        var chart = new AnyChart('<?= $this->webroot ?>swf/AnyChart.swf');
        chart.width = 554;
        chart.height = 50;
        chart.setXMLFile('<?= $this->webroot ?>getDetailedReading/<?= $set['stationID']; ?>/winddir/6h/<?= $set['startDate']; ?>/<?= $set['endDate']; ?>');
        chart.write();
    //]]>
</script>

</center>