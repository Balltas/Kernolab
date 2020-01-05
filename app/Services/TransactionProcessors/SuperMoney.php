<?php

namespace App\Services\TransactionProcessors;

use App\Services\Contracts\ProcessorContract;

class SuperMoney implements ProcessorContract
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function process()
    {
         $this->model->status = 'completed';
         $this->model->details .= \rand();
         $this->model->save();
    }
}