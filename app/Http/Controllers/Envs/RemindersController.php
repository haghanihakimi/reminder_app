<?php

namespace App\Http\Controllers\Envs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Reminder;
use App\Models\Sharing;
use App\Http\Resources\UserNormalResource;
use App\Http\Resources\RemindersNormalResource;
use Inertia\Inertia;
use Carbon\Carbon;

class RemindersController extends Controller
{
    /**
     * This function compiles & returns VIEW page to "Create New Reminder"
     */
    public function createView () {
        return Inertia::render('Reminders/CreateReminder', [
            'auth' => [
                'user' => new UserNormalResource(Auth::guard('web')->user()),
                'logged' => Auth::guard('web')->check(),
                'verified' => Auth::guard('web')->user()->hasVerifiedEmail(),
            ],
        ]);
    }

    /**
     * This function compiles & returns Reminder Edit view page to "Edit" existing "Reminder"
     */
    public function editIndex ($reminder) {
        $reminders = new RemindersNormalResource(Auth::guard('web')->user()->reminder->where('pid', $reminder)->firstOrFail());

        if (!empty($reminders)) {
            return Inertia::render('Reminders/ModifyReminder', [
                'auth' => [
                    'user' => new UserNormalResource(Auth::guard('web')->user()),
                    'logged' => Auth::guard('web')->check(),
                    'verified' => Auth::guard('web')->user()->hasVerifiedEmail(),
                ],
            ]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * This function compiles & returns "Ignore Action" view page to "Ignore" existing "Reminder"
     */
    public function ignoreIndex ($reminder) {
        $reminders = new RemindersNormalResource(Auth::guard('web')->user()->reminder->where('pid', $reminder)->firstOrFail());
        
        if (!empty($reminders)) {
            return Inertia::render('Reminders/IgnoreReminder', [
                'auth' => [
                    'user' => new UserNormalResource(Auth::guard('web')->user()),
                    'logged' => Auth::guard('web')->check(),
                    'verified' => Auth::guard('web')->user()->hasVerifiedEmail(),
                ],
            ]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * This function compiles & returns Delete Action view page to "Delete" existing "Reminder"
     */
    public function deleteIndex ($reminder) {
        $reminders = new RemindersNormalResource(Auth::guard('web')->user()->reminder->where('pid', $reminder)->firstOrFail());
        
        if (!empty($reminders)) {
            return Inertia::render('Reminders/DeleteReminder', [
                'auth' => [
                    'user' => new UserNormalResource(Auth::guard('web')->user()),
                    'logged' => Auth::guard('web')->check(),
                    'verified' => Auth::guard('web')->user()->hasVerifiedEmail(),
                ],
            ]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * This function compiles & returns Ignored Reminders list view page to view and interact with "ignored reminders"
     */
    public function ignoredsView () {
        return Inertia::render('Reminders/IgnoredList', [
            'auth' => [
                'user' => new UserNormalResource(Auth::guard('web')->user()),
                'logged' => Auth::guard('web')->check(),
                'verified' => Auth::guard('web')->user()->hasVerifiedEmail()
            ],
        ]);
    }

    /**
     * This function compiles & returns Shared Reminder as "Private" view page let users view shared reminder
     */
    public function sharedView ($reminder, Request $request) {
        $currentReminder = Reminder::where('link', url()->full())->first();

        if ($request->hasValidSignature() && !empty($currentReminder)) {
            return Inertia::render('Reminders/ReminderUserView', [
                'auth' => [
                    'logged' => Auth::guard('web')->check(),
                    'canEdit' => (Auth::guard('web')->check() && $currentReminder->user_id === Auth::guard('web')->user()->id) ? true : false
                ],
                'reminder' => new RemindersNormalResource ($currentReminder),
            ]);
        }
        return redirect()->route('login');
    }

    /**
     * This function compiles & returns Shared Reminder as "Public" view page let current user & other users view shared reminder
     */
    public function reminderUserView ($reminder) {
        $reminders = new RemindersNormalResource(Auth::guard('web')->user()->reminder->where('pid', $reminder)->firstOrFail());

        if (!empty($reminders)) {
            return Inertia::render('Reminders/ReminderUserView', [
                'auth' => [
                    'user' => new UserNormalResource(Auth::guard('web')->user()),
                    'logged' => Auth::guard('web')->check(),
                    'canEdit' => (Auth::guard('web')->check() && $reminders->user_id === Auth::guard('web')->user()->id) ? true : false
                ],
                'reminder' => $reminders,
            ]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * This function returns list of CURRENT USER's reminders 
     */
    public function listReminders () {
        $remindersList = Auth::guard('web')->user()->reminder()->orderBy('due_date', 'desc')->paginate(15)
        ->through(fn($reminder) => [
            'id' => $reminder->pid,
            'title' => $reminder->reminder_title,
            'descriptions' => !empty($reminder->reminder_descriptions) ? $reminder->reminder_descriptions : '',
            'privacy' => $reminder->privacy,
            'due' => $reminder->due_date,
            'link' => $reminder->link,
        ]);

        return response()->json($remindersList);
    }

    /**
     * This function returns list of ignored reminders which are marked by CURRENT USER's 
     */
    public function ignoredsList () {
        $reminders = Auth::guard('web')->user()->reminder()->onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(25)
        ->through(fn($reminder) => [
            'id' => $reminder->pid,
            'title' => $reminder->reminder_title,
            'descriptions' => !empty($reminder->reminder_descriptions) ? $reminder->reminder_descriptions : '',
            'privacy' => $reminder->privacy,
            'due' => $reminder->due_date,
            'link' => $reminder->link,
        ]);

        return response()->json($reminders);
    }

    /**
     * A VOID function to create new Reminder for CURRENT USER
     */
    public function create (Request $request) {
        $this->createValidation($request);

        $now  = Carbon::now();
            
        $pid = Str::uuid()->toString();

        $reminder = Reminder::create([
            'pid' => $pid,
            'user_id' => Auth::guard('web')->user()->id,
            'reminder_title' => $request->title,
            'reminder_descriptions' => $request->descriptions,
            'privacy' => $request->privacy,
            'due_date' => Carbon::parse($request->due)->format('Y-m-d H:i:s'),
            'link' => ($request->privacy === 'shared') ? $this->generateLink($request->due, $pid) : null
        ]);

        return back()->with('status', ['message' => 'New reminder successfully created.', 'reminder' => new RemindersNormalResource($reminder)]);
    }

    /**
     * A VOID function to PERMANENTLY DELETE specific/chosen reminder for CURRENT USER
     */
    public function destroy ($origin, $reminder) {
        $reminders = Auth::guard('web')->user()->reminder()->withTrashed()->where('pid', $reminder)->first();
        
        if (!empty($reminders)) {
        
            $reminders->forceDelete();
            
            if ($origin === 'ignore') {
                return back()->with('status', "You successfully deleted a reminder forever!");
            }
            return redirect()->route('dashboard')->with('status', "You successfully deleted a reminder forever!");
        }
            
        return back()->with('status', "This reminder does not exist in our record.");
    }

    /**
     * A VOID function to TEMPORARY DELETE specific/chosen reminder for CURRENT USER.
     * All temporary deleted OR marked reminders as DELETED will be completely deleted after 7 days
     * Temporary deleted reminders which are privately or publicly shared will be invisible to others!
     */
    public function ignore ($reminder) {
        $reminders = Auth::guard('web')->user()->reminder->where('pid', $reminder)->first();
        
        if (!empty($reminders)) {
        
            $reminders->delete();
            
            return redirect()->route('dashboard')->with('status', "You successfully moved a reminder to trash!");
        }
            
        return back()->with('status', "This reminder does not exist in our record.");
    }

    /**
     * A VOID function to RESTORE temporary deleted specific/chosen reminder for CURRENT USER
     */
    public function restore ($reminder) {
        $reminders = Auth::guard('web')->user()->reminder()->onlyTrashed()->where('pid', $reminder)->first();
        
        if (!empty($reminders)) {
            $reminders->restore();
            
            return back()->with('status', ["code" => 200, "message" => "Reminder has been restored successfully!"]);
        }
            
        return back()->with('status', ["code" => 404, "message" => "This reminder does not exist in our record."]);
    }

    /**
     * A VOID function to Update chosen Reminder data
     */
    public function saveChanges ($reminder, Request $request) {
        $reminders = Auth::guard('web')->user()->reminder->where('pid', (Str::isUuid($request->id)) ? $request->id : null)->first();
                
        if (!empty($reminders)) {
            $reminders->update([
                'reminder_title' => $request->title,
                'privacy' => $request->privacy,
                'due_date' => Carbon::parse($request->due)->format('Y-m-d H:i:s'),
                'link' => $this->generateLink ($request->privacy, $reminders->link, $reminders->due_date, $request->due, $reminders->pid),
                'reminder_descriptions' => $request->descriptions
            ]);
            
            if ($reminders->save()) {
                return back()->with('status', ['message' => 'Changes saved successfully!', 'link' => $reminders->link, 'reminder' => new RemindersNormalResource($reminders)]);
            }

            return back()->with('status', ['message' => "Something went wrong with saving changes. Please try again later.", 'link' => null]);
        }
        return back()->with('status', ['message' => "This reminder does not exist in our record!", 'link' => null]);
    }

    /**
     * This function returns searched reminder. It finds any possible matches to given keyword
     * @param array
     * @return response
     */
    public function searchReminders (Request $request) {
        $results = Auth::guard('web')->user()
        ->reminder()
        ->when($request->keywords, function ($query, $search) {
            $query->where('reminder_title', 'like', "%{$search}%")
            ->orWhere('reminder_descriptions', 'like', "%{$search}%");
        })
        ->paginate(15)
        ->through(fn($reminder) => [
            'id' => $reminder->pid,
            'title' => $reminder->reminder_title,
            'descriptions' => !empty($reminder->reminder_descriptions) ? $reminder->reminder_descriptions : '',
            'privacy' => $reminder->privacy,
            'due' => $reminder->due_date,
            'link' => $reminder->link,
        ]);

        return response()->json($results);
    }

    /**
     * This function generates and returns a hashed/unique URL for privately shared reminder
     * @param string
     */
    private function generateLink ($privacy, $link, $originTime, $due, $pid) {
        if ($privacy === 'shared' && (empty($link) || $originTime !== $due)) {
            return URL::temporarySignedRoute(
                'share.share.view', now()->addMinutes(Carbon::parse($due)->diffInMinutes()), ['reminder' => $pid]
            );
        }

        if ($privacy === 'shared' && !empty($link) && $originTime === $due) {
            return $link;
        }

        if ($privacy === 'private') {
            return null;
        }

        return null;
    }

    private function savingValidation ($request) {
        return $this->validate($request, [
            'id' => ['required', 'string', 'uuid'],
            'title' => ['required', 'string', 'min:5', 'max:300'],
            'due' => ['required'],
            'descriptions' => ['nullable', 'string', 'max:850']
        ]);
    }

    private function createValidation ($request) {
        return $this->validate($request, [
            'title' => ['required', 'string', 'min:5', 'max:300'],
            'due' => ['required'],
            'descriptions' => ['nullable', 'string', 'max:850']
        ]);
    }
}
