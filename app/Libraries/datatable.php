<?php

namespace App\Libraries;

use CodeIgniter\Model;
// use App\Models\StudentModel;

class datatable
{

	 public function __construct()
    {
        $this->request  = service('request');
        $this->response = service('response');
    }
    public function generate(Model $model, array $searchableColumns = [])
    {
        $draw     = (int) $this->request->getPost('draw');
        $start    = (int) $this->request->getPost('start');
        $length   = (int) $this->request->getPost('length');
        $search   = $this->request->getPost('search')['value'] ?? '';

        $order    = $this->request->getPost('order');
        $columns  = $this->request->getPost('columns');
        $colIndex = $order[0]['column'] ?? 0;
        $colName  = $columns[$colIndex]['data'] ?? $model->primaryKey;
        $sortDir  = $order[0]['dir'] ?? 'asc';

        // Total record count
        $totalRecords = $model->countAll();

        // Filtering
        $builder = $model;
        if (!empty($search) && !empty($searchableColumns)) {
            $builder = $builder->groupStart();
            foreach ($searchableColumns as $column) {
                $builder = $builder->orLike($column, $search);
            }
            $builder = $builder->groupEnd();
        }

        // Count after filtering
        $filteredCount = (clone $builder)->countAllResults(false);

        // Get data
        $data = $builder->orderBy($colName, $sortDir)
                        ->findAll($length, $start);

        // Return JSON
        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredCount,
            'data'            => $data,
        ]);
    }
}