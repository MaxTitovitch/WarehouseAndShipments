<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\UserRequest;
use App\BalanceHistory;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function show($id)
    {
        return response()->json(User::with('balanceHistories')->find($id), 200);
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        $this->createBalanceHistory($user, $request->balance);
        $user->role = $request->role;
        $user->balance = $request->balance;
        $user->save();
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        return response()->json(User::destroy($id), 200);
    }

    private function createBalanceHistory(User $user, $newBalance) {
        if($user->balance != $newBalance) {
            $balanceHistory = new BalanceHistory();
            $balanceHistory->user_id = $user->id;
            $balanceHistory->current_balance = $newBalance;
            $balanceHistory->transaction_cost = abs($user->balance - $newBalance);
            $balanceHistory->type = $user->balance - $newBalance > 0 ? 'Debit' : 'Credit';
            $balanceHistory->save();
        }
    }
}
