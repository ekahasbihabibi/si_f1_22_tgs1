<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('tb_mahasiswa', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('layouts/header_dashboard', $data);
        $this->load->view('layouts/sidebar_dashboard', $data);
        $this->load->view('user/dashboard', $data);
        $this->load->view('layouts/footer_dashboard');
    }

}
