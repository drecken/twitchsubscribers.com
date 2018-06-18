@extends('layouts.main')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<style>
    .user-info {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        /* height: 50px; */
    }
    .user-info > div{
        padding: 5px;
    }
    div.date{
        color: gray;
        font-size: 11px;
    }
    .dataTables_length select,
    .dataTables_filter input{
        display: inline-block;
        width: inherit !important;
        height: inherit !important;
    }
    .content{
        padding-bottom: 30px;
    }
    #subLevelCheckboxes{
        width: 100%;
        max-width: 400px;
        text-align: center;
        margin: auto;
    }
    #subLevelCheckboxes tr{
        border: none;
    }
    #subLevelCheckboxes td{
        text-align: center;
    }
    #subLevelCheckboxes td span{
        padding: 10px;
    }
</style>
<h1>You have {{ number_format(count($subscribers), 0) }} {{ str_plural('subscriber', count($subscribers)) }}</h1>
<div class="content">
    
    <div class="tier-selectors">
        <form action="#">
            <table id="subLevelCheckboxes">
                <tr>
                    <td>
                    <label for="prime">
                        <input id="prime" data-level="p" type="checkbox" class="filled-in tier-checkbox" checked="checked" name="prime"/>
                        <span></span>
                        <div>{{ number_format($counts['prime']) }}</div>
                        <div>Prime</div>
                    </label>
                    </td>
                    <td>
                    <label for="tier1">
                        <input id="tier1" data-level="1" type="checkbox" class="filled-in tier-checkbox" checked="checked" name="tier1"/>
                        <span></span>
                        <div>{{ number_format($counts['1']) }}</div>
                        <div>Tier 1</div>
                    </label>
                    </td>
                    <td>
                    <label for="tier2">
                        <input id="tier2" data-level="2" type="checkbox" class="filled-in tier-checkbox" checked="checked" name="tier2"/>
                        <span></span>
                        <div>{{ number_format($counts['2']) }}</div>
                        <div>Tier 2</div>
                    </label>
                    </td>
                    <td>
                    <label for="tier3">
                        <input id="tier3" data-level="3" type="checkbox" class="filled-in tier-checkbox" checked="checked" name="tier3"/>
                        <span></span>
                        <div>{{ number_format($counts['3']) }}</div>
                        <div>Tier 3</div>
                    </label>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    
    <table class="highlight" id="subscribersTable" data-order='[[ 1, "asc" ]]' data-page-length='10'>
        <thead>
            <tr>
                <th>Sub Date</th>
                <th>User</th>
                <th>Sub Level</th>
            </tr>
        </thead>
        @foreach($subscribers as $subscriber)
        <tr>
            <td class="sub-date" data-sort="{{ $subscriber['date'] }}" data-subdate="{{ $subscriber['date'] }}">
                <div class="date-relative"></div>
                <div class="date"></div>
            </td>
            <td>
                <div class="user-info">
                    <div>
                        @if($subscriber['logo'])
                        <img src="{{ $subscriber['logo'] }}" height="50px">
                        @else
                        <img src="https://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_70x70.png" height="50px">
                        @endif
                    </div>
                    <div>
                        {{ $subscriber['name'] }} (<a href="https://www.twitch.tv/{{ $subscriber['displayName'] }}">{{ $subscriber['displayName'] }}</a>)
                    </div>
                </div>
            </td>
            <td>
                @if($subscriber['tier'] == 'prime')
                Prime                
                @else
                Tier {{ $subscriber['tier'] }}
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>

<script src="/js/moment.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<script>
    var dataTable;

    $(document).ready(function () {
        $(".sub-date").each(function(v){
            var m = moment($(this).data('subdate').trim());
            $(this).children('.date').html(m.format("MMM Do, YYYY @ h:mm a"));
            $(this).children('.date-relative').html(m.fromNow());
        });
        dataTable = $('#subscribersTable').DataTable({
            "columns": [
            { "searchable": false },
            null,
            null
            ]
        });

        $(".tier-checkbox").on("change", function(){
                var regex = "";
                var tiers = [];
            $('.tier-checkbox').each(function(k,v){
                if ($(v).is(':checked')) {
                    tiers.push($(v).data('level'));
                }
            });
                console.debug(tiers);
                if (tiers.length > 0) {
                    regex = '[' + tiers.join() + ']';
                }
            dataTable.search('').column(2).search(regex,true).draw();
        });
    });
</script>
@endsection