<table class="table table-index w-full">
    <thead>
    <tr>
        <th></th>
        <th>07</th><th></th><th>08</th><th></th><th>09</th><th></th><th>10</th><th></th><th>11</th><th></th>
        <th>12</th><th></th><th>13</th><th></th><th>14</th><th></th><th>15</th><th></th><th>16</th><th></th>
        <th>17</th><th></th><th>18</th><th></th><th>19</th><th></th><th>20</th><th></th><th>21</th><th></th><th>22</th><th></th><th>23</th><th></th>
    </tr>
    </thead>
    <tbody>
        @php $hours = ['07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30',
        '17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00','22:30','23:00' ] @endphp
        @foreach($availabilities as $avalability)
            <tr>
                <td><small>{{$avalability['resource']}}</small></td>
                @php $i = 0; @endphp
               @foreach($hours as $hour)
                    @if(isset($avalability['slots'][$i]) && $avalability['slots'][$i]['start_time'] === $hour)
                        <td style="border: 1px #CCC solid;" class="bg-green-100"></td>
                        @php $i++; @endphp
                    @else
                        <td style="border: 1px #CCC solid;" class="bg-gray-100"></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
<div class="new_tenant__main">
    <div class="new_tenant__card">
<div class="bbq2">
    <div class="bbq2__search"></div>
        <div class="bbq2__grid">
            <div class="bbq2__resources">
                <div style="height: 40px; line-height: 40px;"></div>
                @foreach($availabilities as $avalability)
                    <div class="bbq2__resource" style="height: 40px; line-height: 40px;">
                        <div class="bbq2__resource__label">
                            {{$avalability['resource']}}
                        </div>
                    </div>
                @endforeach
                <div class="bbq2__availability">
                    <div class="bbq2__grid-lines" style="background-image: linear-gradient(to right, transparent 0px, transparent 39px, rgb(242, 242, 242) 39px, rgb(242, 242, 242) 40px), linear-gradient(transparent 0px, transparent 39px, rgb(242, 242, 242) 39px, rgb(242, 242, 242) 40px); background-size: 40px 100%, 100% 40px;"></div>
                    <div class="bbq2__hours">
                    @foreach($hours as $hour)
                            <div class="bbq2__hour" style="width: 40px; height: 40px; line-height: 40px;">{{$hour}}</div>
                    @endforeach
                    </div>
                    <div class="bbq2__slots">
                    @foreach($availabilities as $avalability)
                            <div class="bbq2__slots-resource" style="height: 40px; line-height: 40px;">
                                @php $i = 0; @endphp
                                @foreach($hours as $hour)
                                    @if(isset($avalability['slots'][$i]) && $avalability['slots'][$i]['start_time'] === $hour)
                                        <div class="bbq2__slot">
                                            <div class="bbq2-open">
                                                <div class="bbq2-open__fill" style="width: 60px;"><div></div></div>
                                            </div>
                                        </div>
                                        @php $i++; @endphp
                                    @else
                                        <div class="bbq2__hole bg-gray-300"><div></div></div>
                                    @endif
                                @endforeach
                            </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
</div>
<style>
    .new_tenant__main {
        flex-grow: 1;
        flex-shrink: 1;
    }

    .new_tenant__card {
        border-radius: 8px;
        box-shadow: -1px 5px 17px 0 rgba(0,0,0,.1);
        background-color: #fff;
    }

    .bbq2__grid {
        display: flex;
    }

    .bbq2__resources {
        width: 160px;
        border-color: #f2f2f2;
        border-style: solid;
        border-width: 0 1px;
    }

    .bbq2__resources>div {
        box-sizing: border-box;
        border-bottom: 1px solid #f2f2f2;
    }
    .bbq2__resource {
        position: relative;
    }

    .bbq2__availability {
        position: relative;
    }

    .bbq2__grid-lines {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        pointer-events: none;
    }

    .bbq2__hours {
        display: flex;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        pointer-events: none;
        z-index: 100;
        background-color: hsla(0,0%,100%,.5);
    }

    .bbq2__hour {
        box-sizing: border-box;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        color: rgba(1,1,1,.4);
        border-bottom: 1px solid #f2f2f2;
    }

    .bbq2__slots {
        position: relative;
    }

    .bbq2__slots-resource {
        position: relative;
        border-bottom: 1px solid #f2f2f2;
    }

    .bbq2__slot {
        position: absolute;
        top: 0;
        bottom: 0;
    }

    .bbq2-open {
        cursor: pointer;
        background-color: transparent;
    }
    .bbq2-open__fill {
        display: block;
        margin: 0;
        padding: 1px 2px 1px 1px;
        border: 0;
        pointer-events: none;
        visibility: hidden;
    }
    .bbq2-open, .bbq2-open__fill {
        height: 100%;
        box-sizing: border-box;
        position: relative;
    }

    .bbq2__hole {
        position: absolute;
        top: 0;
        bottom: 0;
        box-sizing: border-box;
        padding: 1px 2px 1px 1px;
    }
</style>
