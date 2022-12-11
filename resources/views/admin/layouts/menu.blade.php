
<li>
    <a href="/"><i class="fa fa-home"></i><span>Homepage</span></a>
</li>

@if( $role->name === 'administrator' )
<li class="{{ Request::is('*users*') ? 'active' : '' }}">
    <a href="{!! route('admin.users.index') !!}"><i class="fa fa-users"></i><span>Utenti</span></a>
</li>

<li class="{{ Request::is('*partners*') ? 'active' : '' }}">
    <a href="{!! route('admin.partners.index') !!}"><i class="fa fa-handshake-o"></i><span>Partners</span></a>
</li>


<li class="treeview {{ (Request::is('*banners') ) ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-bullhorn"></i>
        <span>Banner</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="{{ ( Request::is('*banners*') ) ? '' : 'display: none' }}">
        <li class="{{ Request::is('*banners*') ? 'active' : '' }}">
            <a href="{!! route('admin.banners.positions') !!}"><i class="fa fa-circle"></i><span>Posizioni</span></a>
        </li>
        <li class="{{ Request::is('*banners*') ? 'active' : '' }}">
            <a href="{!! route('admin.banners.index') !!}"><i class="fa fa-circle"></i><span>Banner</span></a>
        </li>
    </ul>
</li>

<!--li class="treeview">
    <a href="#">
        <i class="fa fa-image"></i>
        <span>Media</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="display: none;">
        <li class="{{ Request::is('*images*') ? 'active' : '' }}">
            <a href="{!! route('admin.images.index') !!}"><i class="fa fa-image"></i><span>Immagini</span></a>
        </li>
    </ul>
</li-->


<li class="treeview {{ (Request::is('*countries*') || Request::is('*cities*') ||  Request::is('*zones*') ) ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-newspaper-o"></i>
        <span>News</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="{{ (Request::is('*countries*') || Request::is('*cities*') ||  Request::is('*zones*') ) ? '' : 'display: none' }}">
        <li class="{{ Request::is('*news*') ? 'active' : '' }}">
            <a href="{!! route('admin.news.index') !!}"><i class="fa fa-circle"></i><span>Elenco news</span></a>
        </li>
        <li class="{{ Request::is('*cities*') ? 'active' : '' }}">
            <a href="{!! route('admin.newsCategories.index') !!}"><i class="fa fa-circle"></i><span>Categorie news</span></a>
        </li>

    </ul>
</li>

<li class="{{ Request::is('*pages*') ? 'active' : '' }}">
    <a href="{!! route('admin.pages.index') !!}"><i class="fa fa-file-text-o"></i><span>Pagine</span></a>
</li>

<li class="{{ Request::is('*infos*') ? 'active' : '' }}">
    <a href="{!! route('admin.infos.index') !!}"><i class="fa fa-info"></i><span>Info</span></a>
</li>

<li class="treeview {{ (Request::is('*countries*') || Request::is('*cities*') ||  Request::is('*zones*') ) ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-globe"></i>
        <span>Località</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="{{ (Request::is('*countries*') || Request::is('*cities*') ||  Request::is('*zones*') ) ? '' : 'display: none' }}">
        <li class="{{ Request::is('*countries*') ? 'active' : '' }}">
            <a href="{!! route('admin.countries.index') !!}"><i class="fa fa-circle"></i><span>Nazioni</span></a>
        </li>
        <li class="{{ Request::is('*cities*') ? 'active' : '' }}">
            <a href="{!! route('admin.cities.index') !!}"><i class="fa fa-circle"></i><span>Città</span></a>
        </li>
        <li class="{{ Request::is('*zones*') ? 'active' : '' }}">
            <a href="{!! route('admin.zones.index') !!}"><i class="fa fa-circle"></i><span>Zone</span></a>
        </li>
    </ul>
</li>

<li class="{{ Request::is('*event*') ? 'active' : '' }}">
    <a href="{!! route('admin.events.index') !!}"><i class="fa fa-calendar"></i><span>Eventi</span></a>
</li>

<li class="treeview">
    <a href="#">
        <i class="fa fa-flag"></i>
        <span>Torneo</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="display: {{ Request::is('categor*') || Request::is('cit*') || Request::is('edition*') || Request::is('tournament*')? 'block' : 'none' }};">
        <li class="treeview">
            <a href="#">
                <i class="fa fa-tags"></i>
                <span>Definizioni</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu" style="display: none;">

                <li class="{{ Request::is('categor*') ? 'active' : '' }}">
                    <a href="{!! route('admin.categories.index') !!}"><i class="fa fa-circle-o"></i><span>Categorie</span></a>
                </li>

                <li class="{{ Request::is('cit*') ? 'active' : '' }}">
                    <a href="{!! route('admin.categoryTypes.index') !!}"><i class="fa fa-circle-o"></i><span>Tipologie</span></a>
                </li>

            </ul>
        </li>
        @foreach($menu_tournaments as $t)
        <li>
            <a href="{!! route('admin.editions.edit', ['id'=>$t->edition->id]) !!}"><i class="fa fa-circle-o"></i><span>{!! $t->edition->edition_name !!}</span></a>
        </li>
        @endforeach
        <li>
            <a href="{!! route('admin.editions.index') !!}"><i class="fa fa-flags"></i><span>Tutti</span></a>
        </li>
    </ul>
</li>

<li class="treeview {{ Request::is('*ranking*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-globe"></i>
        <span>Ranking</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="{{ Request::is('*ranking*') ? '' : 'display: none' }}">
        {{--
        <li class="{{ Request::is('*ranking*') ? 'active' : '' }}">
            <a href="{!! route('admin.rankings.index') !!}"><i class="fa fa-circle"></i><span>Mostra</span></a>
        </li>
         --}}
        <li class="{{ Request::is('*ranking*') ? 'active' : '' }}">
            <a href="{!! route('admin.rankings.assign') !!}"><i class="fa fa-circle"></i><span>Assegna punti</span></a>
        </li>
    </ul>
</li>

@elseif( $role->name === 'player' )
<li class="treeview">
    <a href="#">
        <i class="fa fa-flag"></i>
        <span>Tornei</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="display: none;">
        <li class="{{ Request::is('teams*') ? 'active' : '' }}">
            <a href="{!! route('admin.player.tournament.currents') !!}"><i class="fa fa-circle"></i><span>I miei tornei</span></a>
        </li>
    </ul>
</li>

@elseif( $role->name === 'club' )
<!--li>
    <a href="/admin/clubs"><i class="fa fa-building"></i><span>Circoli</span></a>
</li-->
@endif
