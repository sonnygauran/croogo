<div class="form">


    <?php echo $this->Form->create('Upload', array('type' => 'file')); ?>
    <?php
    echo $this->Form->input('field', array(
        'options' => array('weathertv' => 'WeatherTV', 'uploaded_videos' => 'Blog'),
        'type' => 'radio'
    ));
    ?>

    <?php echo $this->Form->input('video', array('type' => 'file', 'label' => false)); ?>
    <?php echo $this->Form->end('Upload'); ?>
    <br />
    <h2>WeatherTV Videos</h2>
    <table>
        <tr>
            <th>File</th>
            <th>Url</th>
            <th>Action</th>
        </tr>
        <?php foreach ($files as $file) { ?>
            <tr>
                <td>
                    <?= $file ?>
                </td>
                <td>
                    <a href="/uploads/weathertv/<?= $file ?>" > /uploads/weatherTv/<?= $file ?></a>
                </td>
                <td>
                    <?php
                    echo $this->Html->link("delete", array(
                        'plugin' => null,
                        'controller' => 'uploads',
                        'action' => 'video',
                        $file
                    ));
                    ?>
                </td>

            </tr>
<?php } ?>
    </table>
</div>