<?php

// app/Controllers/MyResourceController.php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use App\Libraries\datatable;

class MyResourceController extends ResourceController
{
    public $orderBy;
    public $groupBy;

    protected function prepareDataToShow()
    {
        $dataToShow = [
            'table'        => $this->table,
            'title'        => $this->title,
            'field'        => $this->field,
            'fieldName'    => $this->fieldName,
            'primaryKey'   => $this->primaryKey,
            'fieldOption'  => $this->fieldOption,
            'fieldList'    => $this->fieldList,
            'selectList'   => $this->selectList,
            'where'        => $this->where,
            'order'        => $this->orderBy,
            'group'        => $this->groupBy
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

            if ($name == "guru_password") {

                // $name = password_hash($name, PASSWORD_DEFAULT);
                $input[$name] = password_hash($this->request->getPost($name),PASSWORD_DEFAULT);
                // echo $input[$name];
                
            }else{
                $input[$name] = $this->request->getPost($name);    
            }
            
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
            
            if ($type == "password") {

                if ($this->request->getPost($name) != "unchanged") {
                    $input[$name] = password_hash($this->request->getPost($name),PASSWORD_DEFAULT);
                }
                
                
                
            }else{
                $input[$name] = $this->request->getPost($name);    
            }
            
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

    public function data(){
        $builder = Database::connect()->table($this->table);
        // $builder->select($this->table.'.*');

         $builder->select(implode(', ', $this->selectList));
        if (!empty($this->joinTable)) {
            foreach ($this->joinTable as $join) {
                    // $join[0] = join table name
                    // $join[1] = join condition
                // $builder->select($join[0] . '.*');
                $builder->join($join[0], $join[1],$join[2]);
            }
        }
        $builder->where($this->table.'.deleted_at',NULL);;

        foreach ($this->where as $key => $value) {
            $builder->where($key, $value);
        }

        if (!empty($this->orderBy)) {
            if (is_array($this->orderBy)) {
                // Handle multiple order conditions
                foreach ($this->orderBy as $key => $value) {
                    if (is_numeric($key) && is_array($value)) {
                        $builder->orderBy($value[0], $value[1]); // [['name', 'ASC']]
                    } else {
                        $builder->orderBy($key, $value); // ['name' => 'ASC']
                    }
                }
            } else {
                // Assume it's a string like "name ASC"
                [$column, $direction] = explode(' ', $this->orderBy);
                $builder->orderBy($column, $direction);
            }
        }

        if (!empty($this->groupBy)) {
            
                // string — can be one or more columns separated by commas
                $builder->groupBy($this->groupBy);
            
        }

        $datatable = new Datatable();

        return $datatable->generate($builder, $this->table.'.'.$this->primaryKey, $this->toSearch);
    }
}
