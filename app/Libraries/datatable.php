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
        $draw   = (int) $this->request->getPost('draw');
        $start  = (int) $this->request->getPost('start');
        $length = (int) $this->request->getPost('length');
        $searchData = $this->request->getPost('search');
        $search = $searchData['value'] ?? null;

        $order  = $this->request->getPost('order')[0] ?? null;
        $cols   = $this->request->getPost('columns') ?? [];

        /* ============================
           TOTAL RECORDS
        ============================ */
        $totalBuilder  = clone $builder;
        $totalRecords  = $totalBuilder->countAllResults(false);

        /* ============================
           SEARCH
        ============================ */
        if ($search && !empty($searchable)) {
            $builder->groupStart();
            foreach ($searchable as $col) {
                if ($col !== null && $col !== '') {
                    $builder->orLike($col, $search);
                }
            }
            $builder->groupEnd();
        }


        /* ============================
           FILTERED RECORDS
        ============================ */
        $filteredBuilder  = clone $builder;
        $filteredRecords  = $filteredBuilder->countAllResults(false);

        /* ============================
           ORDERING (SAFE)
        ============================ */
        if ($order && isset($cols[$order['column']]['data'])) {
            $column = $cols[$order['column']]['data'];
            $dir    = strtolower($order['dir'] ?? 'asc');

            if (in_array($column, $orderable, true)) {
                $builder->orderBy(
                    $column,
                    $dir === 'desc' ? 'DESC' : 'ASC'
                );
            }
        } else {
            $builder->orderBy($primaryKey, 'ASC');
        }

        /* ============================
           PAGINATION
        ============================ */
        if ($length > 0) {
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
