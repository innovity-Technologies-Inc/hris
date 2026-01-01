{{-- View Off Day Plan Details Modal --}}
<div class="modal fade" id="viewOffDayPlanModal{{ $plan->id }}" tabindex="-1"
    aria-labelledby="viewOffDayPlanModalLabel{{ $plan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h6 class="modal-title mb-0" id="viewOffDayPlanModalLabel{{ $plan->id }}">
                    <i class="mdi mdi-calendar-blank me-1"></i>{{ $plan->getPlan->name }}
                    @if ($plan->status === 'active')
                        <span class="badge bg-success ms-2">Active</span>
                    @else
                        <span class="badge bg-warning ms-2">{{ ucfirst($plan->status) }}</span>
                    @endif
                </h6>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted fw-semibold"><i
                                    class="mdi mdi-clock-outline text-primary me-1"></i>Shift</td>
                            <td class="fw-bold">{{ $plan->getPlan->getShift->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold"><i
                                    class="mdi mdi-clock-start text-success me-1"></i>Clock In</td>
                            <td>{{ $plan->getPlan->getShift ? date('h:i A', strtotime($plan->getPlan->getShift->clock_in_time ?? '00:00:00')) : 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold"><i class="mdi mdi-clock-end text-danger me-1"></i>Clock
                                Out</td>
                            <td>{{ $plan->getPlan->getShift ? date('h:i A', strtotime($plan->getPlan->getShift->clock_out_time ?? '00:00:00')) : 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold"><i
                                    class="mdi mdi-clock-alert text-warning me-1"></i>Grace Time</td>
                            <td>{{ $plan->getPlan->getShift->grace_time ?? '0' }} min</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold"><i
                                    class="mdi mdi-calendar-range text-info me-1"></i>Duration</td>
                            <td>{{ date('d M Y', strtotime($plan->from)) }} - {{ date('d M Y', strtotime($plan->to)) }}
                            </td>
                        </tr>
                        <tr class="table-success">
                            <td class="text-muted fw-semibold"><i
                                    class="mdi mdi-cash text-success me-1"></i>Remuneration</td>
                            <td class="fw-bold text-success">
                                @php
                                    $offPlan = $plan->getPlan;
                                    if ($offPlan->offday_config_type === 'Salary Based') {
                                        echo $offPlan->salary_rate_type === 'Basic Rate'
                                            ? 'Basic Rate'
                                            : number_format($offPlan->offday_multiplier, 2) . 'x';
                                    } else {
                                        echo (\App\HelperClass::getCurrency() ?? '৳') .
                                            ' ' .
                                            number_format($offPlan->custom_offday_rate ?? 0, 2) .
                                            '/hr';
                                    }
                                @endphp
                            </td>
                        </tr>
                        @if ($plan->getPlan->short_name)
                            <tr>
                                <td class="text-muted fw-semibold"><i class="mdi mdi-tag text-secondary me-1"></i>Short
                                    Name</td>
                                <td><span class="badge bg-light text-dark">{{ $plan->getPlan->short_name }}</span></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
