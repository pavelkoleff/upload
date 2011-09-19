<?php $this->load->view('/header'); ?>
<div class="admin_menu">
   <?php $this->load->view('/menu'); ?>
</div>
<div class="admin_content">
    <h1>Acme Inc. User File System</h1>
    <?php echo validation_errors(); ?>
    <h3>Upload File</h3>
    <br />
    <div class="edit_form">
        <?php echo form_open_multipart('/home/uploaded'); ?>
            <table>
                <tr>
                    <td class="labels">File</td>
                    <td class="form_fields"><?php echo form_upload(array('name' => 'name', 'value' => set_value('name'), 'id' => 'name', 'size' => 50, 'class' => 'fieldin' )); ?></td>
                </tr>
                <tr>
                    <td class="labels">&nbsp;</td>
					<td class="form_fields" style="font-size:12px;font-style: italic; color:#82ae18;">(Allowed file types: ZIP)</td>
				</tr>
                <tr>
                    <td class="labels">Description</td>
                    <td class="form_fields"><?php echo form_input(array('name' => 'description', 'value' => set_value('description'), 'id' => 'description', 'size' => 50, 'class' => 'fieldin' )); ?></td>
                </tr>
                <tr>
                    <td class="labels">Labels</td>
                    <td class="form_fields"><?php echo form_input(array('name' => 'label', 'value' => set_value('label'), 'id' => 'label', 'size' => 50, 'class' => 'fieldin' )); ?></td>
                </tr>
                <tr>
                    <td class="labels">&nbsp;</td>
					<td class="form_fields" style="font-size:12px;font-style: italic; color:#82ae18;">(Please enter labels separated by comma.)</td>
				</tr>
                <tr>
                    <td class="labels">Status</td>
                    <td class="form_fields"><?php echo form_dropdown('status_id', $status_options, '', 'class = "dropbox"'); ?></td>
                </tr>
                <tr>
                    <td class="labels">Notifications</td>
                    <td class="form_fields">
                        <?php foreach($users as $user): ?>
                            <?php echo form_checkbox('user_'.$user->id, $user->id, FALSE); ?> <?php echo $user->username; ?><br />
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
            </table>
            <div><input type="image" src="<?php echo base_url(); ?>img/submit.png" alt="Login" id="submit" class="login" /><?php //echo form_submit('submit', 'Submit'); ?></div>
        </form>
    </div>
</div>
<?php $this->load->view('/footer'); ?>

