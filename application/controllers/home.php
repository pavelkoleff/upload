<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('form_validation', 'session'));
    }
    
	public function index() {   
        if($this->session->userdata('logged_in')) {
            $this->load->model('admin_model');
            $data['files'] = $this->admin_model->listfiles();
            $this->load->view('/list', $data);
        } else {
            $this->load->view('/login');
        }
	}
    
    public function loginform() {
		$this->load->view('/login');
	}
    
    public function login() {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if($this->session->userdata('logged_in')) {
            $this->index();
        } else {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('/login');
            } else {
                    $this->load->model('admin_model');
                    $query = $this->admin_model->validate();
                    
                    if($query) {
                        $data = array(
                            'username' => $this->input->post('username'),
                            'logged_in' => true
                        );
                        $this->session->set_userdata($data);
                        $this->index();
                    } else {
                        $this->loginform();
                }
            }
        }
    }
    
    function upload() {
        $this->load->model('admin_model');
        $data['users'] = $this->admin_model->getAllUsers();
        $data['status_options'] = $this->admin_model->getStatuses();
        $this->load->view('/upload', $data);
    }
    
    function uploaded() {
        $this->load->model('admin_model');
        $this->load->library('phpmailer');
        $this->admin_model->uploadfile();
        $data['files'] = $this->admin_model->listfiles();
        $this->load->view('list', $data);
    }
    
    function filelist() {
        $this->load->model('admin_model');
        $data['files'] = $this->admin_model->listfiles();
        $this->load->view('list', $data);
    }
    
    function manage($urn) {
        $this->load->model('admin_model');
        $data['files'] = $this->admin_model->viewfile($urn);
        $data['comments'] = $this->admin_model->getComments($urn);
        $labels = $data['files']->label;
        $labels_array = explode(',', $labels);
        $label_list = '';
        foreach($labels_array as $label) {
            $label_list .= anchor('/home/filesearch/'.$label,  $label).'&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        $data['labels'] = $label_list;
        $data['filerevisions'] = $this->admin_model->getFilerevisions($data['files']->id);
        $data['urn'] = $urn;
        $this->load->view('view', $data);
    }
    
    function filesearch($label) {
        $this->load->model('admin_model');
        $data['files'] = $this->admin_model->filesearch($label);
        $this->load->view('list', $data);
    }
    
    function uploadrevision($urn) {
        $this->load->model('admin_model');
        $data['file'] = $this->admin_model->viewfile($urn);
        $data['status_options'] = $this->admin_model->getStatuses();
        $this->load->view('uploadrevision', $data);
    }
    
    function uploadedrevision($urn) {
        $this->load->model('admin_model');
        $this->admin_model->uploadfilerevision($urn);
        $data['files'] = $this->admin_model->listfiles();
        $this->load->view('list', $data);
    }
    
    function comment($urn) {
        $this->load->model('admin_model');
        $this->admin_model->comment($urn);
        $data['files'] = $this->admin_model->viewfile($urn);
        $data['comments'] = $this->admin_model->getComments($urn);
        $data['filerevisions'] = $this->admin_model->getFilerevisions($data['files']->id);
        $labels = $data['files']->label;
        $labels_array = explode(',', $labels);
        $label_list = '';
        foreach($labels_array as $label) {
            $label_list .= anchor('/home/filesearch/'.$label,  $label).'&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        $data['labels'] = $label_list;
        $data['urn'] = $urn;
        $this->load->view('view', $data);
    }
    
    function logout() {
        $this->session->sess_destroy();  
        $this->loginform();  
    }
}