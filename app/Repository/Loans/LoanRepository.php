<?php

namespace App\Repository\Loans;

use App\Models\Loan;
use App\Repository\BaseRepository;

class LoanRepository extends BaseRepository
{

    protected $model;

    /**
     * LoanRepository constructor.
     *
     * @param User $model
     */
    public function __construct(Loan $model)
    {
        parent::__construct($model);

        $this->model = $model;
    }
}
