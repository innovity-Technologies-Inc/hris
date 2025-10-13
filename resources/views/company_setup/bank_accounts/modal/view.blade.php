{{--    Edit Model --}}
<div class="modal fade" id="bankAccountsView{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                Bank Account Information
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table>
                    <tbody>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Account No
                        </td>
                        <td>:</td>

                        <td>
                            {{$item->account_no}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Account Holder Name
                        </td>
                        <td>:</td>

                        <td>
                            {{$item->holder_name}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Account Type
                        </td>
                        <td>:</td>

                        <td>
                            {{ucwords($item->account_type)}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Bank
                        </td>
                        <td>:</td>

                        <td>
                            {{$item->getBank->name}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Branch
                        </td>
                        <td>:</td>

                        <td>
                            {{$item->getBranch->name}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Routing No
                        </td>
                        <td>:</td>

                        <td>
                            {{$item->getBranch->routing_no}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Contact Person
                        </td>
                        <td>:</td>
                        <td>
                            {{$item->contact_person}}
                        </td>
                    </tr>
                    <tr class="mb-2">
                        <td class="fw-bold">
                            Contact Person No
                        </td>
                        <td>:</td>
                        <td>
                            {{$item->contact_person_no}}
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
                            Status
                        </td>
                        <td>:</td>
                        <td>
                            @if($item->status == 'active')
                                <span class="badge text-bg-success">Active</span>
                            @else
                                <span class="badge text-bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
