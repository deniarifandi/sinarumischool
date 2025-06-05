<?php

// app/Controllers/MyResourceController.php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class MyResourceController extends ResourceController
{
    protected function prepareDataToShow()
    {
        $dataToShow = [
            'table'        => $this->table,
            'title'        => $this->title,
            'field'        => $this->field,
            'fieldName'    => $this->fieldName,
            'primaryKey'   => $this->primaryKey,
            'fieldOption'  => $this->fieldOption,
            'fieldList'    => $this->fieldList
        ];

        return $dataToShow;
    }
    public function index()
    {   

        return view('/list', $this->dataToShow);
    }    
    public function new()
    {
        
        return view('/create', $this->dataToShow);
    }

    public function create()
    {
      $input = [];

      foreach ($this->field as $f) {
        $type = $f[0];
        $name = $f[1];

        if ($type === 'file') {
            $file = $this->request->getFile($name);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $file->move(FCPATH . 'uploads', $file->getClientName());
                $input[$name] = $file->getClientName();
            }
        } else {
            $input[$name] = $this->request->getPost($name);
        }
    }

    if (!$this->model->insert($input)) {
        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    session()->setFlashdata('success', 'Data berhasil ditambahkan');
    return redirect()->to(site_url($this->table));
}

public function edit($id = null)
{
    $row = $this->model->find($id);
    if (!$row) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException("Data not found: $id");
    }

    $data = $this->prepareDataToShow();
    $data['data'] = $row;
    return view('edit', $data);
}

public function show($id = null)
{
    echo "show method";
        // Show user with given ID
}

public function update($id = null)
{
    // Get existing record
    $record = $this->model->find($id);
    if (!$record) {
        return redirect()->back()->with('errors', ['Data not found.']);
    }

    // Prepare input array
    $input = [];
    foreach ($this->field as $f) {
        $type = $f[0];
        $name = $f[1];

        if ($type === 'file') {
            $file = $this->request->getFile($name);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads', $newName);
                $input[$name] = $newName;

                // Optional: delete old file if exists
                if (!empty($record[$name]) && file_exists(FCPATH . 'uploads/' . $record[$name])) {
                    unlink(FCPATH . 'uploads/' . $record[$name]);
                }
            } else {
                // Keep old file name if no new file is uploaded
                $input[$name] = $record[$name];
            }
        } else {
            $input[$name] = $this->request->getPost($name);
        }
    }

    // Try update
    try {
        if (!$this->model->update($id, $input)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
        session()->setFlashdata('success', 'Data berhasil diperbarui');
        return redirect()->to(site_url($this->table));
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with('errors', [$e->getMessage()]);
    }
}
public function delete($id = null)
{
    if (!$this->model->delete($id)) {
        return redirect()->back()->with('errors', ['Failed to delete data.']);
    }

    return redirect()->to(site_url($this->table))->with('success', 'Data berhasil dihapus');
}
public function getdata($table){
        $db = \Config\Database::connect();
        $builder = $db->table($table);
        $builder->select('*');
        $builder->where('deleted_at', null);
        $query = $builder->get();
        $result = $query->getResultArray();
        $indexedOnly = array_map('array_values', $result);

        // print_r($indexedOnly);
    
        return $indexedOnly;
    }
}
