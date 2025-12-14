<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Datatable
{
    protected RequestInterface $request;
    protected ResponseInterface $response;

    public function __construct()
    {
        $this->request  = service('request');
        $this->response = service('response');
    }

    /**
     * Generate DataTables JSON response
     */
    public function generate(
        BaseBuilder $builder,
        string $primaryKey,
        array $searchable = [],
        array $orderable = []
    ) {
        // Request params
        $draw   = (int) $this->request->getPost('draw');
        $start  = (int) $this->request->getPost('start');
        $length = (int) $this->request->getPost('length');
        $search = $this->request->getPost('search.value');

        $order  = $this->request->getPost('order')[0] ?? null;
        $cols   = $this->request->getPost('columns') ?? [];

        /* ============================
           TOTAL RECORDS
        ============================ */
        $totalRecords = (clone $builder)->countAllResults();

        /* ============================
           SEARCH
        ============================ */
        if ($search && $searchable) {
            $builder->groupStart();
            foreach ($searchable as $col) {
                $builder->orLike($col, $search);
            }
            $builder->groupEnd();
        }

        /* ============================
           FILTERED RECORDS
        ============================ */
        $filteredRecords = (clone $builder)->countAllResults();

        /* ============================
           ORDERING (SAFE)
        ============================ */
        if ($order && isset($cols[$order['column']]['data'])) {
            $column = $cols[$order['column']]['data'];

            if (in_array($column, $orderable, true)) {
                $builder->orderBy($column, $order['dir'] === 'desc' ? 'DESC' : 'ASC');
            }
        } else {
            $builder->orderBy($primaryKey, 'ASC');
        }

        /* ============================
           PAGINATION
        ============================ */
        if ($length !== -1) {
            $builder->limit($length, $start);
        }

        $data = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data,
        ]);
    }
}
