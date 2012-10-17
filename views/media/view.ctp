<video height='240ppx' width='360px' controls>
    <? foreach (array('m4v' => 'video/x-m4v;', 'mp4' => 'video/mp4;', 'webm' => 'video/webm;') as $codec => $type) { ?>
        <source src="http://199.197.193.129:7777/<?= "{$video['name']}.{$codec}" ?>" type='<?= $type?>' />
    <? } ?>
</video>