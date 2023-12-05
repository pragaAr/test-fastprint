<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Jakarta');

class Product extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('ProductModel', 'Product');
	}

	public function index()
	{
		$data['title'] = 'Produk';

		$this->load->view('pages/product', $data);
	}

	public function get()
	{
		$jam = date('H');
		$date = date('dmy');
		$dashdate = date('d-m-y');

		$url = 'https://recruitment.fastprint.co.id/tes/api_tes_programmer';

		$postData = array(
			'username' => 'tesprogrammer' . $date . "C" . ($jam + 1), //username akan berubah2 mengikuti waktu server
			'password' => md5('bisacoding-' . $dashdate)
		);

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		$result = curl_exec($ch);

		curl_close($ch);

		$request = json_decode($result, true);
		$res = $request['data'];

		echo json_encode($res);
	}

	function initialize()
	{
		$jam = date('H');
		$date = date('dmy');
		$dashdate = date('d-m-y');

		$url = 'https://recruitment.fastprint.co.id/tes/api_tes_programmer';

		$postData = array(
			'username' => 'tesprogrammer' . $date . "C" . ($jam + 1), //username akan berubah2 mengikuti waktu server
			'password' => md5('bisacoding-' . $dashdate)
		);

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		$result = curl_exec($ch);

		curl_close($ch);

		$request = json_decode($result, true);
		$res = $request['data'];

		if ($res) {
			foreach ($res as $item) {
				// Cek apakah data sudah ada dalam database
				$existing_data = $this->Product->getProductId($item);

				if (!$existing_data) {
					// Insert kategori
					$kategori_id = $this->getOrCreateKategori($item['kategori']);

					// Insert status
					$status_id = $this->getOrCreateStatus($item['status']);

					// Insert produk
					$dataProduct = array(
						'id_produk' => $item['id_produk'],
						'nama_produk' => $item['nama_produk'],
						'harga' => $item['harga'],
						'kategori_id' => $kategori_id,
						'status_id' => $status_id
					);

					$this->Product->addProduct($dataProduct);
				}
			}

			$response = [
				'status' => 200,
				'message' => 'Data dari api sudah disimpan ke database',
				'data' => $res
			];
		} else {
			$response = [
				'status' => 500,
				'message' => 'Data dari api gagal di ambil'
			];
		}

		echo json_encode($response);
	}

	private function getOrCreateKategori($nama_kategori)
	{
		// Cek kategori di database
		$result = $this->Product->getKategori($nama_kategori);

		// Jika tidak ada, maka insert
		if (!$result) {
			return $this->Product->addKategori($nama_kategori);
		}

		return $result['id_kategori'];
	}

	private function getOrCreateStatus($nama_status)
	{
		// Cek status di database
		$result = $this->Product->getStatus($nama_status);

		// Jika tidak ada, maka insert
		if (!$result) {
			return $this->Product->addStatus($nama_status);
		}

		return $result['id_status'];
	}

	// Get poroduk data dari database
	public function getProduct()
	{
		header('Content-Type: application/json');
		$data = $this->Product->getDataProduct();

		echo json_encode($data);
	}

	public function add()
	{
		$data['title'] = 'Tambah Produk';

		$data['status'] = $this->Product->getDataStatus();
		$data['kategori'] = $this->Product->getDataKategori();

		// $this->form_validation->set_rules('id', 'Id Produk', 'required|numeric', array(
		// 	'required' => 'Id produk harus diisi',
		// 	'numeric' => 'Hanya angka yang diperbolehkan'
		// ));
		$this->form_validation->set_rules('nama', 'Nama Produk', 'required', array(
			'required' => 'Nama produk harus diisi'
		));
		$this->form_validation->set_rules('harga', 'Harga', 'required|numeric', array(
			'required' => 'Harga produk harus diisi',
			'numeric' => 'Hanya angka yang diperbolehkan'
		));
		$this->form_validation->set_rules('kategori', 'Kategori', 'required', array(
			'required' => 'Kategori produk harus diisi'
		));
		$this->form_validation->set_rules('status', 'Status', 'required', array(
			'required' => 'Status produk harus diisi'
		));

		if ($this->form_validation->run() == false) {
			$this->load->view('pages/add-product', $data);
		} else {
			$this->Product->addNewProduct();
			$this->session->set_flashdata('flash', "Data produk berhasil ditambah");

			redirect('product');
		}
	}

	public function edit($id)
	{
		$data['title'] = 'Edit Produk';

		$data['status'] = $this->Product->getDataStatus();
		$data['kategori'] = $this->Product->getDataKategori();
		$data['produk'] = $this->Product->getDataProductId($id);

		$this->form_validation->set_rules('nama', 'Nama Produk', 'required', array(
			'required' => 'Nama produk harus diisi'
		));
		$this->form_validation->set_rules('harga', 'Harga', 'required|numeric', array(
			'required' => 'Inputan tidak boleh kosong',
			'numeric' => 'Hanya angka yang diperbolehkan'
		));
		$this->form_validation->set_rules('kategori', 'Kategori', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		if ($this->form_validation->run() == false) {
			$this->load->view('pages/edit-product', $data);
		} else {
			$this->Product->editProduct();
			$this->session->set_flashdata('flash', "Data produk dengan id $id berhasil diubah");

			redirect('product');
		}
	}

	public function delete($id)
	{
		$this->Product->deleteProduct($id);
		$this->session->set_flashdata('flash', "Data produk dengan id $id berhasil dihapus");

		redirect('product');
	}

	public function truncate()
	{
		$this->db->truncate('kategori');
		$this->db->truncate('produk');
		$this->db->truncate('status');

		redirect('product');
	}
}
