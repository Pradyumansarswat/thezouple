@extends('front.layout.default_layout')
@section('content')
<div class="container"> 
    <div class="row justify-content-center py-5 mt-5">
        <div class="col-md-10">
            <!--<table class="table">
                <tr>
                    <th colspan="2">Order Details</th>
                </tr>
                @foreach($order_detais as $data)
                <tr>
                    <td>Order Number</td>
                    <td>{{$data->order_number}}</td>
                </tr>
                <tr>
                    <td>Amount</td>
                    <td>{{$data->amount	}}</td>
                </tr>
                <tr>
                    <td>Transaction ID</td>
                    <td>{{$data->transaction_id	}}</td>
                </tr>
                <tr>
                    <td>Date & Time </td>
                    <td>{{$data->order_date	}}</td>
                </tr>
                <tr>
                    <td>Payment Status </td>
                    <td>{{$data->payment_status	}}</td>
                </tr>
                @endforeach
            </table>-->
            @if(isset($mailsend))
            <?php echo $mailsend;?>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection