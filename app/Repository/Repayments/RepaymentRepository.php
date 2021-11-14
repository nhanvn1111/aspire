<?php

namespace App\Repository\Repayments;

use App\Models\Repayment;
use App\Repository\BaseRepository;

class RepaymentRepository extends BaseRepository
{

    protected $model;

    /**
     * RepaymentRepository constructor.
     *
     * @param User $model
     */
    public function __construct(Repayment $model)
    {
        parent::__construct($model);

        $this->model = $model;
    }
}
