@extends('layouts.bot')

@section('css')

    <style>

        .pull-right {
            float: right !important;
        }

        .text-righ {
            text-align: right !important;
        }

        .table td, .table th {
            padding: .75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        .table-monitor thead td, .table-monitor thead th, .table-monitor tbody tr {
            border-bottom-width: 2px;
            background: black;
            color: #28a745;
        }

        .table-monitor td {
            border: unset !important;
        }

    </style>

@endsection

@section('content')

    <div class="container-fluid pt-2">
        <div class="row">
            <div class="col-lg-8">
                <h3 for=""> <i class="fa fa-tachometer-alt"></i> แผงควบคุมบอท <span>Refresh Time Remain <span id="refreshTime"></span></span></h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>แบรนด์</th>
                            <th>SERVER IP</th>
                            {{-- <th>บอทธนาคาร</th> --}}
                            <th>บอทเติมเงิน</th>
                            <th>บอทถอนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brands as $brand)
                            <tr id="tr_{{$brand->id}}">
                                <td>
                                    {{$brand->name}} #{{$brand->id}}
                                </td>
                                <td align="right">
                                    <span id="bot_ip_{{$brand->id}}">
                                        {{$brand->bot_ip}}
                                    </span>
                                </td>
                                {{-- <td width="200" align="center">
                                    <i class="fa fa-circle @if($brand->status_bot_bank == 0) text-danger @else text-success @endif" id="status_bank_{{$brand->id}}"></i>
                                </td> --}}

                                <td width="200" align="center">
                                    <i class="fa fa-circle @if($brand->status_bot_deposit == 0) text-danger @else text-success @endif" id="status_deposit_{{$brand->id}}"></i>
                                </td>

                                <td width="200" align="center">
                                    <i class="fa fa-circle @if($brand->status_bot_withdraw == 0) text-danger @else text-success @endif" id="status_withdraw_{{$brand->id}}"></i>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4" id="botEvent">
            </div>
        </div>
    </div>

    <div class="modal fade" id="unlockModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">_
                    <h5 class="modal-title" id="exampleModalLabel">ระบุรหัสความปลอดภัย</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="">รหัสบอท</label>
                            <input type="password" class="form-control" name="code" id="code">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="unlock()">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

    <script>
        
        var refresh_times = 600000;

        Array.prototype.remove = function() {
            var what, a = arguments, L = a.length, ax;
            while (L && this.length) {
                what = a[--L];
                while ((ax = this.indexOf(what)) !== -1) {
                    this.splice(ax, 1);
                }
            }
            return this;
        };

        // var _send = XMLHttpRequest.prototype.send;
        // XMLHttpRequest.prototype.send = function() {

        //     /* Wrap onreadystaechange callback */
        //     var callback = this.onreadystatechange;
        //     this.onreadystatechange = function() {
        //         if (this.readyState == 4) {
        //             /* We are in response; do something,
        //                 like logging or anything you want */
        //         }
        //         callback.apply(this, arguments);
        //     }

        //     _send.apply(this, arguments);
        // }

        var intervalBank;

        var intervalDeposit;

        var intervalWithdraw;

        var status_bot_bank = [];

        var status_bot_deposit = [];

        var status_bot_withdraw = [];

        var request_detect = [];

        var refresh_times = 1800;

        $(function() {
            getEvent();
            setInterval(function() {
                getEvent();
            },30000);
            checkStatus();
            setInterval(function() {
                if(status_bot_deposit.length == 0 && status_bot_withdraw == 0) {
                    checkStatus();
                }
            },20000);
            setInterval(function() {
                $('#refreshTime').html(refresh_times);
                refresh_times--;
                if(refresh_times <= 0) {
                    if(status_bot_deposit.length == 0 && status_bot_withdraw == 0) {
                        location.reload(true);
                    }
                    
                }
            },1500);

            // var brands = jQuery.parseJSON($('#brands').val());
            // $.each(brands, function(k,v) {
            //     status_bot_bank.push(v);
            // });
            // $('#unlockModal').modal('show');
        });


        // window.onbeforeunload = function() {
        //     return "Data will be lost if you leave the page, are you sure?";
        // };

        function checkStatus() {
            status_bot_bank = [];
            status_bot_deposit = [];
            status_bot_withdraw = [];
            $.get('{{route('bot.check-status')}}', function(r) {
                if(r.status_bot_bank.length > 0) {
                    $.each(r.status_bot_bank, function(k,v) {
                        console.log(v);
                        statusBotBank(v,'on')
                    });
                    status_bot_bank = r.status_bot_bank;
                }
                if(r.status_bot_deposit.length > 0) {
                    $.each(r.status_bot_deposit, function(k,v) {
                        console.log(v);
                        statusBotDeposit(v,'on')
                    });
                    status_bot_deposit = r.status_bot_deposit;
                }
                if(r.status_bot_withdraw.length > 0) {
                    $.each(r.status_bot_withdraw, function(k,v) {
                        console.log(v);
                        statusBotWithdraw(v,'on')
                    });
                    status_bot_withdraw = r.status_bot_withdraw;
                }
            });

        }

        function getEvent() {

            $('#botEvent').load('{{route('bot.event')}}', function(r) {

            });
        }

        function unlock() {

            var code = $('#code').val();

            $.post('{{route('bot.unlock')}}',  {code: code}, function(r){
                if(r.code == 500) {
                    alert(r.message);
                } else if (r.code == 0) {
                    $('#unlockModal').modal('hide');
                }
            });

        }

        function statusBotBank(brand_id,status_manual) {
            if(status_manual) {
                var status = status_manual;
            } else {
                var status = $('#status_bot_bank_' + brand_id).is(':checked') ? 'on' : 'off';
            }
            if(status === 'on') {
                $('#status_bank_' + brand_id).removeClass('text-danger');
                $('#status_bank_' + brand_id).addClass('text-success');
                botBank(brand_id);
                status_bot_bank.push(brand_id);
            } else if(status == 'off') {
                $('#status_bank_' + brand_id).addClass('text-danger');
                $('#status_bank_' + brand_id).removeClass('text-success');
                status_bot_bank.remove(brand_id);
            }
            // console.log('bank status : ' + status_bot_bank);
        }

        function statusBotDeposit(brand_id,status_manual) {
            if(status_manual) {
                var status = status_manual;
            } else {
                var status = $('#status_bot_deposit_' + brand_id).is(':checked') ? 'on' : 'off';
            }
            if(status === 'on') {
                botDeposit(brand_id);
                $('#status_deposit_' + brand_id).removeClass('text-danger');
                $('#status_deposit_' + brand_id).addClass('text-success');
                status_bot_deposit.push(brand_id);
            } else if(status == 'off') {
                $('#status_deposit_' + brand_id).addClass('text-danger');
                $('#status_deposit_' + brand_id).removeClass('text-success');
                status_bot_deposit.remove(brand_id);
            }
            // console.log('deposit status : ' + status_bot_deposit);
        }

        function statusBotWithdraw(brand_id,status_manual) {
            if(status_manual) {
                var status = status_manual;
            } else {
                var status = $('#status_bot_withdraw_' + brand_id).is(':checked') ? 'on' : 'off';
            }
            if(status === 'on') {
                $('#status_withdraw_' + brand_id).removeClass('text-danger');
                $('#status_withdrarw_' + brand_id).addClass('text-success');
                botWithdraw(brand_id);
                status_bot_withdraw.push(brand_id);
            } else if(status == 'off') {
                $('#status_withdraw_' + brand_id).addClass('text-danger');
                $('#status_withdraw_' + brand_id).removeClass('text-success');
                status_bot_withdraw.remove(brand_id);
            }
            // console.log('withdraw status : ' + status_bot_withdraw);
        }

        function botBank(brand_id) {

            // var timer = parseInt(Math.floor(Math.random() * 15) + 10 + '000');

            // $.post('/bot/bank/' + brand_id, {brand_id: brand_id} ,function(r) {
            //     status_bot_bank.remove(brand_id);
            //     // request_detect['bank'].push(brand_id);
            //     // if(r.status === true) {
            //     //     if(status_bot_bank.includes(brand_id)) {
            //     //         setTimeout(function() {
            //     //             botBank(brand_id);
            //     //         }, timer);
            //     //     }
            //     //     $('#bot_ip_' + brand_id).html(r.brand.bot_ip);
            //     // }
            // }).fail(function() {
            //     status_bot_bank.remove(brand_id);
            //     // request_detect['bank'].push(brand_id);
            //     // status_bot_bank.remove(brand_id);
            //     // $('#status_bot_bank_' + brand_id).bootstrapToggle('off');
            // });

        }

        function botDeposit(brand_id) {

            var timer = parseInt(Math.floor(Math.random() * 15) + 10 + '000');

            $.post('/bot/deposit/' + brand_id, {brand_id: brand_id} ,function(r) {
                status_bot_deposit.remove(brand_id);
                // request_detect['deposit'].push(brand_id);
                // if(r.status === true) {
                //     if(status_bot_deposit.includes(brand_id)) {
                //         setTimeout(function() {
                //             botDeposit(brand_id);
                //         }, timer);
                //     }
                //     $('#bot_ip_' + brand_id).html(r.brand.bot_ip);
                // }
            }).fail(function() {
                status_bot_deposit.remove(brand_id);
                // console.log('deposit: warning' + brand_id);
                // request_detect['deposit'].push(brand_id);
                // status_bot_deposit.remove(brand_id);
                // $('#status_bot_deposit_' + brand_id).bootstrapToggle('off');
            });
        }

        function botWithdraw(brand_id) {

            var timer = parseInt(Math.floor(Math.random() * 15) + 10 + '000');

            $.post('/bot/withdraw/' + brand_id, {brand_id: brand_id} ,function(r) {
                // alert(brand_id);
                status_bot_withdraw.remove(brand_id);
                // request_detect['withdraw'].push(brand_id);
                // if(r.status === true) {
                //     if(status_bot_withdraw.includes(brand_id)) {
                //         setTimeout(function() {
                //             botWithdraw(brand_id);
                //         }, timer);
                //     }
                //     $('#bot_ip_' + brand_id).html(r.brand.bot_ip);
                // }
            }).fail(function() {
                status_bot_withdraw.remove(brand_id);
                // request_detect['withdraw'].push(brand_id);
                // status_bot_withdraw.remove(brand_id);
                // status_bot_wfbot_withdraw_' + brand_id).bootstrapToggle('off');
            });

        }

    </script>

@endsection
