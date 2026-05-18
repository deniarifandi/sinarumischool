<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseBuilder;

class DataTables
{
    protected BaseBuilder $builder;
    protected array $columns = [];
    protected array $request;

    public function __construct(BaseBuilder $builder, array $request)
    {
        $this->builder = $builder;
        $this->request = $request;
    }

    public function setColumns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function applySearch(array $searchableColumns)
    {
        $search = $this->request['search']['value'] ?? '';

        if ($search) {
            $this->builder->groupStart();

            foreach ($searchableColumns as $col) {
                $this->builder->orLike($col, $search);
            }

            $this->builder->groupEnd();
        }

        return $this;
    }

    public function applyFilters(callable $callback)
    {
        $callback($this->builder, $this->request);
        return $this;
    }

    public function applyOrdering()
    {
        $order = $this->request['order'][0] ?? null;

        if (!$order) return $this;

        $index = (int) $order['column'];
        $dir   = $order['dir'] ?? 'desc';

        $column = $this->columns[$index] ?? null;

        if ($column) {
            $this->builder->orderBy($column, $dir);
        }

        return $this;
    }

    public function paginate()
    {
        $start  = (int) ($this->request['start'] ?? 0);
        $length = (int) ($this->request['length'] ?? 10);

        $this->builder->limit($length, $start);

        return $this;
    }

    public function result()
    {
        return $this->builder->get()->getResultArray();
    }

    public function countFiltered()
    {
        $clone = clone $this->builder;
        return $clone->countAllResults(false);
    }

    public function countAll(string $table)
    {
        return db_connect()->table($table)->countAllResults();
    }
}