<?php namespace App\Controllers;

use App\Models\KomikModel;

class Komik extends BaseController
{

	protected $komikModel;

	public function __construct()
	{
		$this->komikModel = new KomikModel();
	}

	public function index()
	{
		// $komik = $this->komikModel->findAll();

		$data = [
			'title' => 'DaftarKomik | WebProgrammingUnpas',
			'komik' => $this->komikModel->getKomik()
		];

		// $komikModel = new \App\Models\KomikModel();
		return view('komik/index', $data);
	}

	public function detail($slug)
	{
		$data = [
			'title' => 'DetailKomik | WebProgrammingUnpas',
			'komik' => $this->komikModel->getKomik($slug)
		];

		if(empty($data['komik'])){
			throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul Komik ' . $slug . ' tidak ditemukan.');
		}

		return view('komik/detail', $data);
	}

	public function create()
	{
		// session();

		$data = [
			'title' => 'FormTambahDataKomik | WebProgrammingUnpas',
			'validation' => \Config\Services::validation()
		];

		return view('komik/create', $data);
	}

	public function save()
	{
		// validasi
		if(!$this->validate([
			'judul' => [
				'rules' => 'required|is_unique[komik.judul]',
				'errors' => [
					'required' => '{field} komik harus diisi.',
					'is_unique' => '{field} komik sudah terdaftar'
				]
			]
		])){
			$validation = \Config\Services::validation();
			return redirect()->to('/komik/create')->withInput()->with('validation', $validation);
		}

		$slug = url_title($this->request->getVar('judul'), '-', true);

		$this->komikModel->save([
			'judul' => $this->request->getVar('judul'),
			'slug' => $slug,
			'penulis' => $this->request->getVar('penulis'),
			'penerbit' => $this->request->getVar('penerbit'),
			'sampul' => $this->request->getVar('sampul')
		]);

		session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');

		return redirect()->to('/komik');
	}

}
