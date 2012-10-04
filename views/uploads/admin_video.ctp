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
            <th>Video</th>
            <th>Url</th>
            <th>Action</th>
        </tr>
        
        <?php foreach ($files as $file) { ?>
            <tr>
                <td>
                    <?= $file ?>
                    
                </td>
                <td>
                    <video height='250' width='250'controls>
                        <source src="http://199.195.193.129:7777/<?= $file ?>.m4v" type="video/x-m4v"/>
                        <source src="http://199.195.193.129:7777/<?= $file ?>.mp4" type="video/mp4"/>
                        <source src="http://199.195.193.129:7777/<?= $file ?>.webm" type="video/webm"/>
                    </video>
                </td>
                <td>
                     
    <?= htmlentities("<video controls>") . "\n" ?><br/>
        <?= htmlentities("<source src='")?>http://199.195.193.129:7777/<?= $file ?> <?= htmlentities(".m4v' type='video/x-m4v;' />") . "\n"?>
       <br/> <?= htmlentities("<source src='")?>http://199.195.193.129:7777/<?= $file ?> <?= htmlentities(".mp4' type='video/m4v;' />") . "\n"?>
        <br/><?= htmlentities("<source src='")?>http://199.195.193.129:7777/<?= $file ?> <?= htmlentities(".webm' type='video/x-webm;' />") . "\n"?>
       <br/>
    <?= htmlentities("</video>")?>

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