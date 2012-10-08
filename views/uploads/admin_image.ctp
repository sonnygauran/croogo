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

    <?php echo $this->Form->create('Upload', array('type' => 'file')); ?>
    <?php echo $this->Form->input('image', array('type' => 'file', 'label' => false)); ?>
    <?php echo $this->Form->end('Upload'); ?>
    <?php if (isset($image)): ?>
        <pre><?php echo htmlentities('<img src="' . $image . '" alt="" />') ?></pre>
    <?php endif; ?>
</div>

<table>
    <tr>
        <th>File</th>
        <th>Image</th>
        <th>Url</th>
        <th>Action</th>
    </tr>
    <?php foreach ($files as $file) { ?>
        <tr>
            <td>
                <?= $file ?>
            </td>
            <td>
                <a href="/uploads/uploaded_images/<?= $file ?>" > <img src="/uploads/uploaded_images/<?= $file ?>" width="75" length="75" alt="" /></a>
            </td>
            <td>
               <?= htmlspecialchars('<img src="/uploads/uploaded_images/')?><?=$file?><?= htmlspecialchars('" alt=" " />')?>
                   
                
            </td>
            <td>
                <?php
                echo $this->Html->link("delete", array(
                    'plugin' => null,
                    'controller' => 'uploads',
                    'action' => 'image',
                    $file
                ));
                ?>
            </td>

        </tr>
<?php } ?>
</table>