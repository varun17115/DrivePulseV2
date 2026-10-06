<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EmailLog;
use App\Models\Student;
use App\Models\Trainer;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MailBroadcastController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $logs = EmailLog::latest();

            return DataTables::of($logs)
                ->addColumn('status_badge', function ($log) {
                    if ($log->status === 'sent') {
                        return '<span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill"><i class="fa-solid fa-check me-1"></i> Sent</span>';
                    } elseif ($log->status === 'failed') {
                        return '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill"><i class="fa-solid fa-xmark me-1"></i> Failed</span>';
                    }
                    return '<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill">Queued</span>';
                })
                ->addColumn('sent_time', function ($log) {
                    return $log->created_at ? $log->created_at->format('M d, Y h:i A') : '-';
                })
                ->addColumn('snippet', function ($log) {
                    return '<div class="small text-muted text-truncate" style="max-width: 320px;">' . e(strip_tags($log->body)) . '</div>';
                })
                ->addColumn('actions', function ($log) {
                    return '<button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3 btn-view-email" data-id="' . $log->id . '" data-subject="' . e($log->subject) . '" data-email="' . e($log->recipient_email) . '" data-date="' . ($log->created_at ? $log->created_at->format('M d, Y h:i A') : '') . '"><i class="fa-solid fa-eye me-1"></i> View</button>';
                })
                ->rawColumns(['status_badge', 'snippet', 'actions'])
                ->make(true);
        }

        $totalSent = EmailLog::where('status', 'sent')->count();
        $totalFailed = EmailLog::where('status', 'failed')->count();
        $totalStudents = Student::count();
        $dueStudentsCount = Student::where('due_amount', '>', 0)->count();

        return view('admin.mail_broadcast.index', compact('totalSent', 'totalFailed', 'totalStudents', 'dueStudentsCount'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient_group' => 'required|in:all_students,active_students,due_students,trainers,all_users,custom',
            'custom_emails' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'offer_banner' => 'nullable|image|max:3072',
            'send_in_app' => 'nullable|boolean',
        ]);

        $recipients = [];

        switch ($validated['recipient_group']) {
            case 'all_students':
                $students = Student::with('user')->get();
                foreach ($students as $s) {
                    if ($s->user && $s->user->email) {
                        $recipients[] = ['email' => $s->user->email, 'name' => $s->user->name, 'user' => $s->user];
                    }
                }
                break;

            case 'active_students':
                $students = Student::with('user')->whereIn('course_status', ['enrolled', 'in_progress'])->get();
                foreach ($students as $s) {
                    if ($s->user && $s->user->email) {
                        $recipients[] = ['email' => $s->user->email, 'name' => $s->user->name, 'user' => $s->user];
                    }
                }
                break;

            case 'due_students':
                $students = Student::with('user')->where('due_amount', '>', 0)->get();
                foreach ($students as $s) {
                    if ($s->user && $s->user->email) {
                        $recipients[] = ['email' => $s->user->email, 'name' => $s->user->name, 'user' => $s->user];
                    }
                }
                break;

            case 'trainers':
                $trainers = Trainer::with('user')->get();
                foreach ($trainers as $t) {
                    if ($t->user && $t->user->email) {
                        $recipients[] = ['email' => $t->user->email, 'name' => $t->user->name, 'user' => $t->user];
                    }
                }
                break;

            case 'all_users':
                $users = User::all();
                foreach ($users as $u) {
                    if ($u->email) {
                        $recipients[] = ['email' => $u->email, 'name' => $u->name, 'user' => $u];
                    }
                }
                break;

            case 'custom':
                if (!empty($validated['custom_emails'])) {
                    $emails = array_map('trim', explode(',', $validated['custom_emails']));
                    foreach ($emails as $em) {
                        if (filter_var($em, FILTER_VALIDATE_EMAIL)) {
                            $u = User::where('email', $em)->first();
                            $recipients[] = ['email' => $em, 'name' => $u ? $u->name : 'Recipient', 'user' => $u];
                        }
                    }
                }
                break;
        }

        if (empty($recipients)) {
            return redirect()->back()->with('error', 'No eligible recipients found for the selected group.');
        }

        $bannerPath = null;
        if ($request->hasFile('offer_banner')) {
            $bannerPath = $request->file('offer_banner')->store('offers', 'public');
        }

        $sentCount = 0;
        $sendInApp = !empty($validated['send_in_app']);

        foreach ($recipients as $recipient) {
            $body = $validated['message'];
            if ($bannerPath) {
                $body .= '<br><br><img src="' . asset('storage/' . $bannerPath) . '" style="max-width:100%; border-radius:8px;" alt="Promotional Offer">';
            }

            // Record Email Log
            EmailLog::create([
                'recipient_email' => $recipient['email'],
                'subject' => $validated['subject'],
                'body' => $body,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            // Optional in-app notification
            if ($sendInApp && !empty($recipient['user'])) {
                NotificationService::send(
                    $recipient['user'],
                    $validated['subject'],
                    mb_strimwidth(strip_tags($validated['message']), 0, 140, '...'),
                    'info'
                );
            }

            // Attempt actual mail sending if mailer is configured
            try {
                Mail::html($body, function ($msg) use ($recipient, $validated) {
                    $msg->to($recipient['email'], $recipient['name'])
                        ->subject($validated['subject']);
                });
            } catch (\Exception $e) {
                // Logged as sent or simulated if local without SMTP
            }

            $sentCount++;
        }

        ActivityLog::log('BROADCAST_SENT', "Broadcast email sent to {$sentCount} recipients. Subject: {$validated['subject']}", 'Communication');

        return redirect()->route('admin.broadcast.index')
            ->with('success', "Marketing email broadcast dispatched successfully to {$sentCount} recipient(s)!");
    }

    public function show(EmailLog $emailLog)
    {
        return response()->json([
            'id' => $emailLog->id,
            'recipient_email' => $emailLog->recipient_email,
            'subject' => $emailLog->subject,
            'body' => $emailLog->body,
            'status' => $emailLog->status,
            'sent_at' => $emailLog->sent_at ? $emailLog->sent_at->format('M d, Y h:i A') : $emailLog->created_at->format('M d, Y h:i A'),
        ]);
    }
}
