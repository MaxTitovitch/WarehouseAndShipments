@extends('layouts.app')

@section('title')
    Inbound shipment
@endsection

@section('content')

    <button type="button" class="btn btn-dark btn-lg float-right my-3 mr-3 create-shipment" data-toggle="modal"
            data-target="#exampleModal">Add New
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
                <td>{{ $shipment->user->id }}</td>
                <td>{{ $shipment->shipped }}</td>
                <td>{{ $shipment->received }}</td>
                <td>{{ $shipment->shipping_company }}</td>
                <td>{{ $shipment->tracking_number }}</td>
                <td>{{ $shipment->comment }}</td>
                <td>{{ $shipment->quantity }}</td>
                <td>{{ $shipment->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="#" class="show-shipment">Show</a>
                    <a href="#" class="edit-shipment">Edit</a>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="tracking-number">Tracking number</label>
                            <input type="text" class="form-control" required maxlength="255" id="tracking-number"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Tracking number">
                            <small id="ariaDescribedbyHelp" class="form-text text-muted tracking-number-error"></small>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Shipping company</label>
                            <select class="form-control" id="exampleFormControlSelect1">
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
                            <small id="commentHelp" class="form-text text-muted comment-error"></small>
                        </div>
                        <div class="form-group">
                            <label for="date">Date of receiving</label>
                            <input type="date" class="form-control" id="date" name="date" placeholder="date" required
                                   aria-describedby="dateHelp">
                            <small id="dateHelp" class="form-text text-muted date-error"></small>
                        </div>
                        <div class="form-group">
                            <label for="date">Date of shipping</label>
                            <input type="date" class="form-control" id="date" name="date" placeholder="date" required
                                   aria-describedby="dateHelp">
                            <small id="dateHelp" class="form-text text-muted date-error"></small>
                        </div>
                        <div class="form-group">
                            <label for="productFormControlSelect1">Select product</label>
                            <div class="products-container">
                                <div class="product-container">
                                    <select class="form-control product-select"></select>
                                    <input type="text" class="form-control" id="quantity" placeholder="quantity"
                                           required>
                                    <a href="#">
                                        <i class="fa fa-times fa-2x text-dark" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="button-plus mt-2 mr-2">
                                <a href="#">
                                    <i class="fa fa-2x fa-plus text-dark" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
