<div class="table-responsive">
<table class="table table-striped table-hover" id="users-table">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Sesso</th>
            <th>Email</th>
            <th>Cellulare</th>
            <th>{!! trans('labels.role') !!}</th>
            <th>Città</th>
            <th>Circolo</th>
            <th>{!! trans('labels.status') !!}</th>
            <th>{!! trans('labels.created_at') !!}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>
                {!! Form::open(['route' => ['admin.users.destroy', $user->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.users.edit', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Sei sicuro?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>

            <td style="width: 30px">
                @php
                $avatar = false
                @endphp
                @if(  count($user->metas) > 0 )
                    @foreach($user->metas as $meta)
                        @if($meta->meta == 'avatar' && !empty($meta->meta_value))
                            <img src="{!! env('APP_URL') !!}/storage/{!! $meta->meta_value !!}" class="img-circle pull-left" style="width: 40px; height: 40px;">
                            @php
                            $avatar = true
                            @endphp
                        @endif
                    @endforeach
                    @if(!$avatar)
                        <img src="https://via.placeholder.com/40?text=?" class="img-circle pull-left">
                    @endif
                @else
                    <img src="https://via.placeholder.com/40?text=?" class="img-circle pull-left">
                @endif
            </td>
            <td>{!! $user->name !!}</td>
            <td>{!! $user->surname !!}</td>
            <td>@if($user->gender == 'm') Maschio @else Femmina @endif</td>
            <td>{!! $user->email !!}</td>
            <td>{!! $user->mobile_phone !!}</td>
            <td>{!! trans('labels.'.$user->role->name) !!}</td>
            <td>
                @if( $user->city )
                {!! $user->city->name !!}
                @endif
            </td>
            <td>
                @if( $user->id_role == 2)
                    @if( $user->clubs )
                        {!! $user->clubs->name !!}
                    @endif
                @elseif( $user->id_role == 3)
                    @if( App\Models\Club::where('id_user', '=', $user->id)->first() )
                        {!! App\Models\Club::where('id_user', '=', $user->id)->first()->name !!}
                    @endif
                @endif
            </td>
            <td>
                @if( $user->status == 0 ) <label class="label label-danger">Disabilitato</label>
                @elseif( $user->status == 1 ) <label class="label label-success">Attivo</label>
                @endif
            </td>
            <td>
                {!! $user->created_at->format('d/m/Y H:i:s') !!}
            </td>

        </tr>
    @endforeach
    </tbody>
</table>
</div>
