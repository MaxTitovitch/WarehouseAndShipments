@extends('layouts.app')

@section('title')
    Перечень Вещей
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
{{--        @if(Auth::user()->role != 'Admin')--}}
            <button type="button" class="btn btn-dark btn-lg float-right my-3 mr-3 create-product" data-toggle="modal"
                    data-target="#modalAdd">Добавить
            </button>
{{--        @endif--}}

        <form action="{{ route('parse') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="products" class="display-none">
            <input id="import-input" type="file" name="file" class="display-none" accept=".csv, .xlsx, .xls">
            <input id="import-submit" type="submit" value="Submit" class="display-none">
    {{--        <button id="import-open" type="button" class="btn btn-dark btn-lg float-right my-3 mr-3">Import</button>--}}
        </form>
        <a href="{{route('exportProducts')}}" class="btn btn-dark btn-lg float-right my-3 mr-3">Экспорт</a>

        <div class="table-container">
        <table class="table table-bordered table-striped table-hover" id="dtEntityTable">
            <thead class="thead-dark">
            <tr>
                <th scope="col" class="th-sm">ID</th>
                @if (Auth::user()->role == 'Admin')
                    <th scope="col" class="th-sm">Пользователь</th>
                @endif
                <th scope="col" class="th-sm">Создано</th>
                <th scope="col" class="th-sm">Зарезервировано</th>
                <th scope="col" class="th-sm">Доступно</th>
                <th scope="col" class="th-sm">В пути</th>
                <th scope="col" class="th-sm">Название</th>
                <th scope="col" class="th-sm">Бренд</th>
                <th scope="col" class="th-sm">UPC</th>
                <th scope="col" class="th-sm">SKU</th>
                <th scope="col" class="th-sm">Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <th scope="row">{{ $product->id }}</th>
                    @if (Auth::user()->role == 'Admin')
                        <td>{{ $product->user->name }} ({{ $product->user->suite }})</td>
                    @endif
                    <td>{{ $product->created_at->format('Y-m-d') }}</td>
                    <td>{{ $product->received }}</td>
                    <td>{{ $product->available }}</td>
                    <td>{{ $product->in_transit}}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->brand }}</td>
                    <td>{{ $product->upc }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>
                        <a href="#" class="show-product text-dark font-weight-bold show-entity-button"
                           data-value-id="{{ $product->id }}">Показать</a>
                            <a href="#" class="edit-product text-dark font-weight-bold edit-entity-button"
                               data-value-id="{{ $product->id }}">Редактировать</a>

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
                    <h5 class="modal-title" id="modalAddLabelProduct">Добавить вещь</h5>
                    <button type="button" class="close close-modal-button" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-submit">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name">Название</label>
                            <input type="text" class="form-control" required maxlength="255" id="name"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Название">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="brand">Бренд</label>
                            <input type="text" class="form-control" required maxlength="255" id="brand"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Бренд">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="upc">UPC</label>
                            <input type="text" class="form-control" required maxlength="50" id="upc"
                                   aria-describedby="commentHelp" placeholder="UPC">
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" class="form-control" required maxlength="50" id="sku"
                                   aria-describedby="commentHelp" placeholder="SKU">
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label for="reserved">Date of reserving</label>--}}
{{--                            <input type="date" class="form-control" id="reserved" name="reserved" placeholder="date"--}}
{{--                                   aria-describedby="dateHelp">--}}
{{--                            <small id="dateHelp" class="form-text text-danger"></small>--}}
{{--                        </div>--}}
{{--                        <div class="form-group custom-control custom-switch">--}}
{{--                            <input type="checkbox" class="custom-control-input" id="available">--}}
{{--                            <label for="available" class="custom-control-label">Доступно</label>--}}
{{--                            <small id="availabletHelp" class="form-text text-danger"></small>--}}
{{--                        </div>--}}
{{--                        <div class="form-group custom-control custom-switch">--}}
{{--                            <input type="checkbox" class="custom-control-input" id="in_transit">--}}
{{--                            <label for="in_transit" class="custom-control-label">В пути</label>--}}
{{--                            <small id="inTransitHelp" class="form-text text-danger"></small>--}}
{{--                        </div>--}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary close-modal-button" data-dismiss="modal">
                            Закрыть
                        </button>
                        <button type="submit" class="btn btn-dark save-changes">Создать</button>
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
                    <h5 class="modal-title" id="showModalLabel">Перечень вещей</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductId">ID</label>
                            <span class="form-control form-control-height " id="showProductId"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductCreated">Создано</label>
                            <span class="form-control form-control-height " id="showProductCreated"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductUPC">UPC</label>
                            <span class="form-control form-control-height " id="showProductUPC"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductSKU">SKU</label>
                            <span class="form-control form-control-height " id="showProductSKU"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductBrand">Бренд</label>
                            <span class="form-control form-control-height " id="showProductBrand"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductName">Название</label>
                            <span class="form-control form-control-height " id="showProductName"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductTransit">В пути</label>
                            <span class="form-control form-control-height " id="showProductTransit"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductReserved">Зарезервировано</label>
                            <span class="form-control form-control-height " id="showProductReserved"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductAvailable">Доступно</label>
                            <span class="form-control form-control-height " id="showProductAvailable"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductUser">Пользователь</label>
                            <span class="form-control form-control-height " id="showProductUser"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/product.js') }}" defer></script>
@endsection
