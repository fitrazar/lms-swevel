<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Assignment;
use App\Models\Enrollment;
use Illuminate\Console\Command;
use App\Notifications\AssignmentNotification;

class SendDeadlineNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignment:send-deadline-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for assignments that are due in less than 1 day';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $enrollments = Enrollment::where('status', 'active')
            ->get();

        foreach ($enrollments as $enrollment) {
            $assignment = $enrollment->course
                ->topics()
                ->first()
                ->material
                ->assignment;

            if ($assignment) {
                $activatedAt = Carbon::parse($enrollment->activated_at);

                $deadline = $activatedAt->addDays((int) $assignment->deadline)->endOfDay();

                if ($deadline->isTomorrow()) {
                    $enrollment->participant->user->notify(new AssignmentNotification($assignment));
                }
            }
        }

        $this->info('Deadline notifications sent.');
    }

}
