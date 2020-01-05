<?php

namespace App\Services\TransactionProcessors;

use App\Services\Contracts\ProcessorContract;

class Megacash implements ProcessorContract
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function process()
    {
        $this->model->status = 'completed';
        $this->model->details = \substr($this->model->details, 0, 20);
        $this->model->save();
    }
}