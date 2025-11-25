<style>
    .skeleton-loader {
        padding: 20px;
    }

    .skeleton-line {
        height: 14px;
        background: linear-gradient(90deg, #e6e6e6 0%, #f2f2f2 50%, #e6e6e6 100%);
        border-radius: 8px;
        margin-bottom: 12px;
        animation: skeleton-loading 1.2s infinite ease-in-out;
    }

    @keyframes skeleton-loading {
        0% {
            background-position: -100px 0;
        }
        100% {
            background-position: 200px 0;
        }
    }

</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body pt-0">
                {{-- Tab Navigation --}}
                <ul class="nav nav-underline border-bottom pt-2  mb-3" id="plan-tabs" role="tablist">

                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($type == 'meal-plans') active @endif  p-2 ajax-tab"
                           data-url="{{route('employees.profile.plans', ['id' => $employee->id, 'type' => 'meal-plans'])}}">
                            <span class="d-none d-sm-block">Meal Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($type == 'shift-plans') active @endif p-2 ajax-tab"
                           data-url="{{route('employees.profile.plans', ['id' => $employee->id, 'type' => 'shift-plans'])}}">
                            <span class="d-none d-sm-block">Shift Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($type == 'roster-plans') active @endif p-2 ajax-tab"
                           data-url="{{route('employees.profile.plans', ['id' => $employee->id, 'type' => 'roster-plans'])}}">
                            <span class="d-none d-sm-block">Roster Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($type == 'ot-plans') active @endif p-2 ajax-tab"
                           data-url="{{route('employees.profile.plans', ['id' => $employee->id, 'type' => 'ot-plans'])}}">
                            <span class="d-none d-sm-block">OT Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($type == 'offday-plans') active @endif p-2 ajax-tab"
                           data-url="{{route('employees.profile.plans', ['id' => $employee->id, 'type' => 'offday-plans'])}}">
                            <span class="d-none d-sm-block">Off Day Work Plan</span>
                        </a>
                    </li>


                </ul>


{{--                Tab Skeleton--}}
                <div id="tabSkeleton" class="skeleton-loader" style="display:none;">
                    <div class="skeleton-line" style="width: 80%"></div>
                    <div class="skeleton-line" style="width: 60%"></div>
                    <div class="skeleton-line" style="width: 90%"></div>
                    <div class="skeleton-line" style="width: 70%"></div>
                    <div class="skeleton-line" style="width: 50%"></div>
                </div>


                {{-- Tab Content --}}
                <div id="tabContentWrapper" class="tab-content text-muted">
                    @if($type == 'meal-plans')
                        @include('employees.partials.profile_view.partials.meal_plan')

                    @elseif($type == 'shift-plans')
                        @include('employees.partials.profile_view.partials.shift_plan')

                    @elseif($type == 'roster-plans')
                        @include('employees.partials.profile_view.partials.roster_plan')

                    @elseif($type == 'ot-plans')
                        @include('employees.partials.profile_view.partials.ot_plan')

                    @elseif($type == 'offday-plans')
                        @include('employees.partials.profile_view.partials.offday_plan')

                    @endif


                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const tabs = document.querySelectorAll(".ajax-tab");
        const wrapper = document.getElementById("tabContentWrapper");
        const skeleton = document.getElementById("tabSkeleton");

        tabs.forEach(tab => {
            tab.addEventListener("click", async function (e) {
                e.preventDefault();

                // remove active class
                tabs.forEach(t => t.classList.remove("active"));
                this.classList.add("active");

                const url = this.dataset.url;

                // Show skeleton, clear wrapper
                skeleton.style.display = "block";
                wrapper.style.opacity = "0.3";
                wrapper.innerHTML = "";  // REMOVE OLD DATA


                try {
                    const response = await fetch(url, {
                        headers: { "X-Requested-With": "XMLHttpRequest" }
                    });

                    const html = await response.text();

                    // Hide skeleton, show content
                    skeleton.style.display = "none";
                    wrapper.style.opacity = "1";
                    wrapper.innerHTML = html;

                    // Update URL
                    window.history.pushState({}, "", url);

                } catch (err) {
                    console.error("Error loading tab:", err);
                    skeleton.style.display = "none";
                    wrapper.innerHTML = "<p class='text-danger'>Failed to load content.</p>";
                }
            });
        });
    });
</script>


