<?php

namespace App\Http\Controllers;

use App\Http\Resources\SmsResource;
use App\Models\Sms;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, User $user)
    {
        $query = Sms::query()->where('sender_id',$user->id);
        $date = $request->date_filter;

        switch ($date){
            case 'today':
                $query->whereDate('send_time', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('send_time',Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('send_time',[Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                 $query->whereBetween('send_time',[Carbon::now()->subWeek(),Carbon::now()]);
                 break;
            case 'this_month':
                $query->whereMonth('send_time',Carbon::now()->month);
                break;
            case 'last_month':
                $query->whereMonth('send_time',Carbon::now()->subMonth()->month);
                break;
            case 'this_year':
                $query->whereYear('send_time',Carbon::now()->year);
                break;
            case 'last_year':
                $query->whereYear('send_time',Carbon::now()->subYear()->year);
                break;
        }

        if(count($query->get()) == 0){
            return response()->json([
                'data' => null,
                'status' => 'success',
                'code' => '200',
                'message' => 'Sms which belongs to relevant date cannot found.'
            ]);
        }

        return SmsResource::collection($query->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $user, Request $request)
    {
        $validatedData = $request->validate([
            'number' => 'required',
            'message' => 'required',
        ]);

        $sms = Sms::create([
            'sender_id' => $user->id,
            'number' => $validatedData['number'],
            'message' => $validatedData['message'],
            'send_time' => Carbon::now(),
        ]);

        return new SmsResource($sms);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user,string $id)
    {
        return new SmsResource(Sms::where('id', $id)->first());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
