<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php 
    echo $this->Html->css(array(
            'forecast',
        ));
    echo $this->Layout->js();
    echo $this->Html->script(array(
        'jquery/jquery.min',
    ));
?>
</head>
<body>

<?php

echo $this->Layout->sessionFlash();
echo $content_for_layout;

?>

</body>
</html>