@extends('layouts.bot')

@section('css')

    <style>

        table tr {
            font-size: 12px;
        }

        table tr td {
            padding: 2px;
        }

    </style>
    
@endsection

@section('content')

    <div class="container-fluid mt-5" style="display: none;" id="brandDetail">
        <h2 id="brandName"></h2>
        <input type="hidden" id="brandId" value="">
        <br>
        {{-- <div class="row">
            <div class="col-lg-4">
                <h2>บอทธนาคาร</h2>
                <button class="btn btn-primary btn-block" onclick="startBotBank()" id="buttonStartBank">
                    <i class="fa fa-play"></i>&nbsp;&nbsp;เริ่มทำงาน
                </button>
                <button class="btn btn-danger btn-block" style="display: none;" onclick="stopBotBank()" id="buttonStopBank">
                    <i class="fa fa-stop"></i>&nbsp;&nbsp;หยุดทำงาน
                </button>
            </div>
            <div class="col-lg-4">
                <h2>บอทเติมเงิน</h2>
                <button class="btn btn-primary btn-block" onclick="startBotDeposit()" id="buttonStartDeposit">
                    <i class="fa fa-play"></i>&nbsp;&nbsp;เริ่มทำงาน
                </button>
                <button class="btn btn-danger btn-block" style="display: none;" onclick="stopBotDeposit()" id="buttonStopDeposit">
                    <i class="fa fa-stop"></i>&nbsp;&nbsp;หยุดทำงาน
                </button>
            </div>
            <div class="col-lg-4">
                <h2>บอทถอนเงิน</h2>
                <button class="btn btn-primary btn-block" onclick="startBotWithdraw()" id="buttonStartWithdraw">
                    <i class="fa fa-play"></i>&nbsp;&nbsp;เริ่มทำงาน
                </button>
                <button class="btn btn-danger btn-block" style="display: none;" onclick="stopBotWithdraw()" id="buttonStopWithdraw">
                    <i class="fa fa-stop"></i>&nbsp;&nbsp;หยุดทำงาน
                </button>
            </div>
        </div> --}}
    </div>
    <br>
    <div class="container-fluid mt-4" id="brandLists">
        
    </div>

    <div class="modal fade" id="unlockModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="">ระบุไอดีของ Manager</label>
                            <input type="text" class="form-control" name="code" id="code">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="setBrand()">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

    <script>
        
        window.onbeforeunload = function() {
            return "Data will be lost if you leave the page, are you sure?";
        };

        var active = 0;

        var timeStatement = 0;

        var runStatement;

        var runDeposit;

        var runWithdraw;

        var brandId;

        $(function() {
            $('#unlockModal').modal('show');
        });

        function setBrand() {

            var brand_id = $('#brand_id').val();

            var code = $('#code').val();

            if(brand_id == '') {
                alert('กรุณาเลือกแบรนด์');
                exit;
            }

            $.post('{{route('agent.bot.set-brand')}}', {brand_id: brand_id, code: code}, function(r) {
                if(r.code == 500) {
                    alert(r.message);
                } else if (r.code == 0) {
                    brandId = r.brand.id;
                    $('#brandName').html(r.brand.name);
                    $('#agentUsername').html(r.agent_username);
                    $('#brandId').val(r.brand.id);
                    $('#brandDetail').show();
                    setInterval(function() {
                        brandLists(r.brand);
                    },5000)
                    $('#unlockModal').modal('hide');
                    
                }
            });

        }

        // function startBotBank() {
        //     $('#buttonStartBank').hide();
        //     $('#buttonStopBank').show();
        //     runStatement = setInterval(function(){
            //bot bank
        //             console.log('get bank');
        //         });
        //     },10000);
        // }

        // function stopBotBank() {
        //     $('#buttonStartBank').show();
        //     $('#buttonStopBank').hide();
        //     clearInterval(runStatement);
        // }

        // function startBotDeposit() {
        //     $('#buttonStartDeposit').hide();
        //     $('#buttonStopDeposit').show();
        //bot start

        //     });
        //     runDeposit = setInterval(function(){
            //bot deposit
                    
        //         });
        //     },10000);
        // }

        // function stopBotDeposit() {
        //     $('#buttonStartDeposit').show();
        //     $('#buttonStopDeposit').hide();
        //bot stop
                
        //     });
        //     clearInterval(runDeposit);
        // }

        // function startBotWithdraw() {
        //     $('#buttonStartWithdraw').hide();
        //     $('#buttonStopWithdraw').show();
        //     runWithdraw = setInterval(function(){
            //bot withdraw
                    
        //         });
        //     },10000);
        // }

        // function stopBotWithdraw() {
        //     $('#buttonStartWithdraw').show();
        //     $('#buttonStopWithdraw').hide();
        //     clearInterval(runWithdraw);
        // }

        function brandLists(brand) {

            $('#brandLists').load('/brand-lists/' + brand.id, function() {
                
            });

        }

        // function restartBot() {

            // bot restart
        //         $('#restartBot').fadeOut();
        //     }); 

        // }

    </script>
    
@endsection