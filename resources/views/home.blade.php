@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(count($users))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            <!-- <button class="btn btn-success" onclick="sendfriendrequest('{{ $user->id }}');">Add Friend</button> -->

                                            <button class="btn btn-primary call" data-id="{{ $user->id }}" onclick="makeacall('{{ $user->id }}')">Make call</button>
                                            <button class="btn btn-primary" onclick="openmessagemodal('{{ $user->id }}');">Text a message</button>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2">{{ $users->links() }}</td>
                                </tr>
                            </tbody>
                        </table>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div id="msgmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Type your message here</p>
        <textarea class="form-control" id="msg" ></textarea>
        <input type="hidden" id="receiver_id" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="sendnow()">Send Now</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection
@section('after_scripts')
<script type="text/javascript">
    function makeacall(id)
    {
        $.ajax({
            type: 'get',
            url: '/makeacall',
            data: {
                "id" : id
            },
            success: function(data)
            {
                console.log(data);
            }
        });
    }
    function openmessagemodal(id){
        $("#msgmodal").modal("toggle");
        $("#receiver_id").val(id);
    }
    function sendnow(){
        var msg = $("#msg").val().trim();
        var id = $("#receiver_id").val();
        if(msg!=""){
            $.ajax({
                type: 'get',
                url: '/sendsms',
                data: {
                    "id" : id,
                    "msg" : msg,
                },
                success: function(data)
                {
                    if(data)
                    {
                        $("#msgmodal").modal("toggle");
                        alert("Message sent");
                    }
                }
            });
        }
        else{
            alert("Please type a message");
        }
    }
    function sendfriendrequest(id)
    {
        $.ajax({
            type: 'get',
            url: '/friend/request/'+id,
                data: {
                    "id" : id,
                },
                success: function(data)
                {
                    location.reload();
                }

        });
        
    }
</script>
@endsection
