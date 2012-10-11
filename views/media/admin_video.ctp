<div class="form">


    <?php echo $this->Form->create('Media', array('type' => 'file')); ?>
    <?php
    echo $this->Form->input('type', array(
        'options' => array('weathertv' => 'WeatherTV', 'uploaded_videos' => 'Blog'),
        'type' => 'radio'
    ));
	echo $this->Form->input('name', array(
		'label' => 'Description',
	));
    ?>

    <?php echo $this->Form->input('video', array('type' => 'file', 'label' => false)); ?>
    <?php echo $this->Form->end('Upload'); ?>
    
</div>
