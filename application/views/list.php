<?php $this->load->view('/header'); ?>
<div class="admin_menu">
   <?php $this->load->view('/menu'); ?>
</div>
<div class="admin_content">
    <h1>Acme Inc. User File System</h1>
    <table width="600">
        <tr>
            <td><b>ID</b></td>
            <td><b>File Name</b></td>
            <td><b>File URN</b></td>
            <td><b>File Status</b></td>
            <td><b>Manage</b></td>
        </tr>
    <?php foreach($files as $file): ?>
            <tr>
                <td><?php echo $file->id; ?></td>
                <td><?php echo $file->name; ?></td>
                <td><?php echo $file->urn; ?></td>
                <td><?php echo $this->admin_model->getStatus($file->status_id); ?></td>
                <td><?php echo anchor('/home/manage/'.$file->urn,  'manage'); ?></td>
            </tr>
    <?php endforeach; ?>
    </table>
</div>
<?php $this->load->view('/footer'); ?>