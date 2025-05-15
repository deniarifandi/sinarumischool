<?php

namespace App\Libraries;

use CodeIgniter\Model;
// use App\Models\StudentModel;
use CodeIgniter\Database\BaseBuilder;

class datatable
{

	 public function __construct()
    {
        $this->request  = service('request');
        $this->response = service('response');
    }
    public function generate(BaseBuilder $builder, string $primaryKey, array $searchable = [])
    {
      $draw   = (int) $this->request->getPost('draw');
        $start  = (int) $this->request->getPost('start');
        $length = (int) $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'] ?? '';

        $order    = $this->request->getPost('order');
        $columns  = $this->request->getPost('columns');
        $colIndex = $order[0]['column'] ?? 0;
        $colName  = $columns[$colIndex]['data'] ?? $primaryKey;
        $sortDir  = $order[0]['dir'] ?? 'asc';

        // Clone original builder for total count
        $baseBuilder = clone $builder;
        $totalRecords = $baseBuilder->countAllResults(false);

        // Search filter
        if (!empty($search) && !empty($searchable)) {
            $builder->groupStart();
            foreach ($searchable as $col) {
                $builder->orLike($col, $search);
            }
            $builder->groupEnd();
        }

        // Clone for filtered count
        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults(false);

        // Sorting and limiting
        $builder->orderBy($colName, $sortDir);
        $data = $builder->get($length, $start)->getResult();

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data,
        ]);
    }
}