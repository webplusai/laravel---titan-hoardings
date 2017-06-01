@extends('layouts.default')
@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ $title or '' }}</h5>
                        <div class="ibox-tools">
                            <a href="/clients/create" class="btn btn-primary btn-xs">Create Client</a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <form method="GET" action="/clients">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="text" placeholder="Search Clients" class="input form-control" name="global_search" value="{{Request::get('global_search')}}" >
    									<span class="input-group-btn">
    										<button type="submit" class="btn btn btn-primary search-user-btn"> <i class="fa fa-search"></i> Search</button>
    									</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if (count($clients))
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Trading Name</th>
                                    <th>Email</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody class="table_body">
                                @foreach($clients as $client)
                                    <tr>
                                        <td>{{ $client->id }}</td>
                                        <td>{{ $client->name }}</td>
                                        <td>{{ $client->email }}</td>
                                        <td>{{ $client->created_at->format('d M Y') }}</td>
                                        <td>{{ $client->updated_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="/clients/view/{{ $client->id }}">View</a></li>
                                                    <li><a href="/clients/edit/{{ $client->id }}">Edit</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="javascript:;" onclick="deleteClient('{{ $client->id }}')">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($clients->total() > 10)
                                    <tr>
                                        <td colspan="6" align="right">
                                            {{$clients->render()}}
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        @else
                            <div class="text-center">
                                <p>No Client found in the system, please <a href="/clients/create">create</a> one.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    function deleteClient(client_id)
    {
        if (window.confirm('Delete Client?')) {
            $.ajax({
                url:'/clients/delete',
                method: 'post',
                data:{
                    _token : '{{ csrf_token() }}',
                    client_id : client_id
                },
                success: function (response) {
                    window.location.href = '/clients';

                },
            });
        }
    }
</script>
@endsection
