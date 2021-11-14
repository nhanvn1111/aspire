<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Repository\Loans\LoanRepository;
use App\Services\Loans\LoanService;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\LoanResource;
class LoanController extends BaseController
{

    protected $loanRepository;
    protected $loanService;

    public function __construct(
        LoanRepository $loanRepository,
        LoanService $loanService,
    ) {
        $this->loanRepository = $loanRepository;
        $this->loanService = $loanService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loan = $this->loanService->getAll();
        return $this->sendResponse(LoanResource::collection($loan), 'Loan retrieved successfully.');
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'amount'=> 'required',
            'repayment_frequency' => 'required',
            'interest_rate'=> 'required',
            'arrangement_fee'=> 'required',
            'amount_need_repayment'=>'required',
            'amount_repayment'=> 'required',
            'current_month_repayment'=> 'required',
            'status'=> 'required',
            'deleted'=> 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $loan = $this->loanService->create($input);
   
        return $this->sendResponse(new LoanResource($loan), 'Loan created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loans = $this->loanService->getById($id);
  
        if (is_null($loans)) {
            return $this->sendError('Loan not found.');
        }
   
        return $this->sendResponse(new LoanResource($loans), 'Loans retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'amount_need_repayment'=>'required',
            'current_month_repayment'=> 'required',
            'status'=> 'required',
            'id'=> 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $user = $request->user();
        $id = $input['id'];
        $loan = $this->loanService->getById($id);
        if (is_null($loan)) {
            return $this->sendError('Loan not found.');
        }
        $loan->amount_need_repayment = $input['amount_need_repayment'];
        $loan->current_month_repayment = $input['current_month_repayment'];
        $loan->status = $input['status'];
        $loan->save();
   
        return $this->sendResponse(new LoanResource($loan), 'Loan updated successfully.');
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        $id = $input['id'];
        $loan = $this->loanService->getById($id);
        if (is_null($loan)) {
            return $this->sendError('Loan not found.');
        }
        $loan->delete();
        return $this->sendResponse([], 'Loan deleted successfully.');
    }
}
