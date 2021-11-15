# README #

This is a code challenge API Restfull use Passpost parkage to verify. 
Idea user type admin can: 
  - create user for loan. 
  - create Loan.
  - update Loan.
  - delete Loan
  - create Repayment
  - update Repayment
  - delete Repayment.

Validator in create user, loan and repayment

## Database structure ##
* Table Users: 
    - Description: it''s table default when install laravel project & passport parkage
    - Field:
        - name
        - email 
        - email_verified_at
        - password
        - remember_token
        - created_at
        - updated_at

* Table Loans:
    - Description: it a main table to store all loan
    - Field:
        - amount: amount you want to borrow
        - user_id : borrower
        - repayment_frequency: Borrowed time
        - interest_rate: loan interest rate
        - arrangement_fee: application fee, procedure
        - amount_need_repayment: remaining amount to be paid
        - amount_repayment: total payable after fee + interest + loan amount
        - current_month_repayment: monthly payment amount
        - status: the state of the transaction, respectively
            - 'LOAN_STATUS_NONE' initialization,
            - 'LOAN_STATUS_IN_PROGRESS' during the loan period
            - 'LOAN_STATUS_OVERDUE' overdue payment
            - 'LOAN_STATUS_COMPLETED' complete debt payment
        - deleted: status soft delete
* Table complete debt payment
    - Description: it a table to store timeline repayment
    - Field:
        - loan_id: ID of loan
        - amount: amount to pay, it alway equal `current_month_repayment`.
        - message: note to pament.
        - deleted: status soft delete

### How do I get set up? ###

* Envirenment: 
    - php8, mysql.
* Dependencies: 
    - passport parkage
* Database configuration
    - Change DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env file
* How to run tests
    - Step 1 run install with command `composer install`
    - Step 2 run `php artisan key:generate` to set  APP_KEY in .env file
    - Step 3 run mirgate database `php artisan migrate:fresh` in the first runtime.
    - Step 4 run command php artisan passport:install, php artisan passport:client --personal

#### API ####
    - api/register: register user to get token. It's have som validate: password must be at least 6 characters long, email is unique.. 
    - api/login: login to get token process loan or repayment.
    - api/loans: list of the loan
    - api/create/loans: create new loan.
    - api/loan/1: get detail of loan items.
    - api/loan/edit: update the loan, current I'm only update `current_month_repayment`, `status`, `amount_need_repayment` with mission whenever repayment or approve loan.
    - api/loan/delete: current I work with hard delete, with improve soft delete in next version.
    - api/repayments: list of repayment. Current I have use resouce in Laravel to format `amout` and time line.
    - api/create/repayment: create new repayment. Validate with `status` not in LOAN_STATUS_NONE, LOAN_STATUS_COMPLETED and `amount` equal `current_month_repayment` of the loan
    - api/repayment/edit: I only process update message.
    - api/repayment/delete: current I work with hard delete, with improve soft delete in next version.

#### Unit test ####
    - Current I only test some function 
        - testRequiredFieldsForRegistration: required field when register
        - testSuccessfulRegistration: test register success
        - testSuccessfulLogin: login success
        - testIndexLoan: admin will login and get list Loan
        - testCreateLoan: admin will login and create new Loan
        - testIndexRepaymentsFail: create new user, create Loan and repayment fail in validate case.

    - With this structure, I have write with Service container, Dependency injection to prepare write advance mock data to test. I will complete in nexttime.


    
