<?php
namespace App\Repository;

use DB;
use Auth;
use App\Tip;
use App\Image;
use App\Client;
use App\Trauma;
use App\MenuLink;
use App\ScaleTip;
use Carbon\Carbon;
use App\ClientPoint;
use App\SleepTracker;
use App\Subscription;
use App\ClientMoodMark;
use App\ExerciseTracker;
use App\ApiScaleQuestion;
use App\TraumaCopingCart;
use App\ApiUserScaleAnswer;
use App\ExerciseTrackerPoint;
use App\ApiScaleQuestionAnswer;
use App\GratitudeQuestionAnswer;
use App\Repository\Interfaces\GeneralRepositoryInterface;

class GeneralRepository implements GeneralRepositoryInterface
{
    private $tip, $trauma, $menu, $image, $question, $answer, $subscription, $exercise, $exercise_point,
            $mood_mark, $trauma_copying, $scale_question_answer, $scale_tips, $sleep_tracker, $points, $gratitude_answer, $client;

    public function __construct(
        Tip $tip,
        Trauma $trauma,
        MenuLink $menu,
        Image $image,
        ApiScaleQuestion $question,
        ApiUserScaleAnswer $answer,
        Subscription $subscription,
        ClientMoodMark $mood_mark,
        TraumaCopingCart $trauma_copying,
        ApiScaleQuestionAnswer $scale_question_answer,
        ScaleTip $scale_tips,
        SleepTracker $sleep_tracker,
        ClientPoint $points,
        GratitudeQuestionAnswer $gratitude_answer,
        Client $client,
        ExerciseTracker $exercise,
        ExerciseTrackerPoint $exercise_point
    )
    {
        $this->tip = $tip;
        $this->trauma = $trauma;
        $this->menu = $menu;
        $this->image = $image;
        $this->question = $question;
        $this->answer = $answer;
        $this->subscription = $subscription;
        $this->mood_mark = $mood_mark;  
        $this->trauma_copying = $trauma_copying;
        $this->scale_question_answer = $scale_question_answer;
        $this->scale_tips = $scale_tips;
        $this->sleep_tracker = $sleep_tracker;
        $this->points = $points;
        $this->gratitude_answer = $gratitude_answer;
        $this->client = $client;
        $this->exercise = $exercise;
        $this->exercise_point = $exercise_point;
    }

    public function getTips()
    {
        return $this->tip->all();
    }

    public function getTraumas()
    {
        return $this->trauma->all();
    }

    public function getMenuLinks()
    {
        return $this->menu->all();
    }

    public function getImages()
    {
        return $this->image->all();
    }

    public function getQuestions()
    {
        return $this->question->all();
    }

    public function storeAnswer($data)
    {
        $answer = new $this->answer;
        $answer->answer_id = $data['answer_id'];
        $answer->user_id = Auth::user()->id;
        $answer->save();

        $score = $this->scale_question_answer->find($data['answer_id']);
        $tips = $this->scale_tips->where('min_value', '<=', $score->score)->where('max_value', '>=', $score->score)->first();

        return [ 'tbl_score' => [ [ 'score' => $score->score ] ], 'details' => [  $tips ] ];
    }

    public function getSubsciptions()
    {
        return $this->subscription->all();
    }

    public function storeMoodMarks($data)
    {
        $mood = $this->mood_mark->create($data);
        $cnt = $this->points->whereDate('created_at', Carbon::now()->format('Y-m-d'))->where('client_id', Auth::user()->id)->where('rankable_type', get_class($mood))->count();

        if ($cnt == 0) {
            $points = new $this->points;
            $points->client_id = Auth::user()->id;
            $points->rankable_type = get_class($mood);
            $points->rankable_id = $mood->id;
            $points->points = 0.25;
            $points->save();
        }

        return true;

    }

    public function getTraumaCopyingCart($request)
    {
        return $this->trauma_copying->where([ 'lflag' => $request->lflag, 'trauma_id' => $request->trauma_id ])->get();
    }

    public function storeSleepTracker($data)
    {

        $start = Carbon::parse($data['from']);
        $end = Carbon::parse($data['to']);
        $sleep = $end->diffInHours($start);

        $age = Carbon::parse(Auth::user()->birth_date)->diff(\Carbon\Carbon::now())->format('%y');
        $depth = 0;
        if ($age >= 14 && $age <= 25) {
            $depth = $sleep - 8;
        }

        if ($age > 25 && $age <= 55) {
            $depth = $sleep - 7;
        }

        if ($age > 55) {
            $depth = $sleep - 6;
        }


        $sleep_tracker = new $this->sleep_tracker;
        $sleep_tracker->client_id = Auth::user()->id;
        $sleep_tracker->date = Carbon::now()->format('Y-m-d');
        $sleep_tracker->from = $data['from'];
        $sleep_tracker->to = $data['to'];
        $sleep_tracker->depth = $depth;
        $sleep_tracker->save();

        $cnt = $this->points->whereDate('created_at', Carbon::now()->format('Y-m-d'))->where('client_id', Auth::user()->id)->where('rankable_type', get_class($sleep_tracker))->count();

        if ($cnt == 0) {
            $points = new $this->points;
            $points->client_id = Auth::user()->id;
            $points->rankable_type = get_class($sleep_tracker);
            $points->rankable_id = $sleep_tracker->id;
            $points->points = 0.25;
            $points->save();
        }

        return true;
    }

    public function storeGratitudeAnswer($data)
    {
        DB::transaction(function () use ($data) {
            $set_no = $this->gratitude_answer->max('set_no');
            if (empty($set_no)) {
                $set_no = 1;
            } else {
                $set_no += 1;
            }
            $score = 0;
            for ($i = 1; $i <= 4; $i++) {
                if (isset($data['answer'.$i]) && !empty($data['answer'.$i])) {
                    $score += 0.25;  
                }
            }
            for ($i = 1; $i <= 4; $i++) {
                $gratitude_answer = new $this->gratitude_answer;
                $gratitude_answer->question = $data['question'.$i];
                $gratitude_answer->answer = (isset($data['answer'.$i]) ? $data['answer'.$i] : '');
                $gratitude_answer->score = $score;
                $gratitude_answer->set_no = $set_no;
                $gratitude_answer->save();
            }

            
            $cnt = $this->points->whereDate('created_at', Carbon::now()->format('Y-m-d'))->where('rankable_type', get_class($gratitude_answer))->where('client_id', Auth::user()->id)->count();
            
            if ($cnt == 0) {
                $points = new $this->points;
                $points->client_id = Auth::user()->id;
                $points->rankable_type = get_class($gratitude_answer);
                $points->rankable_id = $gratitude_answer->id;
                $points->points = 0.25;
                $points->save();
            }

        });

        return true;
    }

    public function getInstitueList()
    {
        $month = Carbon::now()->month;

        $clients = $this->client->select('id')->where('user_id', Auth::user()->user_id)->get();
        $points = DB::table('client_points')
            ->select(DB::raw('SUM(client_points.points) as points, clients.name'))
            ->join('clients', 'clients.id', 'client_points.client_id')
            ->whereIn('client_points.client_id', $clients->pluck('id')->toArray())
            ->whereMonth('client_points.created_at', $month)->groupBy('client_id')->get()->take(10);

        // $points = $this->points->selectRaw('SUM(points) as points')->addSelect('clients.name')->whereIn('client_id', $clients)->whereMonth('created_at', $month)->groupBy('client_id')->get()->take(10);
        return $points->map(function($key, $value) {
            $key->rank = $value + 1;
            return $key;
        });
    }

    public function storeExerciseTracker($data)
    {
        $exercise = new $this->exercise;
        $exercise->client_id = Auth::user()->id;
        $exercise->start_time = $data['start_time'];
        $exercise->end_time = $data['end_time'];
        $exercise->exercise_type = $data['exercise_type'];
        $exercise->date = $data['date'];
        $exercise->score = 0;
        $exercise->save();

        $total_physical = 0;
        $total_technical = 0;
        $today_excericses = $this->exercise->whereDate('date', $data['date'])->get();
        foreach ($today_excericses as $exc) {
            $startTime = Carbon::parse($data['date'].$exc->start_time);
            $finishTime = Carbon::parse($data['date'].$exc->end_time);
            if ($exc->exercise_type == 'physical') {
                $total_physical += $finishTime->diffInMinutes($startTime);
            } else {
                $total_technical += $finishTime->diffInMinutes($startTime);
            }
        }

        $total_points = 0;
        if ($total_physical >= 20) {
            $total_points += 0.5;
        } else if ($total_technical >= 20) {
            $total_points += 0.5;
        }

        if ($total_points > 0) {
            $exercise_point = $this->exercise_point->where([ 'date' => $data['date'], 'client_id' => Auth::user()->id ])->first();
            if (empty($exercise_point)) {
                $exercise_point = new $this->exercise_point;
                $exercise_point->date = $data['date'];
                $exercise_point->client_id = Auth::user()->id;
            }

            $exercise_point->points = $total_points;
            $exercise_point->save();
        }
        
        $cnt = $this->points->whereDate('created_at', Carbon::now()->format('Y-m-d'))->where('client_id', Auth::user()->id)->where('rankable_type', get_class($exercise))->count();
            
        if ($cnt == 0) {
            $points = new $this->points;
            $points->client_id = Auth::user()->id;
            $points->rankable_type = get_class($exercise);
            $points->rankable_id = $exercise->id;
            $points->points = 0.25;
            $points->exercise_type = $data['exercise_type'];
            $points->save();
        }

        return true;
    }
}