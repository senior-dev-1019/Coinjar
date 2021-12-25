

<div class="col-md-6 text-center">
     <table class = "table">
          <thead>
               <tr>
                    <th colspan="2" scope="colgroup" style = "border: 1px solid black;border-top: none !important;border-left: none !important;border-right: none !important; font-size:28px;">Buy</th>
               </tr>
          </thead>
          <tbody>

               @for($j = 0; $j < count($exchange_buyer); $j++)
                    <tr>
                         @if ($exchange->exchange3 == 1)
                         <td class="minval3" style = "border: 1px solid black;border-right: none !important;">{{number_format((float)$exchange_buyer[ $j ]["price"], 5, '.', '')}}</td>
                         <td class="volume-td" class="minval3" style = "border: 1px solid black;border-left: none !important;">{{number_format((float)$exchange_buyer[ $j ]["size"], 5, '.', '')}}</td>
                         <td class="volume-td" class="minval3" style = "border: 1px solid black;border-left: none !important;">{{$exchange_buyer[$j]["timestamp"]}}</td>
                         @else
                         <td style = "border: 1px solid black;border-right: none !important;background-color: grey;">0</td>
                         <td style = "border: 1px solid black;border-left: none !important;background-color: grey;">0</td>
                         <td style = "border: 1px solid black;border-left: none !important;background-color: grey;">0</td>
                         @endif
                    </tr>
               @endfor
               <tr>
                    <th rowspan="1" scope="rowgroup" style="border-left: 1px solid; border-bottom: 1px solid;">Time Stamp</th>
                    <td colspan="2" style="border-right: 1px solid; border-bottom: 1px solid;" id="timestampbuyer">{{$lasttimestamp_buy}}</td>
               </tr>
          </tbody>
     </table>
</div>

<div class="col-md-6 text-center">
     <table class = "table">
          <thead>
               <tr>
                    <th colspan="2" scope="colgroup" style = "border: 1px solid black;border-top: none !important;border-left: none !important;border-right: none !important; font-size:28px">Sell </th>
               </tr>
          </thead>
          <tbody>
               @for($i = 0; $i < count($exchange_seller); $i++)
                    <tr>
                         @if ($exchange->exchange3 == 1)
                         <td style = "border: 1px solid black;border-right: none !important;">{{number_format((float)$exchange_seller[ $i ]["price"], 5, '.', '')}}</td>
                         <td class="volume-td" style = "border: 1px solid black;border-left: none !important;">{{number_format((float)$exchange_seller[$i]["size"], 5, '.', '')}}</td>
                         <td class="volume-td" style = "border: 1px solid black;border-left: none !important;">
                         {{$exchange_seller[ $i ]["timestamp"]}}</td>
                         @else
                         <td style = "border: 1px solid black;border-right: none !important;background-color: grey;">0</td>
                         <td style = "border: 1px solid black;border-left: none !important;background-color: grey;">0</td>
                         <td style = "border: 1px solid black;border-left: none !important;background-color: grey;">0</td>
                         @endif
                    </tr>
               @endfor
               <tr>
                    <th rowspan="1" scope="rowgroup" style="border-left: 1px solid; border-bottom: 1px solid;">Time Stamp</th>
                    <td colspan="2" style="border-right: 1px solid; border-bottom: 1px solid;"  id="timestampseller">{{$lasttimestamp_sell}}</td>
               </tr>
          </tbody>
     </table>
</div>

<input type="text" id="check-seller" value="{{ $data->seller_sound_state }}" style="display: none">
<input type="text" id="check-buyer" value="{{ $data->buyer_sound_state }}" style="display: none">
<input type="text" id="sound-seller" value="{{ $data->seller_sound }}" style="display: none">
<input type="text" id="sound-buyer" value="{{ $data->buyer_sound }}" style="display: none">
<input id="last_price_sell" value= "{{ $lastprice_sell }}" style="display:none">
<input id="last_price_buy" value= "{{ $lastprice_buy }}" style="display:none">
<input id="lasttimestamp_sell" value= "{{ $lasttimestamp_sell }}" style="display:none">
<input id="lasttimestamp_buy" value= "{{ $lasttimestamp_buy }}" style="display:none">

<audio src="./uploads/seller/{{ $data->seller_sound }}" id="audio_sell"></audio>
<audio src="./uploads/buyer/{{ $data->buyer_sound }}"  id="audio_buy"></audio>
