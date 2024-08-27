<?php


namespace App\Http\Controllers;
require __DIR__ . '/../../../vendor/autoload.php';

use App\Models\FreeLessons;
use App\Models\GuestUsers;
use App\Models\Cources;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Calendar as Google_Service_Calendar;
use Google\Service\Calendar\Event as Google_Service_Calendar_Event;
use Google_Service_Calendar_ConferenceData;
use Google_Service_Calendar_CreateConferenceRequest;
use Google_Service_Calendar_ConferenceSolutionKey;
use Google\Service\Calendar\EventDateTime as Google_Service_Calendar_EventDateTime;



use Spatie\GoogleCalendar\GoogleCalendar;

use Illuminate\Http\Request;

class FreeLessonsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if($request->has('id')){
        $freeLesson=FreeLessons::find($request->id);
        $user=GuestUsers::find($request->userId);
        $course=Cources::find($request->courseId);

        if(!$freeLesson){
            $jsonData=['message'=>'lesson not found'];
        }
        else{
           $jsonData=['status'=>'successful',
                      'course name:' =>$course->title,
                      'guest user name :'=>$user->firstName . $user->lastName,
                      'data'=>$freeLesson                            
                     ];
            }
        }
        else{
            $freeLesson = FreeLessons::paginate(5);
            $jsonData=['status'=>'success',
                        'course name:' =>$course->title,
                        'guest user name :'=>$user->firstName . $user->lastName,
                        'data'=>$freeLesson            
                      ];
            }
        return response()->json([$jsonData]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'userId' => 'required|integer',
            'courseId' => 'required|integer',
            'numberOfMonth' => 'required|integer',
            'dayOfMonth' => 'required|integer',
            'startTime' => 'required|date_format:H:i:s',
            'endTime' => 'required|date_format:H:i:s',
        ]);
        $user=GuestUsers::find($request->userId);
        $course=Cources::find($request->courseId);
        
        $freeLesson = FreeLessons::create([
            'userId' => $user->id,
            'courseId' => $course->id,
            'numberOfMonth' => $request->numberOfMonth,
            'dayOfMonth' => $request->dayOfMonth,
            'startTime' => $request->startTime,
            'endTime' => $request->endTime,
            'meetUrl' =>$this->createEvent()
        ]);

        return response()->json([
            'message' => 'free lesson created successfuly',
            'course name:' =>$course->title,
            'guest user name :'=>$user->firstName . $user->lastName,
             'data' => $freeLesson]
            , 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FreeLessons $freeLessons)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FreeLessons $freeLessons)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'numberOfMonth' => 'required',
            'dayOfMonth' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
            'meetUrl'=>'required'
        ]);
            $freeLesson=FreeLessons::find($id);

            $freeLesson->numberOfMonth=$request->numberOfMonth;
            $freeLesson->dayOfMonth=$request->dayOfMonth;
            $freeLesson->startTime=$request->startTime;
            $freeLesson->endTime=$request->endTime;
            $freeLesson->meetUrl=$request->meetUrl;
            $freeLesson->save();

            return response()->json([
                    'message'=>'success',
                    'data'=>$freeLesson
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id'=>'required'
        ]);
        $freeLesson = FreeLessons::find($request->id);
        if (!$freeLesson) {
            return response()->json(['message' => 'free Lesson not found'], 404);
        }
        $freeLesson->forceDelete();
        return response()->json(['message' => 'free Lesson deleted']);
    }
    
    public function createEvent()
    {
        $client = new Client();
        $accessToken = 'ya29.a0AcM612y4dKHqxu-HtzGVqPzTeVRsyh3PRyJ5fKLmo6_6Lf-xWrcpkmafKcFg_3XFmNWSgTKsergXiLtjxQV13RNPR1_F3lSP3tiyHL9DrXi-X63BPQeiPIfCp43UwM2dm6GyyXeQ_MESG-objPb0Edj4ZmC47GzxTFjriGiNaCgYKAdcSARESFQHGX2Mik9voxkzolJO6d5tX7I18iw0175';
        $refreshToken='1//05WF8ttP0p-RPCgYIARAAGAUSNwF-L9IreY4FeCImPddXDfhdL5aF85hsaJMYQvccut5VbvhoUBYtE3s8aP2X38hBcPdJMcUYvQg';
        $client->setAccessToken([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in' => 3600, // مدة صلاحية الـ access token بالثواني
        ]);
        
        //$client->setAccessToken();
        $client->setApplicationName('laravelcalendar');
        $client->setClientId('691463698835-fum9nttru55k9k7pfvf7j9ef51slslod.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('https://www.google.com');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
        }

        $service = new Google_Service_Calendar($client);

        $event = new Google_Service_Calendar_Event();
        $event->setSummary('حدث جديد');
        $event->setDescription('Event description');
        $event->setStart(new Google_Service_Calendar_EventDateTime(array(
        'dateTime' => Carbon::now()->toRfc3339String(),
        'timeZone' => 'America/Los_Angeles',
        )));
        $event->setEnd(new Google_Service_Calendar_EventDateTime(array(
        'dateTime' => Carbon::now()->addHour()->toRfc3339String(),
        'timeZone' => 'America/Los_Angeles',
        )));

        $conference = new Google_Service_Calendar_ConferenceData();
        $conferenceRequest = new Google_Service_Calendar_CreateConferenceRequest();
        $conferenceSolutionKey = new Google_Service_Calendar_ConferenceSolutionKey();

        $conferenceSolutionKey->setType('hangoutsMeet');
        $conferenceRequest->setConferenceSolutionKey($conferenceSolutionKey);
        $conferenceRequest->setRequestId('first'); // يجب أن يكون معرفًا فريدًا لكل طلب

        $conference->setCreateRequest($conferenceRequest);
        $event->setConferenceData($conference);

        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event, array('conferenceDataVersion' => 1));

        // عرض تفاصيل الحدث بما في ذلك رابط Google Meet
        //printf('Event created: %s\n', $event->htmlLink);
        //printf('Google Meet URL: %s\n', $event->getHangoutLink());
        $meetUrl=$event->getHangoutLink();
        return  $meetUrl;
           
    }

}
