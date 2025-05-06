<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');

	}
	public function index()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		if ($this->form_validation->run() == false) {
			$data['title'] = ' Login Page';
            $this->load->view('layouts/header_login', $data);
			$this->load->view('login/form_login', $data);
            $this->load->view('layouts/footer_login', $data);
		} else {
			// validasinya success
			$this->_login();
		}
	}
	private function _login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$user = $this->db->get_where('tb_mahasiswa', ['email' => $email])->row_array();
		// jika usernya ada
		if ($user) {
			// jika usernya aktif
			if ($user['is_active'] == 1) {
				// cek password
				if (password_verify($password, $user['password'])) {
					$data = [
						'logged' => TRUE,
						'email' => $user['email'],
						'npm' => $user['npm'],
						'name' => $user['name'],
						'image' => $user['image'],
						'role_id' => $user['role_id']
					];
					$this->session->set_userdata($data);
					if ($user['role_id'] == 2) {
						redirect('user');
					} else {
						redirect('admin');
					}
				} else {
					$this->session->set_flashdata('gagal_login', 'Password Salah');
					redirect('login');
				}
			} else {
				$this->session->set_flashdata('gagal_login', 'Akun Anda Di nonaktifkan');
				redirect('login');
			}
		} else {
			$this->session->set_flashdata('gagal_login', 'Email Anda Tidak Terdaftar');
			redirect('login');
		}
	}
	public function registration()
	{
		$this->form_validation->set_rules('name', 'name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[tb_mahasiswa.email]', [
			'is_unique' => 'This email has already registered!'
		]);
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
			'matches' => 'Password dont match!',
			'min_length' => 'Password too short!'
		]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'User Registration';
            $this->load->view('layouts/header_login', $data);
			$this->load->view('login/form_pendaftaran', $data);
            $this->load->view('layouts/footer_login', $data);
		} else {
			$email = $this->input->post('email', true);
			$data = [
				'name' => htmlspecialchars($this->input->post('name', true)),
				'image' => 'default.svg',
				'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'email' => $this->input->post('email'),
                'npm' => $this->input->post('npm'),
				'role_id' => 2,
				'is_active' => 1,
				'date_created' => date('Y-m-d H:i:s')

			];
			$token = base64_encode(random_bytes(32));
			$user_token = [
				'email' => $email,
				'token' => $token,
				'date_created' => date('Y-m-d H:i:s')

			];
			$this->db->insert('tb_mahasiswa', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success">
            <strong>Success!</strong> Akun anda sudah aktif.
          </div>');
			redirect('login');
		}
	}
	
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
	public function blocked()
	{
		$this->load->view('auth/blocked');
	}
	public function forgotPassword()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'Forgot Password';
			$this->load->view('auth/f_password', $data);
		} else {
			$email = $this->input->post('email');
			$user = $this->db->get_where('occ_user', ['email' => $email, 'is_active' => 1])->row_array();
			$nomor_hp = $user['whatsapp'];
			if ($user) {
				$token = base64_encode(random_bytes(32));
				$user_token = [
					'email' => $email,
					'token' => $token,
					'date_created' => time()
				];
				$this->db->insert('occ_user_token', $user_token);
			
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Please check your email to reset your password!</div>');
				redirect('login');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not registered or activated!</div>');
				redirect('login');
			}
		}
	}
	public function resetPassword()
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');
		$user = $this->db->get_where('occ_user', ['email' => $email])->row_array();
		if ($user) {
			$user_token = $this->db->get_where('occ_user_token', ['token' => $token])->row_array();
			if ($user_token) {
				$this->session->set_userdata('reset_email', $email);
				$this->changePassword();
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong token.</div>');
				redirect('login');
			}
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong email.</div>');
			redirect('login');
		}
	}
	public function changePassword()
	{
		$this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[3]|matches[password2]');
		$this->form_validation->set_rules('password2', 'Repeat Password', 'trim|required|min_length[3]|matches[password1]');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'Change Password';
			$this->load->view('auth/c_password', $data);
		} else {
			$password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
			$email = $this->session->userdata('reset_email');
			$this->db->set('password', $password);
			$this->db->where('email', $email);
			$this->db->update('occ_user');
			$this->session->unset_userdata('reset_email');
			$this->db->delete('occ_user_token', ['email' => $email]);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password has been changed! Please login.</div>');
			redirect('login');
		}
	}
}
