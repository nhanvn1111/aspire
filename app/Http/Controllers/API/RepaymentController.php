<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Repayment;
use Illuminate\Http\Request;
use App\Repository\Repayments\RepaymentRepository;
use App\Services\Repayments\RepaymentService;
use App\Repository\Loans\LoanRepository;
use App\Services\Loans\LoanService;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\RepaymentResource;

class RepaymentController extends BaseController
{

    protected $repaymentRepository;
    protected $repaymentService;
    protected $loanRepository;
    protected $loanService;

    public function __construct(
        RepaymentRepository $repaymentRepository,
        RepaymentService $repaymentService,
        LoanRepository $loanRepository,
        LoanService $loanService,
    ) {
        $this->repaymentRepository = $repaymentRepository;
        $this->repaymentService = $repaymentService;
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
        $repayment = $this->repaymentService->getAll();
    
        return $this->sendResponse(RepaymentResource::collection($repayment), 'Repayment retrieved successfully.');
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
            'loan_id' => 'required',
            'amount' => 'required',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $load_id = $input['loan_id'];
        $amount = $input['amount'];
        $loandetail = $this->loanService->getById($load_id);
        if (is_null($loandetail)) {
            return $this->sendError('Loan not found.');
        }elseif(!is_null($loandetail) && $loandetail->current_month_repayment > $amount){
            return $this->sendError('Amount loan not enought');
        }elseif(!is_null($loandetail) && $loandetail->status == 'LOAN_STATUS_NONE'){
            return $this->sendError('Loan status need approve!');
        }elseif(!is_null($loandetail) && $loandetail->status =='LOAN_STATUS_COMPLETED'){
            return $this->sendError('Loan status has finished!');
        }
        //create new repayment
        $repayment = $this->repaymentService->create($input);

        //update loan
        if($loandetail->current_month_repayment == 1 ){
            $loandetail->status = 'LOAN_STATUS_COMPLETED';
        }
        $loandetail->amount_need_repayment -= $amount;
        $loandetail->current_month_repayment -= 1;
        
        $loandetail->save();

        return $this->sendResponse(new RepaymentResource($repayment), 'Repayment created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $repayments = $this->repaymentService->getById($id);
  
        if (is_null($repayments)) {
            return $this->sendError('Repayments not found.');
        }
   
        return $this->sendResponse(new RepaymentResource($repayments), 'Repayments retrieved successfully.');
    }

  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'id' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $id = $input['id'];
        $repayment = $this->repaymentService->getById($id);
        if (is_null($repayment)) {
            return $this->sendError('Repayment not found.');
        }
        $repayment->message = $input['message'];
        $repayment->save();
   
        return $this->sendResponse(new RepaymentResource($repayment), 'Repayment updated message successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        $id = $input['id'];
        $repayment = $this->repaymentService->getById($id);
        if (is_null($repayment)) {
            return $this->sendError('Repayment not found.');
        }
        $repayment->delete();
        return $this->sendResponse([], 'Repayment deleted successfully.');
    }
}
