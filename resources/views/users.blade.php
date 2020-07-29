@extends('layouts.app')

@section('title')
    Products
@endsection

@section('content')
    <form action="{{ route('parse') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="products" class="display-none">
        <input id="import-input" type="file" name="file" class="display-none" accept=".csv, .xlsx, .xls">
        <input id="import-submit" type="submit" value="Submit" class="display-none">
        <button id="import-open" type="button" class="btn btn-dark btn-lg float-right my-3 mr-3">Import</button>
    </form>

    <div class="table-container">
        <table class="table table-bordered table-striped table-hover" id="dtEntityTable">
            <thead class="thead-dark">
            <tr>
                <th scope="col" class="th-sm">ID</th>
                <th scope="col" class="th-sm">Full Name</th>
                <th scope="col" class="th-sm">Email</th>
                <th scope="col" class="th-sm">Role</th>
                <th scope="col" class="th-sm">Balance</th>
                <th scope="col" class="th-sm">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->balance}}</td>
                    <td>
                        <a href="#" class="show-product text-dark font-weight-bold show-entity-button" data-value-id="{{ $user->id }}">Show</a>
                        <a href="#" class="edit-product text-dark font-weight-bold edit-entity-button" data-value-id="{{ $user->id }}">Edit</a>
                        <a href="#" class="show-product text-dark font-weight-bold delete-entity-button" data-value-id="{{ $user->id }}">Delete</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('modal')
    <div class="modal fade" data-backdrop="static" id="modalAdd" tabindex="-1" role="dialog"
         aria-labelledby="modalAddLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabelProduct">Change user data</h5>
                    <button type="button" class="close close-modal-button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-submit">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold" for="userName">Full Name</label>
                            <span class="form-control text-secondary form-control-height" id="userName"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="userEmail">Email</label>
                            <span class="form-control text-secondary form-control-height" id="userEmail"></span>
                        </div>
                        <div class="form-group">
                            <label for="role" class="font-weight-bold">Role</label>
                            <select class="form-control" id="role">
                                <option>User</option>
                                <option>Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="balance" class="font-weight-bold">Balance</label>
                            <input type="number" class="form-control" required maxlength="255" min="1" max="10000" id="balance"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Balance">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary close-modal-button" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-dark save-changes">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="showModal" tabindex="-1" role="dialog"
         aria-labelledby="showModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel">Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                            <label class="font-weight-bold" for="showUserName">Full Name</label>
                            <span class="form-control form-control-height " id="showUserName"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserEmail">Email</label>
                            <span class="form-control form-control-height " id="showUserEmail"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserRole">Role</label>
                            <span class="form-control form-control-height " id="showUserRole"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserBalance">Balance</label>
                            <span class="form-control form-control-height " id="showUserBalance"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUserCreated">Created</label>
                            <span class="form-control form-control-height " id="showUserCreated"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/user.js') }}" defer></script>
@endsection
