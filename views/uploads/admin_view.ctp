<div class="attachments index">

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Attachment', true), array('action' => 'add')); ?></li>
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
                    <a href="/uploads/uploaded_images/<?= $file ?>" > /uploads/uploaded_images/<?= $file ?></a>
                </td>
                <td>
                    <?php echo $this->Html->link("delete",array(
                        'plugin' => null,
                        'controller' =>'uploads',
                        'action' => 'view',
                        $file
                    )); ?>
                </td>

            </tr>
        <?php } ?>
    </table>

</div>

