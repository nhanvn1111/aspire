<?php

namespace App\Services\Loans;

use App\Repository\Loans\LoanRepository;


class LoanService
{
    protected $loanRepository;
    public function __construct(LoanRepository $loanRepository) {
        $this->loanRepository = $loanRepository;
    }


    public function getAll($page = 1, $pageSize = 15)
    {
        return $this->loanRepository->table()->paginate(perPage: $pageSize, page: $page);
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
        return $this->loanRepository->find(id: $id);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes)
    {
        return $this->loanRepository->create($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function update(array $attributes)
    {
        return $this->loanRepository->update($attributes);
    }

    public function delete($id)
    {
        return $this->loanRepository->delete($id);
    }
}