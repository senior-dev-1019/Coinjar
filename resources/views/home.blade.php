@extends('layouts.app')

@section('content')
<script src="{{asset('js/select2.min.js')}}" type='text/javascript'></script>
<link href="{{asset('css/select2.min.css')}}" rel='stylesheet' type='text/css'>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="d-flex" style="padding: 20px">
                    <div>
                        <select id='selUser' style='width: 200px;'>
                        </select>   
                        <input type='button' value='Select Option' id='but_read'>
                    </div>
                    <div style="margin-left: 20px; font-size:18px; font-weight:600">
                        <div id='result'>Selected : {{ $pair->selectedpair }}</div>
                    </div>
                </div>
                
                <div style="padding: 0px 10px">
                    <div class="col-md-12">
                        <label class="col-md-2" for="api_token">Api Key : </label>
                        <input type="text" id="api_key" style="margin-left: 10px; margin-right: 10px; width: 420px" value="{{ $pair->api_key }}">
                    </div>
                    <div class="col-md-12">
                        <label class="col-md-2" for="api_token">Secret Key : </label>
                        <input type="text" id="secret_key" style="margin-left: 10px; margin-right: 10px; width: 420px" value="{{ $pair->secret_key }}"> 
                        <button id="btn_token">Set Token</button>
                    </div>
                </div>

                <hr>

                <div class="card-body" id = "ajax-board" style = "overflow-x: scroll;background-color: white;color: black;">
                <div id = "ajax-item" class="d-flex text-center">
                    
                    <div class="col-md-6 col-sm-6" >
                        <table class = "table"> 
                            <thead>
                                <tr>
                                    <th colspan="2" scope="colgroup" style = "border: 1px solid white;border-top: none !important;border-left: none !important;border-right: none !important; font-size:28px">Buy </th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @for($j = 0; $j < count($exchange_buyer); $j++)
                                    <tr>
                                        @if ($exchange->exchange3 == 1)
                                        <td style = "border: 1px solid white;border-right: none !important;">{{number_format((float)$exchange_buyer[$j]["price"], 5, '.', '')}}</td>
                                        <td style = "border: 1px solid white;border-left: none !important;">{{number_format((float)$exchange_buyer[$j]["size"], 5, '.', '')}}</td>
                                        <td style = "border: 1px solid white;border-left: none !important;">{{$exchange_buyer[$j]["timestamp"]}}</td>
                                        @else
                                        <td style = "border: 1px solid white;border-right: none !important;background-color: grey;">0</td>
                                        <td style = "border: 1px solid white;border-left: none !important;background-color: grey;">0</td>
                                        <td style = "border: 1px solid white;border-left: none !important;background-color: grey;">0</td>
                                        @endif
                                        
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6 col-sm-6" style="border-left: 1px solid; padding:1px">
                        <table class = "table" >
                            <thead>
                                <tr>
                                <th colspan="2" scope="colgroup" style =  "border: 1px solid white;border-top: none !important;border-left: none !important;border-right: none !important; font-size:28px">Sell </th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 0; $i < count($exchange_seller); $i++)
                                    <tr>
                                        @if ($exchange->exchange3 == 1)
                                        <td style = "border: 1px solid white;border-right: none !important;">{{number_format((float)$exchange_seller[ $i ]["price"], 5, '.', '')}}</td>
                                        <td style = "border: 1px solid white;border-left: none !important;">{{number_format((float)$exchange_seller[ $i ]["size"], 5, '.', '')}}</td>
                                        <td style = "border: 1px solid white;border-left: none !important;">{{$exchange_seller[ $i ]["timestamp"]}}</td>
                                        @else
                                        <td style = "border: 1px solid white;border-right: none !important;background-color: grey;">0</td>
                                        <td style = "border: 1px solid white;border-left: none !important;background-color: grey;">0</td>
                                        <td style = "border: 1px solid white;border-left: none !important;background-color: grey;">0</td>
                                        @endif
                    
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <div style="padding: 20px">
                    <h5>Sound Setting</h5>
                </div>

                <hr>

                <div class="d-flex">
                    <div class="col-md-6 col-sm-6" style="padding: 10px 20px">
                        <div class="col-md-12">
                            @if($pair->buyer_sound_state == 0)
                                <input id="check_buyer" type="checkbox">
                                <label for="check_buyer">Select buyer Alert</label> 
                            @else
                                <input id="check_buyer" type="checkbox" checked>
                                <label for="check_buyer">Select buyer Alert</label> 
                            @endif
                        </div>
                        <div>
                            <div>
                                <form method="POST" enctype="multipart/form-data" id="form_buyer">
                                    @csrf
                                    <input type="file" name="file[]" id="file" accept="audio/*" >
                                    <br>
                                    <label style="margin-top: 10px" for="file"><span style="font-weight: 500">Selected Sound:</span> {{ $pair->buyer_sound }}</label>
                                </form>
                            </div>
                            <div class="col-md-12 text-right">
                                <button id="btn_buyer" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-6 col-sm-6" style="border-left: 1px solid; padding: 10px 20px">
                        <div>
                            @if($pair->seller_sound_state == 0)
                                <input id="check_seller" type="checkbox">
                                <label for="check_seller">Select Seller Alert</label> 
                            @else
                                <input id="check_seller" type="checkbox" checked>
                                <label for="check_seller">Select Seller Alert</label> 
                            @endif
                        </div>
                        
                        <div class="col-md-12">
                            <form method="POST" enctype="multipart/form-data" id="form_seller">
                                @csrf
                                <input type="file" name="file[]" id="file" accept="audio/*">
                                <br>
                                <label style="margin-top: 10px" for="file"><span style="font-weight: 500">Selected Sound:</span> {{ $pair->seller_sound }}</label>
                            </form> 
                            <div class="col-md-12 text-right">
                                <button id="btn_seller" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var pair = ["USDT/AUD", "AAVE/USDC","WBTC/BTC","XLM/BTC","UNI/USDC","KNC/GBP","XLM/GBP","BTC/USDC",
    "COMP/AUD","ETH/USDT","KNC/BTC","LTC/USDT","OMG/BTC","MATIC/USDT","BAT/AUD","COMP/BTC","LTC/USDC","XRP/GBP","SUSHI/BTC","XRP/AUD","LTC/AUD","AAVE/BTC","UNI/AUD","ETH/BTC","EOS/BTC","GBP/AUD","XRP/BTC","ENJ/BTC",
    "BNT/GBP","ALGO/AUD","USDC/GBP","LINK/BTC","COMP/USDC","BTC/USDT","ETH/GBP","BAT/GBP","XLM/AUD","MKR/USDC","LTC/BTC","UNI/BTC","SUSHI/USDC","YFI/USDC","BTC/GBP","LINK/GBP","DAI/AUD","OMG/GBP",
    "ETH/USDC","USDC/AUD","ZRX/GBP","UNI/GBP","BTC/DAI","SNX/USDC","CRV/AUD","MKR/BTC","YFI/AUD","MATIC/AUD","ZRX/BTC","OMG/USDC","KNC/AUD","ZRX/USDC","XLM/USDC","ENJ/AUD","BAT/USDC","MATIC/BTC","LTC/GBP","ETH/AUD","YFI/BTC","XRP/USDC","MATIC/USDC","GRT/USDC",
    "USDT/USDC","KNC/USDC","ALGO/USDC","UNI/USDT","BTC/AUD","USDT/GBP","MATIC/GBP","ENJ/GBP","LINK/AUD","EOS/AUD"]
    var length = pair.length;
    for(var i=0; i < length; i++){
        let setvalue = pair[i].replace("/","");
        $('#selUser').append("<option value="+ setvalue +">"+pair[i]+"</option>");
    }
    $('#selUser').val("{{ $pair->selectedpair }}");

    $("#selUser").select2();
    
    // Read selected option
    $('#but_read').click(function(){
        var selected = $('#selUser option:selected').text();
        let senddata = selected.replace("/","");
        $('#result').html(" Selected : " + selected);
        $.ajax({
            method: 'post',
            url: '/setpair',
            data:{
                "_token": "{{ csrf_token() }}", "selected": senddata
            },
            success: function (data){
                console.log(data);
            }
        });
    });

    $('#btn_token').click(function(){
        var token = $('#api_key').val();
        var secret = $('#secret_key').val();
        confirm("Change API Token ?");
        $.ajax({
            method: 'post',
            url: '/settoken',
            data: {
                "_token": "{{ csrf_token() }}", "api": token, "secret": secret
            },
            success: function (data){
                console.log(data);
            }
        })
    })

    $('#btn_seller').click(function(){
        var formData = new FormData($('#form_seller')[0]);
        $.ajaxSetup({
            headers: {
                "_token": "{{ csrf_token() }}"
            }
        });
        $.ajax({
            method: 'post',
            url: '/setselleraudio',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data){
                console.log(data);
            }
        });
    });

    $('#btn_buyer').click(function(){
        var formData = new FormData($('#form_buyer')[0]);
        $.ajaxSetup({
            headers: {
                "_token": "{{ csrf_token() }}"
            }
        });
        $.ajax({
            method: 'post',
            url: '/setbuyeraudio',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data){
                console.log(data);
            }
        });
    });

    $('#check_seller').click(function(){
        if(this.checked){
            $.ajax({
                method: 'post',
                url: '/sellercheckstate',
                data:{
                    "_token": "{{ csrf_token() }}", "seller_check_state": "on"
                },
                success: function (data){
                    console.log(data);
                }
            });
        }else{
            $.ajax({
                method: 'post',
                url: '/sellercheckstate',
                data:{
                    "_token": "{{ csrf_token() }}", "seller_check_state": "off"
                },
            success: function (data){
                    console.log(data);
                }
            });
        }
    });

    $('#check_buyer').click(function(){
        if(this.checked){
            $.ajax({
                method: 'post',
                url: '/buyercheckstate',
                data:{
                    "_token": "{{ csrf_token() }}", "buyer_check_state": "on"
                },
                success: function (data){
                    console.log(data);
                }
            });
        }else{
            $.ajax({
                method: 'post',
                url: '/buyercheckstate',
                data:{
                    "_token": "{{ csrf_token() }}", "buyer_check_state": "off"
                },
            success: function (data){
                    console.log(data);
                }
            });
        }
    });
    
    function myFunction() {
      document.getElementById("myDropdown").classList.toggle("show");
    }
    
    function filterFunction() {
      var input, filter, ul, li, a, i;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      div = document.getElementById("myDropdown");
      a = div.getElementsByTagName("a");
      for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          a[i].style.display = "";
        } else {
          a[i].style.display = "none";
        }
      }
    }

   
</script>
    
@endsection
