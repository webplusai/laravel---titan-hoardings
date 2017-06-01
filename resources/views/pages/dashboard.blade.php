@extends('layouts.default')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="text-center m-t-lg">
			<h1>
				Dashboard
			</h1>
			<small>
				Latest notifications.
			</small>
		</div>
	</div>
</div>

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12 m-b-lg">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Job Notification</h5>
                    <div class="ibox-tools">
                        <span class="label label-warning-light pull-right">{{ $job_notifications->total() }} Items</span>
                       </div>
                </div>
                <div class="ibox-content">
                    <div>
                        <div id="notification-feeds-container" class="feed-activity-list">
                            @if (count($job_notifications))
                                @foreach($job_notifications as $notification)
                                    <div class="feed-element">
                                        <div class="media-body ">
                                           <small class="pull-right text-navy">{{$notification->created_at->diffForHumans()}}</small>
                                            <strong>{{ $notification->user->first_name }} {{ $notification->user->last_name }} </strong> {{ $notification->title }}.<br>
                                            <small class="text-muted">{{ $notification->created_at->setTimezone(Auth::user()->timezone)->format('h:i a - d.m.Y') }}</small>
                                            @if (strlen($notification->message))
                                                <div class="well">
                                                    {{ $notification->message }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-center">
                                {{ $job_notifications->links() }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
