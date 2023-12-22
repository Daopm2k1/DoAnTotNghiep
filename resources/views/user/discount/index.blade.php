@extends('layouts.user')
@section('head')
    <style>
        .t-center
        {
            text-align: center;
        }
        .t-right
        {
            text-align: right;
        }
        #customers {
            /* font-family: Arial, Helvetica, sans-serif; */
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: center;
            background-color: #FE980F ;
            color: white;
        }
        .flex-btn
        {
            display: inline-flex;
            width: 100%;
            justify-content: flex-end;
        }
        .width-262px
        {
            width: 262px;
        }
    </style>
@endsection
@section('content')
<section id="cart_items">
    <div class="container">
        @include('layouts.user.breadcrumb')
        <h1>Giảm giá</h1>
        <div id="discount-box" class="content-order spacing-top-bottom-30px">
            <div class="container">
                <div class="row">
                    @if(count($discounts) > 0)
                        <div class="col-sm-12">
                            <div class="row container-item-order">
                                <table id="customers">
                                    <thead>
                                       <tr >
                                            <th class="t-center">STT</th>
                                            <th class="t-center">Mã giảm giá</th>
                                            <th class="t-center">Loại</th>
                                            <th class="t-center">Sản phẩm giảm giá</th>
                                            <th class="t-center">Giá giảm</th>
                                            <th class="t-center">Bắt đầu</th>
                                            <th class="t-center">Kết thúc</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($discounts as $key=>$item)
                                        <tr class="t-center">
                                            <td>{{$key + 1}}</td>
                                            <td>{{ $item->discount_code }}</td>
                                            <td>{{ ($item->type == "product") ? "Sản phẩm" : "Danh mục"}}</td>
                                            <td>
                                                @foreach ($item->getItemDiscount() as $k=>$v)
                                                    <p>{{ $v->name }}</p>
                                                @endforeach
                                            </td>
                                            <td>{{ $item->sale_percent }}%</td>
                                            <td>{{ format_date($item->start_date) }}</td>
                                            <td>{{ format_date($item->end_date) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mt-3 paginate"><center>{{ $discounts->withQueryString()->links() }}</center></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-sm-12">
                            <div><center>Không có giảm giá</center></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section> 
@endsection
@section('script')
	<script>
      
    </script>
@endsection