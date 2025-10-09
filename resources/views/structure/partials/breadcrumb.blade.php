<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">{{isset($title) ? $title : ''}}</h4>
    </div>

    @if( isset($section) && isset($sub_section))
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{isset($section_url) ? $section_url: ''}}">{{$section}}</a></li>
                <li class="breadcrumb-item active">{{$sub_section}}</li>
            </ol>
        </div>
    @endif

</div>
