@extends('layouts.app')

@section('title')
    Balance
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
                @if (Auth::user()->role == 'Admin')
                    <th scope="col" class="th-sm">User</th>
                @endif
                <th>Created</th>
                <th>Current Balance</th>
                <th>Transaction cost</th>
                <th>Type</th>
                <th>Comment</th>
                <th scope="col" class="th-sm">Related</th>
            </tr>
            </thead>
            <tbody>
            @foreach($balances as $balance)
                <tr>
                    <th scope="row">{{ $balance->id }}</th>
                    @if (Auth::user()->role == 'Admin')
                        <td>{{ $balance->user->name }} ({{ $balance->user->suite }})</td>
                    @endif
                    <td>{{ $balance->created_at->format('Y-m-d') }}</td>
                    <td>{{ $balance->current_balance }}</td>
                    <td>{{ $balance->transaction_cost }}</td>
                    <td>{{ $balance->type }}</td>
                    <td>{{ $balance->comment}}</td>

                    <td>
                        @if (explode(":", $balance->comment)[0] == 'Order ID')

                            <a href="#" class="show-product text-dark font-weight-bold show-entity-button"
                               data-value-id="{{ explode(":", $balance->comment)[1] }}">Order Show</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    </div>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-12 text-center-mobile">
                <a href="{{ route('inbound-shipments') }}" class="badge badge-dark text-full-size"><i class="fa fa-list-alt" aria-hidden="true"></i> Inbound shipments</a>
                <a href="{{ route('products') }}" class="badge badge-dark text-full-size"><i class="fa fa-cube" aria-hidden="true"></i> Products</a>
                <a href="{{ route('orders') }}" class="badge badge-dark text-full-size"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Orders</a>
                <a href="{{ route('balance') }}" class="badge badge-dark text-full-size"><i class="fa fa-dollar" aria-hidden="true"></i> Balance</a>
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
    <div class="modal fade" id="showModal" tabindex="-1" role="dialog"
         aria-labelledby="showModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel">Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <p class="col-12">
                            <strong>Order ID: </strong><span id="showOrderId"></span>,
                            <strong>Status: </strong><span id="showOrderStatus"></span>
                        </p>
                        <p class="col-12">
                            <strong>User: </strong> <br>
                            <span id="showOrderUser"></span>
                        </p>
                        <p class="col-6">
                            <strong>Shipped to: </strong><br>
                            <strong id="showOrderCustomer"></strong>.
                            <strong id="showOrderCompanyName"></strong><br>
                            <span id="showOrderZipCode"></span><br>
                            <span id="showOrderCity"></span>,
                            <span id="showOrderState"></span>,
                            <span id="showOrderAddress"></span><br>
                            <span id="showOrderCountry"></span><br>
                            tel. <span id="showOrderPhone"></span>
                        </p>
                        <p class="col-6">
                            <strong>Carrier: </strong><br>
                            <span id="showOrderShippingCompany"></span><br>
                            <strong>Packing Selection: </strong><br>
                            <span id="showOrderPackingSelection"></span><br>
                            <strong>Comment: </strong><br>
                            <span id="showOrderComment"></span><br>
                        </p>
                        <p class="col-6">
                            <strong>Tracking Number: </strong><br>
                            <span id="showOrderTrackingNumber"></span><br>
                            <strong>Shipping Cost: </strong><br>
                            <span id="showOrderShippingCost"></span><br>
                            <strong>Fee Cost: </strong><br>
                            <span id="showOrderFeeCost"></span><br>
                        </p>
                        <p class="col-6">
                            <strong>Created: </strong><br>
                            <span id="showOrderCreated"></span><br>
                            <strong>Shipped: </strong><br>
                            <span id="showOrderShipped"></span><br>
                        </p>
                        <div class="col-12">
                            <label class="font-weight-bold">Items:</label>
                            <table class="show-products-container w-100" cellspacing="0" border="1" >
                                <thead>
                                <tr>
                                    <td><strong>UPC</strong></td>
                                    <td><strong>SKU</strong></td>
                                    <td><strong>Brand</strong></td>
                                    <td><strong>Name</strong></td>
                                    <td><strong>Quantity</strong></td>
                                    <td><strong>Price</strong></td>
                                    <td><strong>Description</strong></td>
                                </tr>
                                </thead>
                                <tr class="show-product-container product-order-area">
                                    <td class="show-upc"></td>
                                    <td class="show-sku"></td>
                                    <td class="show-brand"></td>
                                    <td class="show-product"></td>
                                    <td>
                                        <span class="show-quantity"></span><span class="font-weight-bold"> pcs</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">$</span><span class="show-price"></span>
                                    </td>
                                    <td class="show-description"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/balance.js') }}" defer></script>
@endsection
