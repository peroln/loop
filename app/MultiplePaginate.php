<?php

namespace App;

trait MultiplePaginate
{
    private function multiplePaginate(int $currentPage = 1, int $perPage = 15, ...$tables)
    {
        $transactions = array_merge(... $tables);

        //Sort transactions
        usort($transactions, function ($a, $b) {
            return strtotime($a['created_at']) < strtotime($b['created_at']);
        });

        $transactions = collect($transactions)->chunk($perPage);

        if (\count($transactions) < $currentPage) {
            $currentPage = \count($transactions);
        }

        if (0 > $currentPage) {
            $currentPage = 1;
        }

        $transactionsData = [];

        if (\count($transactions)) {
            foreach ($transactions[$currentPage - 1] as $transaction) {
                $transactionsData[] = $transaction;
            }
        }

        //Create data
        $data['total'] = \count($transactions);
        $data['per_page'] = $perPage;
        $data['last_page'] = \count($transactions);
        $data['current_page'] = $currentPage;
        $data['transactions'] = $transactionsData;

        return $data;
    }


    private function multipleModelPaginate(array $tables, int $currentPage = 1, int $perPage = 15)
    {
        $result_tables = [];

        foreach ($tables as $table) {
            $result_tables = array_merge($result_tables, $table->toArray());
        }

        $result_tables = collect($result_tables)->chunk($perPage);


        if (count($result_tables) < $currentPage) {
            $currentPage = count($result_tables);
        }

        if (0 > $currentPage) {
            $currentPage = 1;
        }

        $tableData = [];

        if (count($result_tables)) {
            foreach ($result_tables[$currentPage - 1] as $result_table) {
                $tableData[] = $result_table;
            }
        }

        //Create data
        $data['total'] = count($result_tables);
        $data['per_page'] = $perPage;
        $data['last_page'] = count($result_tables);
        $data['current_page'] = $currentPage;
        $data['data'] = $tableData;

        return $data;
    }


}
