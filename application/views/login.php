</div>
	<?php $this->load->view($this->config->item('ud_admin_folder').'/header'); ?>
	<?php //$this->load->view('header'); ?>
	<div class="login_form">
		<?php echo validation_errors(); ?>
		<?php echo form_open($this->config->item('ud_admin_folder').'/home/login'); ?>
			<h3>Username</h3>
			<?php echo form_input(array('name' => 'username', 'id' => 'username', 'size' => 50, 'class' => 'field' )); ?>
			<h3>Password</h3>
			<?php echo form_password(array('name' => 'password', 'id' => 'password', 'size' => 50, 'class' => 'field' )); ?>
			<div><input type="image" src="<?php echo base_url(); ?>img/login.png" alt="Login" id="submit" class="login" /></div>
		</form>
	</div>
</div>