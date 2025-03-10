# Loan Service API

## Overview
The Loan Service API is a Laravel-based application that provides a RESTful API for managing loans and repayments. It allows users to create loans, view their loans, and make repayments. The application tracks scheduled repayments and received repayments for each loan.

## Features
- User authentication using Laravel Sanctum
- Create loans with specified amount and term
- View loan details including scheduled repayments
- Make repayments towards loans
- Track loan status and remaining balance

## Technology Stack
- **Framework**: Laravel
- **Authentication**: Laravel Sanctum
- **Database**: MySQL/PostgreSQL (configurable)
- **API**: RESTful JSON API

## Models
The application consists of the following key models:

### Loan
Represents a loan taken by a user:
- `user_id`: The user who took the loan
- `amount`: The total loan amount
- `term`: The loan term in months (3 or 6 months)
- `status`: Current status of the loan (pending, approved, paid)

### ScheduledRepayment
Represents a scheduled repayment for a loan:
- `loan_id`: The associated loan
- `amount`: The scheduled repayment amount
- `due_date`: The date when the repayment is due
- `status`: Status of the scheduled repayment (pending, paid, overdue)

### ReceivedRepayment
Represents a repayment made by a user:
- `loan_id`: The associated loan
- `scheduled_repayment_id`: The associated scheduled repayment (if any)
- `amount`: The amount paid
- `payment_date`: The date when the payment was made

## API Endpoints

### Authentication
- POST `/login` - Login a user
- POST `/register` - Register a new user
- POST `/logout` - Logout the current user

### Loans
- GET `/api/loans` - Get all loans for the authenticated user
- POST `/api/loans` - Create a new loan
- GET `/api/loans/{id}` - Get details of a specific loan

### Repayments
- POST `/api/loans/{loanId}/repayments` - Make a repayment for a loan
- GET `/api/loans/{loanId}/scheduled-repayments` - Get scheduled repayments for a loan
- GET `/api/loans/{loanId}/received-repayments` - Get received repayments for a loan

## Installation and Setup

### Prerequisites
- PHP >= 8.0
- Composer
- MySQL or PostgreSQL
- Node.js and NPM (for frontend assets)

### Installation Steps
1. Clone the repository:
```bash
git clone https://github.com/Thando12345/loan-service.git
cd loan-service
```

2. Install PHP dependencies:
```bash
composer install
```

3. Copy the environment file and configure your database:
```bash
cp .env.example .env
```

4. Generate an application key:
```bash
php artisan key:generate
```

5. Run migrations:
```bash
php artisan migrate
```

6. Optional: Seed the database with test data:
```bash
php artisan db:seed
```

7. Start the development server:
```bash
php artisan serve
```

## Usage Examples

### Creating a Loan
```bash
curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer {token}" \
  -d '{"amount": 1000, "term": 3}' \
  http://localhost:8000/api/loans
```

### Making a Repayment
```bash
curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer {token}" \
  -d '{"amount": 333.33}' \
  http://localhost:8000/api/loans/1/repayments
```

## Business Rules
- Loans can be created for either 3 or 6 month terms
- Repayments are scheduled weekly
- For a 3 month term, there are 12 scheduled weekly repayments
- For a 6 month term, there are 24 scheduled weekly repayments
- The loan amount is divided equally among the scheduled repayments
- A loan is marked as fully paid when the total received repayments equal or exceed the loan amount
- Scheduled repayments are marked as overdue if they're not fully paid by their due date

## Application Logic

### Loan Creation
When a loan is created:
1. A new Loan record is created with the specified amount and term
2. Weekly scheduled repayments are automatically generated
3. Each scheduled repayment has an equal portion of the loan amount

### Making Repayments
When a repayment is made:
1. A ReceivedRepayment record is created
2. The associated ScheduledRepayment status is updated (if applicable)
3. If the loan is fully paid, its status is updated to "paid"

## Code Structure
- **Models**: Define the database structure and relationships
- **Controllers**: Handle incoming HTTP requests and return responses
- **Services**: Contain business logic for loan and repayment processing
- **Routes**: Define the API endpoints

## Testing
Run the test suite with:
```bash
php artisan test
```

## License
This project is licensed under the MIT License - see the LICENSE file for details.