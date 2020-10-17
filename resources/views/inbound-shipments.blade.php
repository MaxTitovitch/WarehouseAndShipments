@extends('layouts.app')

@section('title')
    Inbound shipment
@endsection

@section('content')
    <div class="main-container">
        @if(Auth::user()->role != 'Admin')
            <button type="button" class="btn btn-dark btn-lg float-right my-3 mr-3 create-shipment" data-toggle="modal"
                    data-target="#modalAdd">Add New
            </button>
        @endif

        <form action="{{ route('parse') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="shipments" class="display-none">
            <input id="import-input" type="file" name="file" class="display-none" accept=".csv, .xlsx, .xls">
            <input id="import-submit" type="submit" value="Submit" class="display-none">
    {{--        <button id="import-open" type="button" class="btn btn-dark btn-lg float-right my-3 mr-3">Import</button>--}}
        </form>

        <a href="{{route('exportShipments')}}" class="btn btn-dark btn-lg float-right my-3 mr-3">Export</a>

        <div class="table-container">
            <table class="table table-bordered table-striped table-hover" id="dtEntityTable">
                <thead class="thead-dark">
                <tr>
                    <th scope="col" class="th-sm">ID</th>
                    @if (Auth::user()->role == 'Admin')
                        <th scope="col" class="th-sm">User</th>
                    @endif
                    <th scope="col" class="th-sm">Created</th>
                    <th scope="col" class="th-sm">Shipped</th>
                    <th scope="col" class="th-sm">Received</th>
                    <th scope="col" class="th-sm">Tracking number</th>
                    <th scope="col" class="th-sm">Shipping company</th>
                    <th scope="col" class="th-sm">Comment</th>
                    <th scope="col" class="th-sm">Quantity</th>
                    <th scope="col" class="th-sm">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($shipments as $shipment)
                    <tr>
                        <th scope="row">{{ $shipment->id }}</th>
                        @if (Auth::user()->role == 'Admin')
                            <td>{{ $shipment->user->name }} ({{ $shipment->user->suite }})</td>
                        @endif
                        <td>{{ $shipment->created_at->format('Y-m-d') }}</td>
                        <td>{{ $shipment->shipped }}</td>
                        <td>{{ $shipment->received }}</td>
                        <td>{{ $shipment->tracking_number }}</td>
                        <td>{{ $shipment->shipping_company }}</td>
                        <td>{{ $shipment->comment }}</td>
                        <td>{{ $shipment->quantity }}</td>
                        <td>
                            <a href="#" class="show-shipment text-dark font-weight-bold show-entity-button" data-value-id="{{ $shipment->id }}">Show</a>
                            @if(Auth::user()->role == 'Admin' || $shipment->received == null)
                                <a href="#" class="edit-shipment text-dark font-weight-bold edit-entity-button" data-value-id="{{ $shipment->id }}">Edit</a>
                            @endif
                            @if($shipment->received == null && Auth::user()->role != 'Admin')
                                <a href="#" class="show-shipment text-dark font-weight-bold delete-entity-button"
                                   data-value-id="{{ $shipment->id }}">Delete</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('inbound-shipments') }}" class="badge badge-dark text-full-size"><i class="fa fa-list-alt" aria-hidden="true"></i> Inbound shipments</a>
                <a href="{{ route('products') }}" class="badge badge-dark text-full-size"><i class="fa fa-cube" aria-hidden="true"></i> Products</a>
                <a href="{{ route('orders') }}" class="badge badge-dark text-full-size"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Orders</a>
                @if(\Illuminate\Support\Facades\Auth::user())
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                        <a href="{{ route('users') }}" class="badge badge-dark text-full-size"><i class="fa fa-users" aria-hidden="true"></i> Users</a>
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
                                <option>APC</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea rows="5" class="form-control" maxlength="255" id="comment"
                                      aria-describedby="commentHelp" placeholder="comment"></textarea>
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="shipped">Date of shipping</label>
                            <input type="date" class="form-control" id="shipped" name="date" placeholder="date"
                                   aria-describedby="dateHelp" required>
                            <small id="dateHelp" class="form-text text-danger"></small>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                            <div class="form-group">
                                <label for="received">Date of received</label>
                                <input type="date" class="form-control" id="received" name="date" placeholder="date"
                                       aria-describedby="dateHelp">
                                <small id="dateHelp" class="form-text text-danger"></small>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="productFormControlSelect1">Select product</label>
                            <div class="products-container">
{{--                                <div class="product-container">--}}
{{--                                    <select class="form-control product-select product-shipment-select"></select>--}}
{{--                                    <input type="number" class="form-control quantity" placeholder="quantity"--}}
{{--                                           required min="1" max="10000">--}}
{{--                                    <a href="#" class="remove-product-select">--}}
{{--                                        <i class="fa fa-times fa-2x text-dark" aria-hidden="true"></i>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
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


    <div class="modal fade" id="showModal" tabindex="-1" role="dialog"
         aria-labelledby="showModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel">Inbound shipment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label class="font-weight-bold"  for="showId">ID</label>
                            <span class="form-control form-control-height " id="showId"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUser">User</label>
                            <span class="form-control form-control-height " id="showUser"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showShipped">Shipped</label>
                            <span class="form-control form-control-height " id="showShipped"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showReceived">Received</label>
                            <span class="form-control form-control-height " id="showReceived"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showCompany">Shipping company</label>
                            <span class="form-control form-control-height " id="showCompany"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showTrackingNumber">Tracking number</label>
                            <span class="form-control form-control-height " id="showTrackingNumber"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showComment">Comment</label>
                            <span class="form-control form-control-height " id="showComment"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showQuantity">Quantity</label>
                            <span class="form-control form-control-height " id="showQuantity"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showCreated">Created</label>
                            <span class="form-control form-control-height " id="showCreated"></span>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Products</label>
                            <div class="show-products-container">
                                <div class="show-product-container">
                                    <span class="form-control form-control-height show-product">Chear</span>
                                    <span class="form-control form-control-height show-quantity quantity-width"><span class="show-quantity"></span><span class="font-weight-bold"> pcs</span></span>
                                </div>
                            </div>
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
    <script src="{{ asset('js/shipment.js') }}" defer></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
@endsection
