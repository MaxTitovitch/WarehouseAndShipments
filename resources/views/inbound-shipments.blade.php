@extends('layouts.app')

@section('title')
    Inbound shipment
@endsection

@section('content')

    <button type="button" class="btn btn-dark btn-lg float-right my-3 mr-3 create-shipment" data-toggle="modal"
            data-target="#modalAdd">Add New
    </button>
    <form action="{{ route('parse') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="shipments" class="display-none">


        <input id="import-input" type="file" name="file" class="display-none" accept=".csv, .xlsx, .xls">
        <input id="import-submit" type="submit" value="Submit" class="display-none">
        <button id="import-open" type="button" class="btn btn-dark btn-lg float-right my-3 mr-3">Import</button>
    </form>

    <table class="table mt-5 dashboard-table-div">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">User</th>
            <th scope="col">Shipped</th>
            <th scope="col">Received</th>
            <th scope="col">Shipping company</th>
            <th scope="col">Tracking number</th>
            <th scope="col">Comment</th>
            <th scope="col">Quantity</th>
            <th scope="col">Created</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($shipments as $shipment)
            <tr>
                <th scope="row">{{ $shipment->id }}</th>
                <td>{{ $shipment->user->name }}</td>
                <td>{{ $shipment->shipped }}</td>
                <td>{{ $shipment->received }}</td>
                <td>{{ $shipment->shipping_company }}</td>
                <td>{{ $shipment->tracking_number }}</td>
                <td>{{ $shipment->comment }}</td>
                <td>{{ $shipment->quantity }}</td>
                <td>{{ $shipment->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="#" class="show-shipment text-dark font-weight-bold">Show</a>
                    <a href="#" class="edit-shipment text-dark font-weight-bold edit-entity-button" data-value-id="{{ $shipment->id }}">Edit</a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    {{--    @section('content')--}}
    {{--        @dump($shipments)--}}
    {{--    @endsection--}}

@endsection

@section('modal')
    <div class="modal fade" data-backdrop="static" id="modalAdd" tabindex="-1" role="dialog"
         aria-labelledby="modalAddLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabel">Add new Inbound Shipment</h5>
                    <button type="button" class="close close-modal-button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-submit">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="tracking_number-number">Tracking number</label>
                            <input type="text" class="form-control" required maxlength="255" id="tracking_number"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Tracking number">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="shipping_company">Shipping company</label>
                            <select class="form-control" id="shipping_company">
                                <option>USPS</option>
                                <option>FedEx</option>
                                <option>DHL</option>
                                <option>UPS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <input type="text" class="form-control" required maxlength="255" id="comment"
                                   aria-describedby="commentHelp" placeholder="comment">
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 'admin')
                            <div class="form-group">
                                <label for="received">Date of receiving</label>
                                <input type="date" class="form-control" id="received" name="date" placeholder="date"
                                       aria-describedby="dateHelp">
                                <small id="dateHelp" class="form-text text-danger"></small>
                            </div>
                            <div class="form-group">
                                <label for="shipped">Date of shipping</label>
                                <input type="date" class="form-control" id="shipped" name="date" placeholder="date"
                                       aria-describedby="dateHelp">
                                <small id="dateHelp" class="form-text text-danger"></small>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="productFormControlSelect1">Select product</label>
                            <div class="products-container">
                                <div class="product-container">
                                    <select class="form-control product-select"></select>
                                    <input type="number" class="form-control quantity" placeholder="quantity"
                                           required min="1" max="10000">
                                    <a href="#" class="remove-product-select">
                                        <i class="fa fa-times fa-2x text-dark" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="button-plus mt-2 mr-2">
                                <a href="#" class="add-product-select">
                                    <i class="fa fa-2x fa-plus text-dark" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary close-modal-button" data-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-dark save-changes">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/shipment.js') }}" defer></script>
@endsection
