<?php

namespace App\Embed\Midtrans;

use Midtrans\Transaction;

class StatusTransactionService extends Midtrans
{
    protected $order;

    public function __construct($order)
    {
        parent::__construct();

        $this->order = $order;
    }

    public function getstatus()
    {
        $statusTransaction = Transaction::status($this->order);

        return $statusTransaction;
    }
}
