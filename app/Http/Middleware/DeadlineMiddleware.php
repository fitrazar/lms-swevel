<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Notifications\AssignmentNotification;
use Symfony\Component\HttpFoundation\Response;

class DeadlineMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $enrollments = Enrollment::where('status', 'active')
            ->get();

        foreach ($enrollments as $enrollment) {
            foreach ($enrollment->course->topics as $topic) {
                $assignment = $topic->material->assignment;
                if ($assignment) {
                    $activatedAt = Carbon::parse($enrollment->activated_at);

                    $deadline = $activatedAt->addDays((int) $assignment->deadline)->endOfDay();

                    if ($deadline->isTomorrow()) {
                        $user = $enrollment->participant->user;

                        $existingNotification = $user->notifications()
                            ->whereDate('created_at', Carbon::today())
                            ->whereJsonContains('data', ['type' => 'assignment'])
                            ->whereJsonContains('data', ['id' => $assignment->id])
                            ->whereJsonContains('data', ['participant_id' => $enrollment->participant->id])
                            ->first();

                        if (!$existingNotification) {
                            $user->notify(new AssignmentNotification($assignment));
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
