<div class="form">
    <?php echo $this->Form->create('Upload', array('type' => 'file')); ?>
    <?php echo $this->Form->input('video', array('type'=>'file', 'label' => false)); ?>
    <?php echo $this->Form->end('Upload');?>
</div>