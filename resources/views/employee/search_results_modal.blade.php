{{-- Employee Search Results Modal --}}
<div class="modal fade" id="resultsModal" tabindex="-1" aria-labelledby="resultsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="resultsModalLabel">
                    <i class="mdi mdi-account-multiple"></i> <span id="resultsModalTitle">Employee Search Results</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle" id="resultsModalTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>System ID</th>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Type</th>
                                <th id="headerCompany">Company</th>
                                <th id="headerBranch">Branch</th>
                                <th id="headerDivision">Division</th>
                                <th id="headerDepartment">Department</th>
                                <th id="headerSection">Section</th>
                                <th style="width: 200px;">Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Dynamic content will be inserted via JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i> Close
                </button>
                <button type="button" class="btn btn-success" id="btnExportExcelModal">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                </button>
                <button type="button" class="btn btn-primary" onclick="printResults()">
                    <i class="mdi mdi-printer"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

