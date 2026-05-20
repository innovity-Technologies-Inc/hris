{{--    Edit Model --}}
<div class="modal fade" id="companyView{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                {{$item->name}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! $item->description !!}
                </div>
            </div>
        </div>
    </div>
</div>

