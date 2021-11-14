<?php

namespace App\Services\Repayments;

use App\Repository\Repayments\RepaymentRepository;


class RepaymentService
{
    protected $repaymentRepository;
    public function __construct(RepaymentRepository $repaymentRepository) {
        $this->repaymentRepository = $repaymentRepository;
    }

    public function getAll($page = 1, $pageSize = 15)
    {
        return $this->repaymentRepository->table()
        ->join("loans", "loans.id", "repayments.loan_id")
        ->join("users", "users.id", "loans.user_id")
        ->select(
            "repayments.id",
            "repayments.amount",
            "repayments.message",
            "repayments.loan_id",
            "repayments.created_at",
            "repayments.updated_at",
            "users.name",
        )
        ->paginate(perPage: $pageSize, page: $page);
    }
    
    /**
     * Get user by id
     * 
     * @param bigint id
     * 
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getById($id)
    {
        return $this->repaymentRepository->find(id: $id);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes)
    {
        return $this->repaymentRepository->create($attributes);;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function update(array $attributes)
    {
        return $this->repaymentRepository->update($attributes);
    }

    public function delete($id)
    {
        return $this->repaymentRepository->delete($id);
    }
}