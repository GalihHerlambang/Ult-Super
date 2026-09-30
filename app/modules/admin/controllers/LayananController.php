<?php
require_once APP_PATH . '/core/Controller.php';

class LayananController extends Controller
{
    private Layanan $layananModel;
    private LayananTahapan $tahapanModel;
    private LayananField $fieldModel;

    public function __construct()
    {
        $this->layananModel = new Layanan();
        $this->tahapanModel = new LayananTahapan();
        $this->fieldModel   = new LayananField();
    }

    public function index(): void
    {
        $this->requireRole('admin');
        $layanans  = $this->layananModel->all();
        $pageTitle = 'Manajemen Layanan';
        $this->view('admin/views/layanan/index', compact('layanans', 'pageTitle'));
    }

    public function tambah(): void
    {
        $this->requireRole('admin');
        $pageTitle = 'Tambah Layanan';
        $this->view('admin/views/layanan/form', compact('pageTitle'));
    }

    public function simpan(): void
    {
        $this->requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/layanan');
        }

        $fileInfo = $this->handleTemplateUpload();

        $this->layananModel->insert([
            'nama'               => $this->post('nama'),
            'deskripsi'          => $this->post('deskripsi'),
            'is_active'          => $this->post('is_active') === '1',
            'file_template'      => $fileInfo['file_template'] ?? null,
            'nama_file_template'=> $fileInfo['nama_file_template'] ?? null,
            'is_template_wajib'  => $this->post('is_template_wajib') === '1',
        ]);

        $this->flash('success', 'Layanan berhasil ditambahkan.');
        $this->redirect('admin/layanan');
    }

    public function edit(string $id): void
    {
        $this->requireRole('admin');
        $layanan = $this->layananModel->find((int)$id);
        if (!$layanan) { $this->redirect('admin/layanan'); }
        $tahapan  = $this->tahapanModel->getByLayanan((int)$id);
        $fields   = $this->fieldModel->getByLayanan((int)$id);
        $pageTitle = 'Edit Layanan: ' . $layanan['nama'];
        $this->view('admin/views/layanan/form', compact('layanan', 'tahapan', 'fields', 'pageTitle'));
    }

    public function update(string $id): void
    {
        $this->requireRole('admin');
        $layanan = $this->layananModel->find((int)$id);
        if (!$layanan) { $this->redirect('admin/layanan'); }

        $updateData = [
            'nama'              => $this->post('nama'),
            'deskripsi'         => $this->post('deskripsi'),
            'is_active'         => $this->post('is_active') === '1',
            'is_template_wajib' => $this->post('is_template_wajib') === '1',
        ];

        if ($this->post('hapus_file_template') === '1') {
            if (!empty($layanan['file_template'])) {
                $oldFile = PUBLIC_PATH . $layanan['file_template'];
                if (file_exists($oldFile)) @unlink($oldFile);
            }
            $updateData['file_template'] = null;
            $updateData['nama_file_template'] = null;
        }

        $fileInfo = $this->handleTemplateUpload();
        if ($fileInfo) {
            if (!empty($layanan['file_template']) && empty($this->post('hapus_file_template'))) {
                $oldFile = PUBLIC_PATH . $layanan['file_template'];
                if (file_exists($oldFile)) @unlink($oldFile);
            }
            $updateData['file_template'] = $fileInfo['file_template'];
            $updateData['nama_file_template'] = $fileInfo['nama_file_template'];
        }

        $this->layananModel->update((int)$id, $updateData);
        $this->flash('success', 'Layanan berhasil diperbarui.');
        $this->redirect('admin/layanan');
    }

    public function hapus(string $id): void
    {
        $this->requireRole('admin');
        $layanan = $this->layananModel->find((int)$id);
        if ($layanan && !empty($layanan['file_template'])) {
            $oldFile = PUBLIC_PATH . $layanan['file_template'];
            if (file_exists($oldFile)) @unlink($oldFile);
        }
        $this->layananModel->delete((int)$id);
        $this->flash('success', 'Layanan berhasil dihapus.');
        $this->redirect('admin/layanan');
    }

    private function handleTemplateUpload(): ?array
    {
        if (empty($_FILES['file_template']['name']) || $_FILES['file_template']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $dir = PUBLIC_PATH . '/uploads/template_layanan';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $originalName = $_FILES['file_template']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['pdf','doc','docx','xls','xlsx','png','jpg','jpeg','zip'];
        if (!in_array($ext, $allowed)) {
            return null;
        }

        $filename = uniqid('tpl_') . '.' . $ext;
        if (move_uploaded_file($_FILES['file_template']['tmp_name'], $dir . '/' . $filename)) {
            return [
                'file_template' => '/uploads/template_layanan/' . $filename,
                'nama_file_template' => $originalName
            ];
        }
        return null;
    }

    // ---- TAHAPAN ----
    public function tahapan(string $layananId): void
    {
        $this->requireRole('admin');
        $layanan  = $this->layananModel->find((int)$layananId);
        if (!$layanan) { $this->redirect('admin/layanan'); }
        $tahapan  = $this->tahapanModel->getByLayanan((int)$layananId);
        $pageTitle = 'Tahapan: ' . $layanan['nama'];
        $this->view('admin/views/layanan/tahapan', compact('layanan', 'tahapan', 'pageTitle'));
    }

    public function simpanTahapan(string $layananId): void
    {
        $this->requireRole('admin');
        $this->tahapanModel->insert([
            'layanan_id'       => (int)$layananId,
            'urutan'           => (int)$this->post('urutan', 1),
            'nama_tahapan'     => $this->post('nama_tahapan'),
            'role_pelaksana'   => $this->post('role_pelaksana'),
            'tipe_approval'    => $this->post('tipe_approval', 'any'),
            'jenis_tahapan'    => $this->post('jenis_tahapan', 'approval'),
            'action_type'      => $this->post('action_type') ?: null,
            'is_internal_only' => $this->post('is_internal_only') === '1',
            'sla_hari'         => (int)$this->post('sla_hari', 3),
            'is_final'         => $this->post('is_final') === '1',
        ]);
        $this->flash('success', 'Tahapan berhasil ditambahkan.');
        $this->redirect('admin/layanan/tahapan/' . $layananId);
    }

    public function hapusTahapan(string $tahapanId): void
    {
        $this->requireRole('admin');
        $t = $this->tahapanModel->find((int)$tahapanId);
        $layananId = $t['layanan_id'] ?? null;
        $this->tahapanModel->delete((int)$tahapanId);
        $this->flash('success', 'Tahapan berhasil dihapus.');
        $this->redirect('admin/layanan/tahapan/' . $layananId);
    }

    // ---- FIELD FORMULIR ----
    public function field(string $layananId): void
    {
        $this->requireRole('admin');
        $layanan  = $this->layananModel->find((int)$layananId);
        if (!$layanan) { $this->redirect('admin/layanan'); }
        $fields   = $this->fieldModel->getByLayanan((int)$layananId);
        $pageTitle = 'Field Formulir: ' . $layanan['nama'];
        $this->view('admin/views/layanan/field', compact('layanan', 'fields', 'pageTitle'));
    }

    public function simpanField(string $layananId): void
    {
        $this->requireRole('admin');
        $opsiRaw = $this->post('opsi', '');
        $opsi = null;
        if ($opsiRaw) {
            $opsi = array_filter(array_map('trim', explode("\n", $opsiRaw)));
            $opsi = array_values($opsi);
        }
        $this->fieldModel->insert([
            'layanan_id'  => (int)$layananId,
            'label'       => $this->post('label'),
            'nama_field'  => $this->post('nama_field'),
            'tipe_field'  => $this->post('tipe_field', 'text'),
            'opsi'        => $opsi,
            'is_required' => $this->post('is_required') === '1',
            'urutan'      => (int)$this->post('urutan', 1),
        ]);
        $this->flash('success', 'Field berhasil ditambahkan.');
        $this->redirect('admin/layanan/field/' . $layananId);
    }

    public function hapusField(string $fieldId): void
    {
        $this->requireRole('admin');
        $f = $this->fieldModel->find((int)$fieldId);
        $layananId = $f['layanan_id'] ?? null;
        $this->fieldModel->delete((int)$fieldId);
        $this->flash('success', 'Field berhasil dihapus.');
        $this->redirect('admin/layanan/field/' . $layananId);
    }
}
