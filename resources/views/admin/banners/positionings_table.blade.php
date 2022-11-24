<table class="table table-striped">
    <tr>
        <th>
            Associa
        </th>
        <th>
            Partner
        </th>
        <th>
            Banner
        </th>
    </tr>
@foreach($banners as $banner)
@php
    $found = false;    
    foreach($positionings as $pos):
        if($pos->id_banner == $banner->id):
            $found = true; 
            break;
        endif;
    endforeach;    
@endphp
<tr>    
    <td><input type="checkbox" name="chk_positions[]" value="{{ $banner->id }}" style="width: 20px; height: 20px;"
        @if($found) checked @endif 
        ></td>
    <td>{{ $banner->partner->name }}</td>
    <td><img src="{{ asset('storage/'.$banner->filename) }}" style="width: 200px; height: auto"></td>
</tr>
@endforeach
</table>
<input type="hidden" id="id_position" value="{{ $id_position }}">