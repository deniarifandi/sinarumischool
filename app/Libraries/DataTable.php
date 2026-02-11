<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseBuilder;

class DataTable
{
    protected $builder;
    protected $request;
    protected $columns;
    protected $sortable = [];

    public function __construct(BaseBuilder $builder, array $columns = [], array $sortable = [])
    {
        $this->builder  = $builder;
        $this->request  = service('request');
        $this->columns  = $columns;
        $this->sortable = $sortable;
    }

    public function generate()
{
    $draw = (int) $this->request->getPost('draw');
    $start = (int) $this->request->getPost('start');
    $length = (int) $this->request->getPost('length');
    $searchValue = $this->request->getPost('search')['value'] ?? '';

    // Clone before touching builder
    $baseBuilder = clone $this->builder;

    // Count total records (without filters)
    $recordsTotal = $baseBuilder->countAllResults();

    // Rebuild the main query because countAllResults resets it
    $this->builder = clone $this->builder;

    // Apply search
    if (!empty($searchValue) && !empty($this->columns)) {
        $this->builder->groupStart();
        foreach ($this->columns as $col) {
            $this->builder->orLike($col, $searchValue);
        }
        $this->builder->groupEnd();
    }

    // Count filtered results
    $filteredBuilder = clone $this->builder;
    $recordsFiltered = $filteredBuilder->countAllResults();

    // Rebuild again for data fetch
    $this->builder = clone $this->builder;

    // Apply ordering
    // Apply ordering
    $order = $this->request->getPost('order');
    if (!empty($order)) {
        foreach ($order as $ord) {
            $colIdx = $ord['column'];
            $dir    = $ord['dir'] === 'desc' ? 'desc' : 'asc';

            // ✅ If controller defines custom sortable columns
            if (isset($this->sortable[$colIdx])) {
                $this->builder->orderBy($this->sortable[$colIdx], $dir);
                continue;
            }

            // ✅ Fallback to normal behavior (old behavior)
            if (isset($this->columns[$colIdx])) {
                $this->builder->orderBy($this->columns[$colIdx], $dir);
            }
        }
    }


    if ($length != -1) {
        $this->builder->limit($length, $start);
    }

    // ✅ Fetch joined data again
    $data = $this->builder->get()->getResultArray();

    return [
        'draw' => $draw,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data,
    ];
}


// Optional helper to reset builder joins/selects (if needed)
private function rebuildBaseBuilder($builder)
{
    // Rebuild SELECT and JOIN here if resetQuery() is used above
    // For now, we can skip this unless you see missing joins
    return $builder;
}


}
