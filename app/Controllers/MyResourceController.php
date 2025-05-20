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
            $input[$f[1]] = $this->request->getPost($f[1]);
        }

        if (!$this->model->insert($input)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to($this->table)->with('success', 'Data berhasil ditambahkan');
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
        $input = [];
        foreach ($this->field as $f) {
            $input[$f[1]] = $this->request->getPost($f[1]);
        }
        // print_r($id);
        // print_r($input);

        if (!$this->model->update($id, $input)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        return redirect()->to($this->table)->with('success', 'Data berhasil diupdate');       
    }
   public function delete($id = null)
    {
        if (!$this->model->delete($id)) {
            return redirect()->back()->with('errors', ['Failed to delete data.']);
        }

        return redirect()->to(site_url($this->table))->with('success', 'Data berhasil dihapus');
    }
}
