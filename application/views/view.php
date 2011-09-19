<?php $this->load->view('/header'); ?>
<div class="admin_menu">
   <?php $this->load->view('/menu'); ?>
</div>
<div class="admin_content">
    <h1>Acme Inc. Manage File - <?php echo $urn; ?></h1>
    <br />
    <b>File Name</b> - <?php echo $files->name; ?><br />
    <b>File URN</b> - <?php echo $files->urn; ?><br />
    <b>File Description</b> - <?php echo $files->description; ?><br />
    <b>File Labels</b> - <?php echo $labels; ?><br />
    <b>File Timestamp</b> - <?php echo $files->created_at; ?><br />
    <b>File Status</b> - <?php echo $this->admin_model->getStatus($files->status_id); ?><br />
    <b><?php echo anchor(base_url().'/userfiles/'.$files->name,  'Download File'); ?></b><br />
    <?php echo anchor('/home/uploadrevision/'.$files->urn,  'Upload New Revision'); ?>
    <h4>File revisions</h4>
    <table width="600">
        <tr>
            <td><b>File Name</b></td>
            <td><b>File Version</b></td>
            <td><b>Download</b></td>
        </tr>
    <?php foreach($filerevisions as $file): ?>
            <tr>
                <td><?php echo $file->name; ?></td>
                <td><?php echo $file->created_at; ?></td>
                <td><?php echo anchor(base_url().'/userfiles/'.$file->name,  'Download File'); ?></td>
            </tr>
    <?php endforeach; ?>
    </table>
    <br />
    <br />
    <br />
    <h4>User Comments</h4>
    <?php foreach($comments as $comment): ?>
        <b><?php echo $this->admin_model->getUsername($comment->user_id); ?></b> - <?php echo $comment->comment; ?><br /><br />
    <?php endforeach; ?>
    <?php echo form_open_multipart('/home/comment/'.$files->urn); ?>
        <table>
            <tr>
                <td class="labels">Comment</td>
                <td class="form_fields"><?php echo form_textarea(array('name' => 'comment', 'value' => set_value('comment'), 'id' => 'description', 'size' => 50, 'class' => 'fieldin' )); ?></td>
            </tr>
        </table>
        <div><input type="image" src="<?php echo base_url(); ?>img/submit.png" alt="Login" id="submit" class="login" /><?php //echo form_submit('submit', 'Submit'); ?></div>
    </form>
</div>
<?php $this->load->view('/footer'); ?>