<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductModel extends CI_Model
{
  public function getDataProduct()
  {
    $this->db->select('a.id_produk, a.nama_produk, a.harga, b.nama_kategori, c.nama_status');
    $this->db->from('produk a');
    $this->db->join('kategori b', 'b.id_kategori = a.kategori_id', 'left');
    $this->db->join('status c', 'c.id_status = a.status_id', 'left');
    $this->db->where('a.status_id', 1);

    $query = $this->db->get()->result_array();

    return $query;
  }

  public function getDataProductId($id)
  {
    $this->db->select('a.id_produk, a.nama_produk, a.harga, a.kategori_id, a.status_id, b.nama_kategori, c.nama_status');
    $this->db->from('produk a');
    $this->db->where('a.id_produk', $id);
    $this->db->join('kategori b', 'b.id_kategori = a.kategori_id');
    $this->db->join('status c', 'c.id_status = a.status_id');

    $query = $this->db->get()->row_array();

    return $query;
  }

  public function getProductId($item)
  {
    $query = $this->db->get_where('produk', array('id_produk' => $item['id_produk']));
    $result = $query->row_array();

    return $result;
  }

  public function getDataKategori()
  {
    $this->db->select('*');
    $this->db->from('kategori');

    $query = $this->db->get()->result_array();

    return $query;
  }

  public function getDataStatus()
  {
    $this->db->select('*');
    $this->db->from('status');

    $query = $this->db->get()->result_array();

    return $query;
  }

  public function getKategori($nama_kategori)
  {
    $query = $this->db->get_where('kategori', array('nama_kategori' => $nama_kategori));
    $result = $query->row_array();

    return $result;
  }

  public function getStatus($nama_status)
  {
    $query = $this->db->get_where('status', array('nama_status' => $nama_status));
    $result = $query->row_array();

    return $result;
  }

  public function addProduct($dataProduct)
  {
    $this->db->insert('produk', $dataProduct);
  }

  public function addKategori($nama_kategori)
  {
    $this->db->insert('kategori', array('nama_kategori' => $nama_kategori));
    return $this->db->insert_id();
  }

  public function addStatus($nama_status)
  {
    $this->db->insert('status', array('nama_status' => $nama_status));
    return $this->db->insert_id();
  }

  public function addNewProduct()
  {
    $nama = $this->input->post('nama');
    $harga = $this->input->post('harga');
    $kategori_id = $this->input->post('kategori');
    $status_id = $this->input->post('status');

    $data = [
      'nama_produk' => $nama,
      'harga' => $harga,
      'kategori_id' => $kategori_id,
      'status_id' => $status_id,
    ];

    $this->db->insert('produk', $data);
  }

  public function editProduct()
  {
    $id = $this->input->post('id');
    $nama = $this->input->post('nama');
    $harga = $this->input->post('harga');
    $kategori_id = $this->input->post('kategori');
    $status_id = $this->input->post('status');

    $data = [
      'nama_produk' => $nama,
      'harga' => $harga,
      'kategori_id' => $kategori_id,
      'status_id' => $status_id,
    ];

    $where = [
      'id_produk' => $id
    ];

    $this->db->update('produk', $data, $where);
  }

  public function deleteProduct($id)
  {
    $this->db->delete('produk', ['id_produk' => $id]);
  }
}
