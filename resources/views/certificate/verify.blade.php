<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification - DrivePulse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cert-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            max-width: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="cert-card card mx-auto bg-white p-4">
            <div class="text-center mb-4">
                <div class="text-primary mb-2">
                    <i class="fa-solid fa-car-side fa-3x"></i>
                </div>
                <h4 class="fw-bold">DrivePulse</h4>
                <p class="text-muted small">Official Certificate Verification</p>
            </div>

            @if($certificate->status === 'issued')
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="fa-solid fa-circle-check fa-2x me-3"></i>
                    <div>
                        <h6 class="alert-heading mb-0 fw-bold">Valid & Verified Certificate</h6>
                        <span class="small">This certificate is authentic and officially issued.</span>
                    </div>
                </div>
            @else
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="fa-solid fa-circle-xmark fa-2x me-3"></i>
                    <div>
                        <h6 class="alert-heading mb-0 fw-bold">Revoked Certificate</h6>
                        <span class="small">This certificate is no longer valid.</span>
                    </div>
                </div>
            @endif

            <div class="list-group list-group-flush mb-4">
                <div class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Student Name</span>
                    <span class="fw-bold">{{ $certificate->student->user->name ?? 'N/A' }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Certificate No.</span>
                    <span class="fw-bold text-primary">{{ $certificate->certificate_number }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Course</span>
                    <span class="fw-bold">{{ $certificate->course_name }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Grade</span>
                    <span class="badge bg-primary fs-6">{{ $certificate->grade }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Completion Date</span>
                    <span class="fw-semibold">{{ $certificate->completion_date->format('M d, Y') }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Issued On</span>
                    <span class="fw-semibold">{{ $certificate->issue_date->format('M d, Y') }}</span>
                </div>
            </div>

            <div class="text-center mt-2">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">Back to Homepage</a>
            </div>
        </div>
    </div>
</body>
</html>
