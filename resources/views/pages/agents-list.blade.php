@extends('layouts.default')
@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ $title or '' }}</h5>
                        <div class="ibox-tools">
                            <a href="/agents/create" class="btn btn-primary btn-xs">Create Agent</a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <form method="GET" action="/agents">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="text" placeholder="Search Agents" class="input form-control" name="phrase" value="{{Request::get('phrase')}}" >
    									<span class="input-group-btn">
    										<button type="submit" class="btn btn btn-primary search-user-btn"> <i class="fa fa-search"></i> Search</button>
    									</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if(count($agents)>0)
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
                                @foreach($agents as $agent)
                                    <tr>
                                        <td>{{ $agent->id }}</td>
                                        <td>{{ $agent->name }}</td>
                                        <td>{{ $agent->email }}</td>
                                        <td>{{ $agent->created_at->format('d M Y') }}</td>
                                        <td>{{ $agent->updated_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="/agents/view/{{$agent->id}}">View</a></li>
                                                    <li><a href="/agents/edit/{{$agent->id}}">Edit</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="javascript:;" onclick="deleteAgent('{{$agent->id}}')">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($agents->total() > 10)
                                    <tr>
                                        <td colspan="6" align="right">
                                            {{$agents->render()}}
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        @else
                            <div class="text-center">
                                <p>No Agent found in the system, please <a href="/agents/create">create</a> one.</p>
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
        function deleteAgent(agent_id)
        {
            if (window.confirm('Delete Agent?')) {
                $.ajax({
                    url:'/agents/delete',
                    method:'post',
                    data:{
                        _token : '{{csrf_token()}}',
                        agent_id : agent_id,
                    },
                    success: function(response){
                        window.location.href = '/agents';
                    }
                })
            }
        }
    </script>

@endsection