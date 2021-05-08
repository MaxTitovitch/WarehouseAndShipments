<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\UserRequest;
use App\BalanceHistory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserSelfUpdateRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Requests\AddBalanceRequest;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function show($id)
    {
        return response()->json(User::with(['balanceHistories' => function($query) {
            $query->orderBy('created_at', 'DESC');
        }])->find($id), 200);
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
//        $this->createBalanceHistory($user, $request->balance);
        $user->role = $request->role;
        $user->fee = $request->fee;

        $user->save();
        Session::flash('success', 'Пользователь обновлен!');
        return response()->json($user, 200);
    }

    public function addBalance(AddBalanceRequest $request, $id)
    {
        $user = User::find($id);
        $this->createBalanceHistory($user, $request->balance + $user->balance, $request->comment);
        $user->balance += $request->balance;
        $user->save();
        Session::flash('success', 'Пользователь обновлен!');
        return response()->json($user, 200);
    }

    public function updateSelf(UserSelfUpdateRequest $request, $id)
    {
        $user = User::find($id);
        if(Auth::user()->id = $user->id) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            Auth::attempt(['email' => $user->email, 'password' => $user->password]);
            Session::flash('success', 'Личная информация обновлена!');
            return response()->json($user, 200);
        } else {
            Session::flash('error', 'Это не ваш аккаунт!');
            return response()->json(null, 200);
        }
    }

    public function changePassword(ChangePasswordRequest $request, $id)
    {
        $user = User::find($id);
        if(Auth::user()->id = $user->id) {
            if(Hash::check($request->last_password, $user->password)){
                $user->password = Hash::make($request->password);
                $user->save();
                Auth::attempt(['email' => $user->email, 'password' => $user->password]);
                Session::flash('success', 'Пароль Изменён!');
                return response()->json($user, 200);
            } else {
                throw new HttpResponseException(response()->json(['last_password' => ['It isn\'t your last password']], 403));
            }
        } else {
            Session::flash('error', 'Это не ваш аккаунт!');
            return response()->json(null, 200);
        }
    }

    public function destroy($id)
    {
        Session::flash('success', 'Пользователь удален!');
        return response()->json(User::destroy($id), 200);
    }

    private function createBalanceHistory(User $user, $newBalance, $comment = '') {
        if($user->balance != $newBalance) {
            $balanceHistory = new BalanceHistory();
            $balanceHistory->user_id = $user->id;
            $balanceHistory->current_balance = $newBalance;
            $balanceHistory->transaction_cost = abs($user->balance - $newBalance);
            $balanceHistory->type = $user->balance - $newBalance > 0 ? 'Debit' : 'Credit';
            $balanceHistory->comment = $comment;
            $balanceHistory->save();
        }
    }
}
