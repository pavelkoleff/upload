<?php

class Admin_model extends CI_Model {

	function validate() {
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('password', md5($this->input->post('password')));
		$query = $this->db->get('users');
		
		if($query->num_rows == 1) {
			return true;
		}
	}
    
    function listfiles() {
        $query = $this->db->get('files'); 
        $this->db->distinct('urn');
        $files = $query->result();
        
        return $files;
    }
    
    function uploadfile() {
        $this->load->library('upload');
        $config['upload_path'] = '././userfiles/';
		$config['allowed_types'] = 'zip';
		$config['max_size']	= '5000';
		
        $this->upload->initialize($config);

		if(!$this->upload->do_upload("name")) {

		} else {
			$upload_data = $this->upload->data();
            
            $update['urn'] = 'ABC-'.microtime(true);
            $update['name'] = $upload_data['file_name'];
            $update['description'] = $this->input->post('description');
            $update['label'] = $this->input->post('label');
            $update['status_id'] = $this->input->post('status_id');
            $this->db->insert('files', $update);
            $this->saveNotifications($this->db->insert_id());
        }
    }
    
    function uploadfilerevision($urn) {
        $this->load->library('upload');
        $this->load->library('phpmailer');
        $config['upload_path'] = '././userfiles/';
		$config['allowed_types'] = 'zip';
		$config['max_size']	= '5000';
		
        $this->upload->initialize($config);

		if(!$this->upload->do_upload("name")) {

		} else {
			$upload_data = $this->upload->data();
            
            $file = $this->viewfile($urn);
            
            $setrevision['name'] = $file->name;
            $setrevision['file_id'] = $file->id;
            $setrevision['created_at'] = $file->created_at;
            $this->db->insert('filesrevision', $setrevision);
            
            $update['urn'] = $urn;
            $update['name'] = $upload_data['file_name'];
            $update['description'] = $this->input->post('description');
            $update['label'] = $this->input->post('label');
            $update['status_id'] = $this->input->post('status_id');
            
            $this->db->where('urn', $urn);
            $this->db->update('files', $update);
            
            $this->db->where('file_id', $file->id);
            $query = $this->db->get('notification');
            $notifications = $query->result();
            
            foreach($notifications as $notification) {
                $this->db->where('id', $notification->user_id);
                $query = $this->db->get('users');
                $user = $query->result();
            
                $message = "Dear Acme Inc. User,<br /><br />";
                $message .= "New file revision is uploaded.<br />";
                $message .= "File URN - ".$file->urn."<br />";
                $message .= "File Desccription - ".$file->description."<br />";
                $message .= "File labels - ".$file->label."<br />";
                $message .= "File status - ".$this->admin_model->getStatus($file->status_id);
                $this->admin_model->sendMail($user[0]->username, 'New file revision uploaded', $message, $receiver_name = "", $sender_name="", $from_email = "");
            }
        }
    }
    
    function viewfile($urn) {
        $this->db->where('urn', $urn);
		$query = $this->db->get('files');
        $files = $query->result();
        
        return $files[0];
    }
    
    function getFilerevisions($id) {
        $this->db->where('file_id', $id);
        $query = $this->db->get('filesrevision');
        $filerevisions = $query->result();
        
        return $filerevisions;
    }
    
    function getStatuses() {
        $query = $this->db->get('status');      
        $statuses = $query->result();
        
        $stat_array = array();
        $stat_array[null] = '';
        
        foreach($statuses as $status) {
            $stat_array[$status->id] = $status->status;
        }
        
        return $stat_array;
    }
    
    function filesearch($label) {
        $this->db->like('label', $label); 
        $query = $this->db->get('files');
        $files = $query->result();
        
        return $files;
    }
    
    function comment($urn) {
        $this->load->library('phpmailer');
        $file = $this->viewfile($urn);
        $update['file_id'] = $file->id;
        $update['comment'] = $this->input->post('comment');
        
        $this->db->where('username', $this->session->userdata('username'));
        $query = $this->db->get('users');
        $user = $query->result();
        
        $update['user_id'] = $user[0]->id;
        $this->db->insert('comments', $update);
        
        $this->db->where('file_id', $file->id);
        $query = $this->db->get('notification');
        $notifications = $query->result();
        
        foreach($notifications as $notification) {
            $this->db->where('id', $notification->user_id);
            $query = $this->db->get('users');
            $user = $query->result();
        
            $message = "Dear Acme Inc. User,<br /><br />";
            $message .= "New comment is added.<br />";
            $message .= "File URN - ".$file->urn."<br />";
            $message .= "File Desccription - ".$file->description."<br />";
            $message .= "File labels - ".$file->label."<br />";
            $message .= "File status - ".$this->admin_model->getStatus($file->status_id);
            $this->admin_model->sendMail($user[0]->username, 'New comment', $message, $receiver_name = "", $sender_name="", $from_email = "");
        }
    }
    
    function getUsername($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        $user = $query->result();
        
        return $user[0]->username;
    }
    
    function getComments($urn) {
        $file = $this->viewfile($urn);
        $this->db->where('file_id', $file->id);
		$query = $this->db->get('comments');
        $comments = $query->result();
        
        return $comments;
    }
    
    function getAllUsers() {
        $query = $this->db->get('users');
        $users = $query->result();
        
        return $users;
    }
    
    function saveNotifications($id) {
        $users = $this->getAllUsers();
        $this->db->where('id', $id);
		$query = $this->db->get('files');
        $file = $query->result();
        
        foreach($users as $user) {
        
            if($this->input->post('user_'.$user->id) != null && $this->input->post('user_'.$user->id) != '') {
                $update = array(
                   'user_id' => $this->input->post('user_'.$user->id),
                   'file_id' => $id,
                );
                
                $this->db->insert('notification', $update);
                
                $message = "Dear Acme Inc. User,<br /><br />";
                $message .= "New file is uploaded.<br />";
                $message .= "File URN - ".$file[0]->urn."<br />";
                $message .= "File Desccription - ".$file[0]->description."<br />";
                $message .= "File labels - ".$file[0]->label."<br />";
                $message .= "File status - ".$this->admin_model->getStatus($file[0]->status_id);
                $this->admin_model->sendMail($user->username, 'New file uploaded', $message, $receiver_name = "", $sender_name="", $from_email = "");
            }
        }
    }
    
    function getStatus($id) {
        $query = $this->db->get_where('status', array('id' => $id));
        $status = $query->result();
        
        return $status[0]->status;
    }
    
    function getNotificationUsers($file_id) {
        
    }
    
    function sendMail( $to_email, $subject, $message, $receiver_name = "", $sender_name="", $from_email = "")
    {    
        $msg="";
        $sent=false;
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host       = 'smtp.googlemail.com';
        $mail->Port       = 465;
        $mail->Username = 'email_username';
        $mail->Password = 'email_password';
        $mail->From       =  $from_email;
        $mail->FromName   =  $sender_name;
        $mail->Subject    =  strip_tags($subject);
        $mail->Body  = $message;
        $mail->MsgHTML(nl2br($message));
        $mail->AddAddress($to_email, $receiver_name);
        
        $mail->SMTPDebug = 0;

        if(!$mail->Send()) {
            $msg = "Mailer Error: " .$mail->ErrorInfo;
            $sent=false;
        } else {
            $msg = "Message sent!";
            $sent=true;
        }
         
        return ($sent);
    }
}