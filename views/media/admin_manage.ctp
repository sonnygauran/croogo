<div class="weathertv">
	<h1>Weather TV</h1>
	<table>
		<tr>
			<th>Preview</th>
			<th>Description</th>
			<th>Created</th>
			<th>Actions</th>
		</tr>
		<?php foreach($weathertv_videos as $video){ ?>
			<tr>
				<td>
					<video height='240ppx' width='360px' controls>
						<? foreach(array('m4v' => 'video/x-m4v;', 'mp4' => 'video/mp4;', 'webm' => 'video/webm;') as $codec => $type) { ?>
							<source src="http://199.197.193.129:7777/<?= "{$video['Media']['name']}.{$codec}" ?>" type='<?= $type?>;' />
						<? }?>
					</video>
				</td>
				<td><?= $video['Media']['description'] ?></td>
				<td><?= $video['Media']['created'] ?></td>
				<td>
					<?= $this->Html->link(__('Delete', true), array(
						'controller' => 'media',
						'action' => 'delete',
						$video['Media']['id'],
						'token' => $this->params['_Token']['key'],
					), null, __('Are you sure?', true)) ?>
				</td>
			</tr>
		<?php }?>
	</table>
</div>


<div class="blog">
	<h1>Blog Videos</h1>
	<table>
		<tr>
			<th>Preview</th>
			<th>Description</th>
			<th>Created</th>
			<th>Actions</th>
		</tr>
		<?php foreach($blog_videos as $video){ ?>
			<tr>
				<td>
					<video height='240ppx' width='360px' controls>
						<? foreach(array('m4v', 'mp4', 'webm') as $codec){ ?>
							<source src="http://199.195.193.129:7777/<?= "{$video['Media']['name']}.{$codec}" ?>" type='video/x-m4v;' />
						<? }?>
					</video>
				</td>
				<td><?= $video['Media']['description'] ?></td>
				<td><?= $video['Media']['created'] ?></td>
				<td>
					<?= $this->Html->link(__('Delete', true), array(
						'controller' => 'media',
						'action' => 'delete',
						$video['Media']['id'],
						'token' => $this->params['_Token']['key'],
					), null, __('Are you sure?', true)) ?>
				</td>
			</tr>
		<?php }?>
	</table>
</div>


<div class="images">
	<h1>Uploaded Images</h1>
	<table>
		<tr>
			<th>Preview</th>
			<th>Description</th>
			<th>Created</th>
			<th>Actions</th>
		</tr>
		<?php foreach($images as $image){ ?>
			<tr>
				<td>
					<img src="/uploads/uploaded_images/<?= $image['Media']['name']?>" width="360px">
				</td>
				<td><?= $image['Media']['description'] ?></td>
				<td><?= $image['Media']['created'] ?></td>
				<td>
					<?= $this->Html->link(__('Delete', true), array(
						'controller' => 'media',
						'action' => 'delete',
						$image['Media']['id'],
						'token' => $this->params['_Token']['key'],
					), null, __('Are you sure?', true)) ?>
				</td>
			</tr>
		<?php }?>
	</table>
</div>
