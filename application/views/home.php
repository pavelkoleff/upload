<?php $this->load->view('/header'); ?>
<div class="admin_menu">
   <?php $this->load->view('/menu'); ?>
</div>
<div class="admin_content">
    <h1>Acme Inc. User File System</h1>
    <?php echo validation_errors(); ?>
    <?php $this->load->view('/upload'); ?>
</div>
<?php $this->load->view('/footer'); ?>
