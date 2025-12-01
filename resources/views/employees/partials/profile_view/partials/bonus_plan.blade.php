<style>
    .skeleton {
        background: linear-gradient(90deg, #ececec 25%, #f5f5f5 37%, #ececec 63%);
        animation: shimmer 1.4s infinite;
        background-size: 400% 100%;
        border-radius: 4px;
    }

    @keyframes shimmer {
        0% {
            background-position: -400px 0;
        }
        100% {
            background-position: 400px 0;
        }
    }
</style>

<div class="container-fluid px-4 py-5">
    <div class="row g-4">

        {{-- ====================== --}}
        {{-- LEFT: BONUS PLAN LIST --}}
        {{-- ====================== --}}
        <div class="col-lg-4 col-md-5">
            <div class="card shadow-sm">

                <div class="card-header bg-light">
                    <h5 class="mb-3 fw-semibold">💰 Bonus Plan List</h5>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">Select all</label>
                    </div>
                </div>

                {{-- FORM - Normal Submit --}}
                <form id="" method="POST" action="{{route('employees.profile.plans.store', 'bonus-plans')}}">
                    @csrf

                    <div class="card-body p-3" style="max-height: 550px; overflow-y: auto;">
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                        @foreach($bonusPlans as $item)
                            <div class="d-flex align-items-start mb-2 plan-item"
                                 data-id="{{ $item->id }}" style="cursor:pointer;">

                                <input type="checkbox"
                                       class="form-check-input plan-checkbox me-2"
                                       name="plan_ids[]"
                                       value="{{ $item->id }}"
                                       id="plan-{{ $item->id }}" @if(isset($activeBonusPlans) && $activeBonusPlans->contains('plan_id', $item->id)) checked @endif >

                                <label for="plan-{{ $item->id }}" class="form-check-label">
                                    {{ $item->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-light">
                        <button class="btn btn-primary w-100 py-2" type="submit">
                            <i class="bi bi-check-circle me-2"></i>Submit Selected
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- ====================== --}}
        {{-- RIGHT: DETAILS PANEL --}}
        {{-- ====================== --}}
        <div class="col-lg-8 col-md-7">
            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>Details
                    </h5>
                </div>

                <div class="card-body" style="min-height: 400px;">

                    {{-- ====================== --}}
                    {{-- EMPTY STATE --}}
                    {{-- ====================== --}}
                    <div id="emptyState"
                         class="d-flex flex-column align-items-center justify-content-center"
                         style="height: 350px;">
                        <i class="bi bi-file-earmark-text text-muted"
                           style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">Select a bonus plan to view details</p>
                    </div>

                    {{-- ====================== --}}
                    {{-- SKELETON LOADER --}}
                    {{-- ====================== --}}
                    <div id="skeletonLoader" class="d-none">
                        <div class="skeleton mb-3" style="height: 30px; width: 50%;"></div>
                        <div class="skeleton mb-2" style="height: 20px; width: 30%;"></div>
                        <div class="skeleton mb-4" style="height: 20px; width: 20%;"></div>

                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="skeleton" style="height: 70px;"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="skeleton" style="height: 70px;"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="skeleton" style="height: 70px;"></div>
                            </div>
                        </div>

                        <div class="row mt-3 g-2">
                            <div class="col-md-4">
                                <div class="skeleton" style="height: 70px;"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="skeleton" style="height: 70px;"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="skeleton" style="height: 70px;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- ====================== --}}
                    {{-- REAL DETAILS CONTENT --}}
                    {{-- ====================== --}}
                    <div id="detailsContent" class="d-none">

                        <div class="pb-3 border-bottom border-primary border-2 mb-3">
                            <h4 class="fw-bold mb-1" id="planName"></h4>
                            <div class="text-muted">
                                <span class="me-3">
                                    <i class="bi bi-tag me-1"></i>
                                    <span id="planType"></span>
                                </span>
                                <span id="planStatusBadge"></span>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div id="descriptionSection"
                             class="mb-3 p-3 bg-light rounded border d-none">
                            <h6 class="text-uppercase fw-bold mb-3"
                                style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                <i class="bi bi-file-text me-2 text-primary"></i>Description
                            </h6>
                            <div class="fw-semibold" id="planDescription"></div>
                        </div>

                        {{-- Bonus Information --}}
                        <div class="mb-3 p-3 bg-light rounded border">
                            <h6 class="text-uppercase fw-bold mb-3"
                                style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                <i class="bi bi-info-square me-2 text-primary"></i>Bonus Information
                            </h6>

                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1"
                                           style="font-size: 0.688rem;">Bonus Type</label>
                                    <div class="fw-semibold" id="bonusType"></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1"
                                           style="font-size: 0.688rem;">Config Type</label>
                                    <div class="fw-semibold" id="configType"></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1"
                                           style="font-size: 0.688rem;">Status</label>
                                    <div id="statusBadge"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Config Details --}}
                        <div class="mb-3 p-3 bg-light rounded border">
                            <h6 class="text-uppercase fw-bold mb-3"
                                style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                <i class="bi bi-gear me-2 text-primary"></i>Configuration Details
                            </h6>

                            <div class="row g-2" id="configDetails"></div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {

        /* -----------------------------
           SELECT ALL CHECKBOX
        ------------------------------*/
        $("#selectAll").on("change", function () {
            let checked = $(this).prop("checked");
            $(".plan-checkbox").prop("checked", checked);

            if (checked) {
                let firstId = $(".plan-checkbox").first().val();
                loadPlanDetails(firstId);
            }
        });

        /* -----------------------------
           SINGLE CHECKBOX CHANGE
        ------------------------------*/
        $(document).on("change", ".plan-checkbox", function () {
            let id = $(this).val();

            // Load details when checkbox clicked
            loadPlanDetails(id);

            // Update select all checkbox
            let allChecked = $(".plan-checkbox:checked").length === $(".plan-checkbox").length;
            $("#selectAll").prop("checked", allChecked);
        });

        /* -----------------------------
           CLICK ON LIST ITEM
        ------------------------------*/
        $(".plan-item").on("click", function (e) {
            let id = $(this).data("id");

            // If checkbox itself clicked → already handled
            if ($(e.target).hasClass("plan-checkbox")) {
                return;
            }

            $("#plan-" + id).prop("checked", true).trigger("change");
        });

        /* -----------------------------
           LOAD BONUS PLAN DETAILS
        ------------------------------*/
        function loadPlanDetails(id) {

            $("#emptyState").addClass("d-none");
            $("#detailsContent").addClass("d-none");
            $("#skeletonLoader").removeClass("d-none");

            $.ajax({
                url: `/get-bonus-plan-details/${id}`,
                type: "GET",

                success: function (res) {

                    /* -----------------------------
                       BASIC DETAILS
                    ------------------------------*/
                    $("#planName").text(res.name);
                    $("#planType").text(res.bonus_type);

                    $("#planStatusBadge").html(
                        `<span class="badge bg-${res.status === 'active' ? 'success' : 'secondary'}">
                        ${res.status}
                    </span>`
                    );

                    if (res.description) {
                        $("#descriptionSection").removeClass("d-none");
                        $("#planDescription").html(res.description);
                    } else {
                        $("#descriptionSection").addClass("d-none");
                    }

                    $("#bonusType").text(res.bonus_type);
                    $("#configType").text(res.bonus_config_type);

                    $("#statusBadge").html(
                        `<span class="badge bg-${res.status === 'active' ? 'success' : 'danger'}">
                        ${res.status}
                    </span>`
                    );

                    /* -----------------------------
                       CONFIG DETAILS (Dynamic Logic)
                    ------------------------------*/
                    let configHtml = "";

                    // RULE 1: CONFIG TYPE = CUSTOM
                    if (res.bonus_config_type === "Custom") {

                        configHtml = `
                        <div class="col-md-4">
                            <label class="text-secondary text-uppercase fw-semibold mb-1">
                                Custom Overtime Rate
                            </label>
                            <div class="fw-semibold">${res.custom_overtime_rate ?? '-'}</div>
                        </div>
                    `;

                    }

                    // RULE 2: CONFIG TYPE = SALARY BASED
                    else if (res.bonus_config_type === "Salary Based") {

                        // Always show salary rate type
                        configHtml += `
                        <div class="col-md-4">
                            <label class="text-secondary text-uppercase fw-semibold mb-1">
                                Salary Rate Type
                            </label>
                            <div class="fw-semibold">${res.salary_rate_type ?? '-'}</div>
                        </div>
                    `;

                        // Salary Based + Basic Rate → hide multiplier
                        if (res.salary_rate_type === "Basic Rate") {
                            // nothing more to add
                        }

                        // Salary Based + Multiplier → show overtime multiplier
                        else if (res.salary_rate_type === "Multiplier") {

                            configHtml += `
                            <div class="col-md-4">
                                <label class="text-secondary text-uppercase fw-semibold mb-1">
                                    Overtime Multiplier
                                </label>
                                <div class="fw-semibold">${res.overtime_multiplier ?? '-'}</div>
                            </div>
                        `;
                        }
                    }

                    // Inject config HTML
                    $("#configDetails").html(configHtml);

                    /* -----------------------------
                       FINISH LOADING
                    ------------------------------*/
                    $("#skeletonLoader").addClass("d-none");
                    $("#detailsContent").removeClass("d-none");
                },

                error: function () {
                    $("#planName").text("Error loading details.");
                    $("#skeletonLoader").addClass("d-none");
                    $("#detailsContent").removeClass("d-none");
                }
            });
        }

    });
</script>

