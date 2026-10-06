<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrainerRequest;
use App\Http\Requests\UpdateTrainerRequest;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class TrainerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $trainers = Trainer::with('user')->select('trainers.*');

            return DataTables::of($trainers)
                ->addColumn('name', function ($trainer) {
                    return $trainer->user ? $trainer->user->name : 'N/A';
                })
                ->addColumn('email', function ($trainer) {
                    return $trainer->user ? $trainer->user->email : 'N/A';
                })
                ->addColumn('phone', function ($trainer) {
                    return $trainer->user ? ($trainer->user->phone ?? 'N/A') : 'N/A';
                })
                ->addColumn('experience', function ($trainer) {
                    return $trainer->experience_years . ' yrs';
                })
                ->addColumn('hourly_rate_formatted', function ($trainer) {
                    return '₹' . number_format($trainer->hourly_rate, 2) . '/hr';
                })
                ->addColumn('status_badge', function ($trainer) {
                    $badges = [
                        'available' => 'bg-success',
                        'busy' => 'bg-warning',
                        'on_leave' => 'bg-secondary',
                        'inactive' => 'bg-danger',
                    ];
                    $badgeClass = $badges[$trainer->status] ?? 'bg-secondary';
                    $statusLabel = ucwords(str_replace('_', ' ', $trainer->status));
                    return '<span class="badge ' . $badgeClass . '">' . $statusLabel . '</span>';
                })
                ->addColumn('actions', function ($trainer) {
                    $showUrl = route('admin.trainers.show', $trainer->id);
                    $editUrl = route('admin.trainers.edit', $trainer->id);
                    $deleteUrl = route('admin.trainers.destroy', $trainer->id);

                    return '
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="' . $showUrl . '" class="btn btn-sm btn-outline-info" title="View"><i class="fa-solid fa-eye"></i></a>
                            <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-url="' . $deleteUrl . '" data-name="' . ($trainer->user ? e($trainer->user->name) : 'Trainer') . '" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        return view('admin.trainers.index');
    }

    public function create()
    {
        return view('admin.trainers.create');
    }

    public function store(StoreTrainerRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'status' => 'active',
            ]);

            $user->assignRole('trainer');

            Trainer::create([
                'user_id' => $user->id,
                'employee_id' => $validated['employee_id'],
                'license_number' => $validated['license_number'],
                'license_expiry' => $validated['license_expiry'],
                'experience_years' => $validated['experience_years'],
                'specialization' => $validated['specialization'] ?? null,
                'hourly_rate' => $validated['hourly_rate'],
                'status' => $validated['status'],
            ]);

            ActivityLog::log('TRAINER_ONBOARDED', "Onboarded trainer {$user->name} ({$validated['employee_id']})", 'Trainers');

            DB::commit();

            return redirect()->route('admin.trainers.index')->with('success', 'Trainer onboarded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to onboard trainer: ' . $e->getMessage());
        }
    }

    public function show(Trainer $trainer)
    {
        $trainer->load(['user', 'bookings.student.user', 'bookings.vehicle', 'progressEvaluations.student.user']);
        return view('admin.trainers.show', compact('trainer'));
    }

    public function edit(Trainer $trainer)
    {
        $trainer->load('user');
        return view('admin.trainers.edit', compact('trainer'));
    }

    public function update(UpdateTrainerRequest $request, Trainer $trainer)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $user = $trainer->user;
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            $trainer->update([
                'employee_id' => $validated['employee_id'],
                'license_number' => $validated['license_number'],
                'license_expiry' => $validated['license_expiry'],
                'experience_years' => $validated['experience_years'],
                'specialization' => $validated['specialization'] ?? null,
                'hourly_rate' => $validated['hourly_rate'],
                'status' => $validated['status'],
            ]);

            ActivityLog::log('TRAINER_UPDATED', "Updated trainer details for {$user->name}", 'Trainers');

            DB::commit();

            return redirect()->route('admin.trainers.index')->with('success', 'Trainer profile updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update trainer: ' . $e->getMessage());
        }
    }

    public function destroy(Trainer $trainer)
    {
        try {
            $user = $trainer->user;
            $name = $user ? $user->name : $trainer->employee_id;

            ActivityLog::log('TRAINER_DELETED', "Deleted trainer {$name}", 'Trainers');

            if ($user) {
                $user->delete();
            } else {
                $trainer->delete();
            }

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Trainer deleted successfully!']);
            }

            return redirect()->route('admin.trainers.index')->with('success', 'Trainer deleted successfully!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete trainer: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to delete trainer: ' . $e->getMessage());
        }
    }

    public function exportCsv()
    {
        $trainers = Trainer::with('user')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=drivepulse_trainers_" . date('Ymd_His') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Employee ID', 'Name', 'Email', 'Phone', 'License No', 'License Expiry', 'Experience (Years)', 'Specialization', 'Hourly Rate (₹)', 'Status'];

        $callback = function () use ($trainers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($trainers as $t) {
                fputcsv($file, [
                    $t->employee_id,
                    $t->user->name ?? '',
                    $t->user->email ?? '',
                    $t->user->phone ?? '',
                    $t->license_number,
                    $t->license_expiry ? $t->license_expiry->format('Y-m-d') : '',
                    $t->experience_years,
                    $t->specialization,
                    number_format($t->hourly_rate, 2),
                    $t->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
