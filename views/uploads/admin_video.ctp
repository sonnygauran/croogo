<div class="form">
   
    
    <?php echo $this->Form->create('Upload', array('type' => 'file')); ?>
     <?php 
        echo $this->Form->input('field', array(
            'options' => array('weathertv' => 'WeatherTV', 'uploaded_videos' => 'Blog'),
            'type' => 'radio'
        )); 
     ?>
   
    <?php echo $this->Form->input('video', array('type'=>'file', 'label' => false)); ?>
    <?php echo $this->Form->end('Upload');?>
</div>