<div class="node-info">
<?php
	$type = $types_for_layout[$this->Layout->node('type')];
/*
	if ($type['Type']['format_show_author'] || $type['Type']['format_show_date']) {
		__('Posted');
	}
	if ($type['Type']['format_show_author']) {
		echo ' ' . __('by', true) . ' ';
		if ($this->Layout->node('User.website') != null) {
			$author = $this->Html->link($this->Layout->node('User.name'), $this->Layout->node('User.website'));
		} else {
			$author = $this->Layout->node('User.name');
		}
		echo $this->Html->tag('span', $author, array(
			'class' => 'author',
		));
	}
 * 
 */
	if ($type['Type']['format_show_date']) {
		//echo ' ' . __('on', true) . ' ';
		//echo $this->Html->tag('span', $this->Time->format(Configure::read('Reading.date_time_format'), $this->Layout->node('created'), null, Configure::read('Site.timezone')), array('class' => 'date'));
        $createdTime = strtotime($this->Layout->node('created'));
            ?>
    <div class="date">
        <div class="day"><?= date('d', $createdTime) ?></div>
        <div class="month"><?= date('M', $createdTime) ?></div>
        <div class="year"><?= date('Y', $createdTime) ?></div>
    </div>
             <?php
	}
        
?>
</div>