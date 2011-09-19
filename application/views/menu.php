
<div class="logout">
	[ <?php echo anchor($this->config->item('ud_admin_folder').'/home/logout', 'Log out'); ?> ] 
	<?php echo anchor($this->config->item('ud_admin_folder').'/home/logout', img(array('src' => 'img/logout.png', 'height' => '25'))); ?>
</div>
<?php echo br(3); ?>
<?php echo anchor('/home/upload', 'Upload File'); ?>
<?php echo br(); ?>
<?php echo anchor('/home/filelist', 'Files List'); ?>
<?php echo br(); ?>