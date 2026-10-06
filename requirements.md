# DrivePulse – Driving School Management System

## Version
1.0

## Project Type
Web-Based Driving School Management System

---

# 1. Project Overview

DrivePulse is a comprehensive web-based Driving School Management System developed to automate and streamline the day-to-day operations of driving schools. The system eliminates manual paperwork and provides a centralized platform for managing students, trainers, vehicles, bookings, attendance, payments, mock tests, certificates, notifications, and reports.

The objective of the system is to improve operational efficiency, increase data accuracy, provide better communication among stakeholders, and enhance the learning experience for students.

---

# 2. Objectives

- Automate driving school operations
- Reduce manual paperwork
- Centralize student and trainer records
- Improve scheduling and booking management
- Track student progress effectively
- Manage vehicle allocation and maintenance
- Automate payment and certificate generation
- Generate analytical reports and insights
- Enhance communication through notifications and emails

---

# 3. User Roles

## Admin

### Responsibilities
- Manage Students
- Manage Trainers
- Manage Vehicles
- Manage Bookings
- Manage Payments
- Manage Mock Tests
- Manage Certificates
- Manage Notifications
- Generate Reports
- Configure System Settings

---

## Trainer

### Responsibilities
- View Schedule
- View Assigned Students
- Mark Attendance
- Update Student Progress
- Evaluate Mock Tests
- Provide Feedback

---

## Student

### Responsibilities
- Register and Login
- Book Driving Lessons
- View Schedule
- View Attendance
- View Progress
- Take Mock Tests
- Make Payments
- Download Certificates
- Receive Notifications

---

# 4. Functional Modules

---

## 4.1 Authentication Module

### Features
- Login
- Logout
- Forgot Password
- Reset Password
- Change Password
- Role Based Access Control

### Roles
- Admin
- Trainer
- Student

---

## 4.2 Student Management Module

### Features
- Add Student
- Edit Student
- Delete Student
- View Student Profile
- Search Student
- Filter Student Records

### Student Details
- Full Name
- Email
- Phone Number
- Address
- Date of Birth
- Gender
- License Type
- Admission Date
- Status

---

## 4.3 Trainer Management Module

### Features
- Add Trainer
- Edit Trainer
- Delete Trainer
- Assign Students
- View Assigned Students

### Trainer Details
- Name
- Email
- Phone Number
- Experience
- Specialization
- Salary
- Status

---

## 4.4 Vehicle Management Module

### Features
- Add Vehicle
- Edit Vehicle
- Delete Vehicle
- Assign Vehicle
- View Vehicle History

### Vehicle Details
- Vehicle Number
- Vehicle Model
- Vehicle Type
- Registration Date
- Insurance Expiry Date
- Status

---

## 4.5 Vehicle Maintenance Module

### Features
- Add Service Record
- Update Service Details
- View Service History
- Track Maintenance Cost
- Insurance Expiry Alerts
- Service Due Reminders

---

## 4.6 Lesson Booking Module

### Features
- Create Booking
- Approve Booking
- Cancel Booking
- Reschedule Booking
- Assign Trainer
- Assign Vehicle

### Booking Status
- Pending
- Approved
- Completed
- Cancelled

---

## 4.7 Attendance Management Module

### Trainer Features
- Mark Attendance
- Update Attendance
- Add Remarks

### Admin Features
- View Attendance Records
- Generate Attendance Reports

### Student Features
- View Attendance History

---

## 4.8 Progress Tracking Module

### Trainer Evaluation Areas
- Steering Control
- Parking Skills
- Reverse Driving
- Traffic Awareness
- Highway Driving
- Road Safety

### Features
- Assign Scores
- Add Remarks
- Track Lesson Performance
- Generate Progress Reports

---

## 4.9 Payment Management Module

### Features
- Record Payments
- View Payment History
- Generate Receipts
- Track Pending Payments

### Payment Status
- Paid
- Partial
- Pending

---

## 4.10 Mock Test Module

### Features
- Create Mock Tests
- Manage Question Bank
- Attempt Online Tests
- Auto Calculate Results
- Store Test History

### Categories
- Traffic Signs
- Traffic Rules
- Road Safety
- Driving Regulations

---

## 4.11 Certificate Management Module

### Features
- Generate Certificates
- Download Certificates
- Verify Certificates
- Revoke Certificates

### Certificate Details
- Certificate Number
- Issue Date
- QR Code
- Verification URL

---

## 4.12 Notification Module

### Features
- Booking Notifications
- Payment Reminders
- Mock Test Notifications
- Certificate Notifications
- Attendance Alerts

### Channels
- In-App Notifications
- Email Notifications

---

## 4.13 Email Management Module

### Features
- Registration Confirmation Email
- Booking Confirmation Email
- Payment Receipt Email
- Certificate Issued Email
- Reminder Emails

---

## 4.14 Reports Module

### Reports
- Student Report
- Trainer Report
- Attendance Report
- Payment Report
- Mock Test Report
- Vehicle Report
- Certificate Report

### Export Formats
- PDF
- Excel

---

## 4.15 Dashboard & Analytics Module

### Admin Dashboard

#### Statistics
- Total Students
- Total Trainers
- Total Vehicles
- Active Bookings
- Pending Payments
- Certificates Issued

#### Charts
- Monthly Admissions
- Revenue Analysis
- Attendance Trends
- Mock Test Performance
- Vehicle Usage Statistics

---

# 5. Database Dictionary

---

## users

Stores user authentication information.

| Field | Description |
|---------|---------|
| id | User ID |
| name | User Name |
| email | User Email |
| password | Encrypted Password |
| phone | Contact Number |
| role | Admin/Trainer/Student |
| status | Active/Inactive |
| created_at | Creation Date |

---

## students

Stores student details.

| Field | Description |
|---------|---------|
| id | Student ID |
| user_id | Reference User ID |
| address | Student Address |
| dob | Date of Birth |
| gender | Gender |
| license_type | License Category |
| join_date | Admission Date |
| status | Active/Inactive |

---

## trainers

Stores trainer information.

| Field | Description |
|---------|---------|
| id | Trainer ID |
| user_id | Reference User ID |
| experience | Experience in Years |
| specialization | Area of Expertise |
| salary | Salary |
| status | Active/Inactive |

---

## vehicles

Stores vehicle information.

| Field | Description |
|---------|---------|
| id | Vehicle ID |
| vehicle_no | Registration Number |
| model | Vehicle Model |
| type | Vehicle Type |
| insurance_expiry | Insurance Expiry Date |
| status | Available/Assigned/Maintenance |

---

## vehicle_services

Stores maintenance records.

| Field | Description |
|---------|---------|
| id | Service ID |
| vehicle_id | Vehicle Reference |
| service_date | Service Date |
| cost | Maintenance Cost |
| next_service_date | Next Service Date |
| description | Service Description |

---

## bookings

Stores lesson booking information.

| Field | Description |
|---------|---------|
| id | Booking ID |
| student_id | Student Reference |
| trainer_id | Trainer Reference |
| vehicle_id | Vehicle Reference |
| booking_date | Booking Date |
| time_slot | Training Slot |
| status | Booking Status |

---

## attendance

Stores attendance records.

| Field | Description |
|---------|---------|
| id | Attendance ID |
| student_id | Student Reference |
| trainer_id | Trainer Reference |
| booking_id | Booking Reference |
| date | Attendance Date |
| status | Present/Absent |
| remarks | Additional Notes |

---

## progress

Stores student performance records.

| Field | Description |
|---------|---------|
| id | Progress ID |
| student_id | Student Reference |
| trainer_id | Trainer Reference |
| lesson_no | Lesson Number |
| score | Performance Score |
| remarks | Trainer Feedback |

---

## payments

Stores fee payment information.

| Field | Description |
|---------|---------|
| id | Payment ID |
| student_id | Student Reference |
| booking_id | Booking Reference |
| amount | Amount Paid |
| payment_mode | Payment Method |
| payment_date | Payment Date |
| status | Paid/Pending/Partial |

---

## mock_tests

Stores mock test results.

| Field | Description |
|---------|---------|
| id | Test ID |
| student_id | Student Reference |
| test_name | Test Name |
| total_marks | Total Marks |
| obtained_marks | Obtained Marks |
| attempt_date | Test Date |

---

## mock_test_questions

Stores all mock test questions.

| Field | Description |
|---------|---------|
| id | Question ID |
| question | Question |
| option_a | Option A |
| option_b | Option B |
| option_c | Option C |
| option_d | Option D |
| correct_answer | Correct Answer |
| category | Question Category |

---

## certificates

Stores certificate information.

| Field | Description |
|---------|---------|
| id | Certificate ID |
| student_id | Student Reference |
| certificate_no | Certificate Number |
| issue_date | Issue Date |
| qr_code | QR Code |
| status | Active/Revoked |

---

## certificate_verifications

Stores certificate verification history.

| Field | Description |
|---------|---------|
| id | Verification ID |
| certificate_id | Certificate Reference |
| verified_on | Verification Date |
| ip_address | Verifier IP |

---

## notifications

Stores system notifications.

| Field | Description |
|---------|---------|
| id | Notification ID |
| user_id | User Reference |
| title | Notification Title |
| message | Notification Message |
| type | Notification Type |
| status | Read/Unread |

---

## email_logs

Stores email history.

| Field | Description |
|---------|---------|
| id | Email ID |
| student_id | Student Reference |
| subject | Email Subject |
| message | Email Content |
| sent_at | Sent Date |
| status | Delivery Status |

---

## system_settings

Stores application settings.

| Field | Description |
|---------|---------|
| id | Setting ID |
| school_name | School Name |
| email | Official Email |
| phone | Contact Number |
| address | School Address |
| logo | School Logo |
| updated_at | Last Updated Date |

---

# 6. Non-Functional Requirements

## Security
- Password Hashing
- CSRF Protection
- Input Validation
- Role-Based Access Control

## Performance
- Response Time Below 3 Seconds
- Optimized Database Queries
- AJAX-Based Operations

## Scalability
- Modular Architecture
- Reusable Components
- Maintainable Codebase

## Usability
- Responsive Design
- Mobile Friendly Interface
- User-Friendly Navigation

---

# 7. Future Enhancements

- Mobile Application
- GPS Tracking for Training Sessions
- Online Payment Gateway Integration
- SMS Notifications
- WhatsApp Notifications
- AI-Based Performance Analysis
- Cloud Deployment
- Advanced Analytics Dashboard

---

# 8. Development Phases

## Phase 1
- Authentication
- Student Management
- Trainer Management
- Vehicle Management

## Phase 2
- Booking Management
- Attendance Management
- Progress Tracking

## Phase 3
- Payment Management
- Notifications
- Reports

## Phase 4
- Mock Tests
- Certificate Management
- Dashboard Analytics

---

# 9. Expected Outcomes

- Digital Transformation of Driving School Operations
- Centralized Data Management
- Reduced Manual Work
- Better Student Monitoring
- Improved Trainer Efficiency
- Automated Reporting
- Enhanced User Experience
- Secure and Scalable Platform
