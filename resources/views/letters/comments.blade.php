<style>
    .bg-silver{
        border: 1px solid #e2e2e2;
        margin-top: 0;
        margin-bottom:0;
    }
    .comments-list{
        min-height: 15em;
        max-height: 15em;
        overflow-y: auto;
    }
    .toggle {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.toggle input {
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle label {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 34px;
}

.toggle label:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 50%;
}

.toggle input:checked + label {
  background-color: #2196F3;
}

.toggle input:checked + label:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}
.alert-inf{
    background-color: rgba(33, 150, 243, 0.1);
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="page-header">
                <h4><small class="pull-right">{{$document->comments()->count()}} comments</small> Comments </h4>
            </div>
            <div class="comments-list">
                @foreach ($document->comments as $comment)
                <div class="media" style="padding-bottom: 10px;">
                    <p class="pull-right"><small>{{\Carbon\Carbon::parse($comment->created_at)->diffForHumans()}}</small></p>
                    <a class="media-left" href="#">
                        <img src="https://picsum.photos/50/50.jpg" style="border-radius: 5px;">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading user_name">{{ $comment->createdBy->name }}</h4>
                        {!! $comment->notes !!}
                    </div>
                </div>
                <hr class="bg-silver">                    
                @endforeach

            </div>
            <div style="padding-top:30px;">
                {!! Form::open(['route' => ['letters.comment', 'id' => $document->id]]) !!}

                {!! Form::bsTextarea('notes', null, ['class'=>'form-control b-wysihtml5-editor']) !!}
                @if($document->assigned_to==$user->id)
                <button class="btn btn-info" type="submit" name="action" value="comment">SEND COMMENT</button>
                @else
                <button class="btn btn-info" type="submit" name="action" value="comment">DEFER TO ASSIGNEE </button>
                @endif
                @if($document->assigned_to!=$user->id)
                <button class="btn pull-right" type="submit" name="action" value="approve">APPROVE TO NEXT STAGE</button>
                @endif
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>