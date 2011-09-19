<?php $this->load->view('/header'); ?>
<div class="admin_menu">
   <?php $this->load->view('/menu'); ?>
</div>
<div class="admin_content">
    <h1>Acme Inc. Upload Revision to File <?php echo $file->urn; ?></h1>
    <?php echo validation_errors(); ?>
    <h3>Upload File</h3>
    <br />
    <div class="edit_form">
        <?php echo form_open_multipart('/home/uploadedrevision/'.$file->urn); ?>
            <table>
                <tr>
                    <td class="labels">File</td>
                    <td class="form_fields"><?php echo form_upload(array('name' => 'name', 'value' => $file->name, 'id' => 'name', 'size' => 50, 'class' => 'fieldin' )); ?></td>
                </tr>
                <tr>
                    <td class="labels">Description</td>
                    <td class="form_fields"><?php echo form_input(array('name' => 'description', 'value' => $file->description, 'id' => 'description', 'size' => 50, 'class' => 'fieldin' )); ?></td>
                </tr>
                <tr>
                    <td class="labels">Labels</td>
                    <td class="form_fields"><?php echo form_input(array('name' => 'label', 'value' => $file->label, 'id' => 'label', 'size' => 50, 'class' => 'fieldin' )); ?></td>
                </tr>
                <tr>
                    <td class="labels">Status</td>
                    <td class="form_fields"><?php echo form_dropdown('status_id', $status_options, $file->status_id, 'class = "dropbox"'); ?></td>
                </tr>
            </table>
            <div><input type="image" src="<?php echo base_url(); ?>img/submit.png" alt="Login" id="submit" class="login" /><?php //echo form_submit('submit', 'Submit'); ?></div>
        </form>
    </div>
</div>
<?php $this->load->view('/footer'); ?>

