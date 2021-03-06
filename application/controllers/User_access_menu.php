<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_access_menu extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('User_access_menu_model');
        is_logged_in();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'user_access_menu/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'user_access_menu/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'user_access_menu/index.html';
            $config['first_url'] = base_url() . 'user_access_menu/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->User_access_menu_model->total_rows($q);
        $user_access_menu = $this->User_access_menu_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'user_access_menu_data' => $user_access_menu,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'content' => 'user_access_menu/user_access_menu_list',
            'judul' => 'user_access_menu',
        );
        $this->load->view('layout', $data);
    }

    public function read($id)
    {
        $row = $this->User_access_menu_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'role_id' => $row->role_id,
                'menu_id' => $row->menu_id,

                'content' => 'user_access_menu/user_access_menu_read',
                'judul' => 'user_access_menu',
            );
            $this->load->view('layout', $data);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  Data tidak ditemukan.
                </div>');
            redirect(site_url('user_access_menu'));
        }
    }

    public function create()
    {
        $data = array(
            'content' => 'user_access_menu/user_access_menu_form',
            'judul' => 'user_access_menu',
            'button' => 'Create',
            'action' => site_url('user_access_menu/create_action'),
            'id' => set_value('id'),
            'role_id' => set_value('role_id'),
            'menu_id' => set_value('menu_id'),

        );
        $this->load->view('layout', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'role_id' => $this->input->post('role_id', TRUE),
                'menu_id' => $this->input->post('menu_id', TRUE),
            );

            $this->User_access_menu_model->insert($data);
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  Data berhasil ditambah.
                </div>');
            redirect(site_url('user_access_menu'));
        }
    }

    public function update($id)
    {
        $row = $this->User_access_menu_model->get_by_id($id);

        if ($row) {
            $data = array(
                'content' => 'user_access_menu/user_access_menu_form',
                'judul' => 'user_access_menu',
                'button' => 'Update',
                'action' => site_url('user_access_menu/update_action'),
                'id' => set_value('id', $row->id),
                'role_id' => set_value('role_id', $row->role_id),
                'menu_id' => set_value('menu_id', $row->menu_id),
            );
            $this->load->view('layout', $data);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  Data tidak ditemukan.
                </div>');
            redirect(site_url('user_access_menu'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'role_id' => $this->input->post('role_id', TRUE),
                'menu_id' => $this->input->post('menu_id', TRUE),
            );

            $this->User_access_menu_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  Berhasil perbarui data.
                </div>');
            redirect(site_url('user_access_menu'));
        }
    }

    public function delete($id)
    {
        $row = $this->User_access_menu_model->get_by_id($id);

        if ($row) {
            $this->User_access_menu_model->delete($id);
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  Data berhasil dihapus.
                </div>');
            redirect(site_url('user_access_menu'));
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  Data tidak ditemukan
                </div>');
            redirect(site_url('user_access_menu'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('role_id', 'role id', 'trim|required');
        $this->form_validation->set_rules('menu_id', 'menu id', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file User_access_menu.php */
/* Location: ./application/controllers/User_access_menu.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2020-02-13 09:02:50 */
/* http://harviacode.com */
