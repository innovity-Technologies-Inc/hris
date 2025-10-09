{{--    Edit Model --}}
<div class="modal fade" id="companyView{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <img src="{{asset('storage/'.$item->logo)}}" class="avatar avatar-sm rounded-circle me-3">
                {{$item->name}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table>
                    <tbody>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Short Name
                        </td>
                        <td>:</td>

                        <td>
                            {{$item->short_name}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Group
                        </td>
                        <td>:</td>

                        <td>
                            {{$item->getGroup->name}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Type
                        </td>
                        <td>:</td>

                        <td>
                            {{$item->getCompanyType->name}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Address
                        </td>
                        <td>:</td>

                        <td>
                            {{$item->address}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Email
                        </td>
                        <td>:</td>

                        <td>
                            {{$item->email}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Telephone
                        </td>
                        <td>:</td>

                        <td>
                            {{$item->telephone}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Fax
                        </td>
                        <td>:</td>
                        <td>
                            {{$item->fax}}
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
