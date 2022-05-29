<!-- Button trigger modal -->
<button type="button" class="btn btn-{{$modal["color"]}}" data-toggle="modal" data-target="#{{ $modal["id"] }}">
    {{ $modal["action"] }} @if ($modal["show_title"]==True)
    {{ $data["title"] }}
    @endif
</button>

<!-- Modal -->
<div class="modal fade" id="{{ $modal["id"] }}" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">{{ $data["title"] }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $modal["url"] }}" method="post" enctype="multipart/form-data">

                <div class="modal-body">
                    @include($modal["content"])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-{{$modal["color"]}}">{{ $modal["action"] }}</button>
                </div>
            </form>
        </div>
    </div>
</div>