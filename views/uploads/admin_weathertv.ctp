<div class="attachments index">

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('Upload Video', true), array('action' => 'video')); ?></li>
        </ul>
    </div>

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
                    <?php echo $this->Html->link("delete",array(
                        'plugin' => null,
                        'controller' =>'uploads',
                        'action' => 'weathertv',
                        $file
                    )); ?>
                </td>

            </tr>
        <?php } ?>
    </table>

</div>

