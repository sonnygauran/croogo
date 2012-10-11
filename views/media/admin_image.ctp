<style>
    div.form{
        font-size: 16px;
    }

    div.form > pre{
        background-color: #eee; 
        margin-top: 20px; 
        padding: 20px 10px; 
        letter-spacing: -1px
    }
</style>
<div class="form">

    <?php echo $this->Form->create('Media', array('type' => 'file')); ?>
	<?php echo $this->Form->input('description'); ?>
    <?php echo $this->Form->input('image', array('type' => 'file', 'label' => false)); ?>
    <?php echo $this->Form->end('Media'); ?>
    <?php if (isset($image)): ?>
        <pre><?php echo htmlentities('<img src="' . $image . '" alt="" />') ?></pre>
    <?php endif; ?>
</div>

