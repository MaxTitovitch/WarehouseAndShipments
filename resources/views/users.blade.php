@extends('layouts.app')

@section('title')
    Пользователи
@endsection

@section('content')

    <div class="main-container">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <div class="table-container">
        <table class="table table-bordered table-striped table-hover" id="dtEntityTable">
            <thead class="thead-dark">
            <tr>
                <th scope="col" class="th-sm">ID</th>
                <th scope="col" class="th-sm">Уникальный номер</th>
                <th scope="col" class="th-sm">ФИО</th>
                <th scope="col" class="th-sm">Email</th>
                <th scope="col" class="th-sm">Роль</th>
                <th scope="col" class="th-sm">Баланс Получателей</th>
                <th scope="col" class="th-sm">Ставка коммисии</th>
                <th scope="col" class="th-sm">Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->suite }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->balance}}</td>
                    <td>{{ $user->fee}}</td>
                    <td>
                        <a href="#" class="show-product text-dark font-weight-bold show-entity-button" data-value-id="{{ $user->id }}">Показать</a>
                        <a href="#" class="edit-product text-dark font-weight-bold edit-entity-button" data-value-id="{{ $user->id }}">Редактировать</a>
                        <a href="#" class="show-product text-dark font-weight-bold delete-entity-button" data-value-id="{{ $user->id }}">Удалить</a>
                        <a href="#" class="show-product text-dark font-weight-bold balance-entity-button" data-value-id="{{ $user->id }}"
                           data-value-name="{{ $user->name }}">Баланс Получателей</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    </div>
    <div class="container-fluid mt-5"">
        <div class="row">
            <div class="col-12 text-center-mobile">
                <a href="{{ route('inbound-shipments') }}" class="badge badge-dark text-full-size"><i class="fa fa-list-alt" aria-hidden="true"></i> Исходящие посылки</a>
                <a href="{{ route('products') }}" class="badge badge-dark text-full-size"><i class="fa fa-cube" aria-hidden="true"></i> Перечень Вещей</a>
                <a href="{{ route('orders') }}" class="badge badge-dark text-full-size"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Входящие посылки</a>
                <a href="{{ route('balance') }}" class="badge badge-dark text-full-size"><i class="fa fa-dollar" aria-hidden="true"></i> Баланс Получателей</a>
                @if(\Illuminate\Support\Facades\Auth::user())
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                        <a href="{{ route('users') }}" class="badge badge-dark text-full-size"><i class="fa fa-users" aria-hidden="true"></i> Пользователи</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" data-backdrop="static" id="modalAdd" tabindex="-1" role="dialog"
         aria-labelledby="modalAddLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabelProduct">Изменение Личной Информации</h5>
                    <button type="button" class="close close-modal-button" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-submit">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold" for="userName">ФИО</label>
                            <span class="form-control text-secondary form-control-height" id="userName"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="userEmail">Email</label>
                            <span class="form-control text-secondary form-control-height" id="userEmail"></span>
                        </div>
                        <div class="form-group">
                            <label for="role" class="font-weight-bold">Роль</label>
                            <select class="form-control" id="role">
                                <option>Пользователь</option>
                                <option>Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fee" class="font-weight-bold">Ставка коммисии</label>
                            <input type="number" class="form-control" required maxlength="255" min="0" max="10000" id="fee"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Ставка коммисии">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary close-modal-button" data-dismiss="modal">
                            Закрыть
                        </button>
                        <button type="submit" class="btn btn-dark save-changes">Изменить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="showModal" tabindex="-1" role="dialog"
         aria-labelledby="showModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel">Входящие посылки</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserID">ID</label>
                            <span class="form-control form-control-height " id="showUserID"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserSuite">Уникальный номер</label>
                            <span class="form-control form-control-height " id="showUserSuite"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserName">ФИО</label>
                            <span class="form-control form-control-height " id="showUserName"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserEmail">Email</label>
                            <span class="form-control form-control-height " id="showUserEmail"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserRole">Роль</label>
                            <span class="form-control form-control-height " id="showUserRole"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserBalance">Баланс Получателей</label>
                            <span class="form-control form-control-height " id="showUserBalance"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserFee">Ставка коммисии</label>
                            <span class="form-control form-control-height " id="showUserFee"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserCreated">Создано</label>
                            <span class="form-control form-control-height " id="showUserCreated"></span>
                        </div>
                        <table class="table table-bordered table-striped table-hover" id="dtEntityTableShow">
                            <thead class="thead-dark">
                            <tr>
                                <th>Баланс Получателей</th>
                                <th>Transaction cost</th>
                                <th>Type</th>
                                <th>Коммент</th>
                                <th>Создано</th>
                            </tr>
                            </thead>
                            <tbody id="balanceHistoryArea">
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBalance" tabindex="-1" role="dialog"
         aria-labelledby="modalBalanceLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBalanceLabel">Adding a Credit to a user</h5>
                    <button type="button" class="close close-modal-button" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-submit" id="formBalance">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold" for="userNameBalance">ФИО</label>
                            <span class="form-control text-secondary form-control-height" id="userNameBalance"></span>
                        </div>
                        <div class="form-group">
                            <label for="creditAmount" class="font-weight-bold">Сумма</label>
                            <input type="number" class="form-control" required maxlength="255" min="-100000" max="100000" id="creditAmount"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Credit amount">
                            <small id="creditAmountSmall" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="creditComment" class="font-weight-bold">Коммент</label>
                            <textarea class="form-control" maxlength="255" id="creditComment"
                                      aria-describedby="ariaDescribedbyHelp" placeholder="Коммент"></textarea>
                            <small id="creditCommentSmall" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary close-modal-button" data-dismiss="modal">
                            Закрыть
                        </button>
                        <button type="submit" class="btn btn-dark save-changes">Обновить Баланс Получателей</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/user.js') }}" defer></script>
@endsection
