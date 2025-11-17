@extends('layouts.app')

@section('title', 'Business Settings')

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        background: #f5f7fa;
        padding: 0;
    }

    .container {
        max-width: 100%;
        margin: 0;
    }

    .page-header {
        margin-bottom: 32px;
    }

    .page-header h2 {
        font-size: 1.8rem;
        color: black;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .page-header p {
        color: #7f8c8d;
        font-size: 1rem;
    }

    .tabs {
        background: white;
        border-radius: 8px 8px 0 0;
        padding: 0;
        display: flex;
        overflow-x: auto;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .tab-button {
        flex: 1;
        min-width: 140px;
        padding: 16px 20px;
        border: none;
        background: white;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        color: #7f8c8d;
        transition: all 0.2s ease;
        border-bottom: 2px solid transparent;
        position: relative;
    }

    .tab-button:hover {
        background: #f8f9fa;
    }

    .tab-button.active {
        color: black;
        border-bottom-color: #3498db;
        background: #f8f9fa;
        font-weight: 600;
    }

    .tab-button:first-child {
        border-radius: 8px 0 0 0;
    }

    .tab-button:last-child {
        border-radius: 0 8px 0 0;
    }

    .tab-content {
        display: none;
        background: white;
        padding: 32px;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .section-header {
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #ecf0f1;
    }

    .section-header h3 {
        font-size: 1.2rem;
        margin-bottom: 4px;
        font-weight: 600;
    }

    .section-header p {
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 3px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .required::after {
        content: '*';
        color: #e74c3c;
        margin-left: 2px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 11px 14px;
        border: 1px solid #dce1e6;
        border-radius: 4px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        font-family: inherit;
        background: white;
    }

    .form-group select  {
      width:100%;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .form-group input.error,
    .form-group select.error,
    .form-group textarea.error {
        border-color: #e74c3c;
    }

    .form-group input:read-only {
        background: #f8f9fa;
        color: #7f8c8d;
    }

    .form-group small {
        color: #7f8c8d;
        font-size: 0.85rem;
        margin-top: 4px;
    }

    .error-message {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 4px;
        display: none;
    }

    .error-message.show {
        display: block;
    }

    .toggle-switch {
        display: flex;
        align-items: center;
        gap: 12px;
    }

   .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
        background-color: whitesmoke;
        border-radius: 26px;
        transition: .3s;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        transition: .3s;
        border-radius: 26px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #27ae60;
    }

    input:checked + .slider:before{
        transform: translateX(24px);
        background-color: #27ae60;
    }

    .multiselect {
        position: relative;
    }

    .multiselect-display {
        padding: 11px 14px;
        border: 1px solid #dce1e6;
        border-radius: 4px;
        cursor: pointer;
        background: white;
        transition: all 0.2s ease;
        min-height: 44px;
        display: flex;
        align-items: center;
    }

    .multiselect-display:hover {
        border-color: #bdc3c7;
    }

    .multiselect-display.active {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .multiselect-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dce1e6;
        border-radius: 4px;
        margin-top: 4px;
        display: none;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .multiselect-dropdown.show {
        display: block;
    }

    .multiselect-option {
        padding: 12px 14px;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .multiselect-option:hover {
        background: #f8f9fa;
    }

    .multiselect-option input[type="checkbox"] {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .save-button {
        background: #3B82F6;
        color: white;
        border: none;
        padding: 9px;
        font-size: 0.95rem;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: block;
        margin: 32px auto 0;
    }

    .save-button:hover {
        background-color: var(--primary-color); 
        color: white; 
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .save-button:active {
        transform: translateY(1px);
    }

    .save-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        display: none;
        animation: slideIn 0.3s ease;
        max-width: 400px;
        font-size: 0.9rem;
    }

    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    .notification.show {
        display: block;
    }

    .notification.success {
        background: #27ae60;
        color: white;
    }

    .notification.error {
        background: #e74c3c;
        color: white;
    }

    .notification.info {
        background: #3498db;
        color: white;
    }

    .char-counter {
        font-size: 0.8rem;
        color: #95a5a6;
        text-align: right;
        margin-top: 4px;
    }

    .info-icon {
        display: inline-block;
        width: 16px;
        height: 16px;
        background: #bdc3c7;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 16px;
        font-size: 11px;
        cursor: help;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        body {
            padding: 20px 10px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .tabs {
            flex-direction: column;
        }

        .tab-button {
            border-bottom: none;
            border-left: 2px solid transparent;
        }

        .tab-button.active {
            border-bottom: none;
            border-left-color: #3498db;
        }

        .tab-content {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')

    <div class="tabs">
        <button class="tab-button active" data-tab="basic">Basic Profile</button>
        <button class="tab-button" data-tab="tax">Tax Settings</button>
        <button class="tab-button" data-tab="address">Address & IDs</button>
        <button class="tab-button" data-tab="locale">Preferences</button>
        <button class="tab-button" data-tab="regulatory">Regulatory</button>
        <button class="tab-button" data-tab="documents">Documents</button>
        <button class="tab-button" data-tab="misc">Miscellaneous</button>
    </div>

    <div class="tab-content active" data-tab="basic">
        <div class="section-header">
            <h3>Basic Business Profile</h3>
            <p>Essential information about your business</p>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="required">Business Legal Name</label>
                <input type="text" id="businessLegalName" maxlength="255" placeholder="Staffify BPO & Digital Agency Inc.">
                <small>Official registered business name</small>
                <div class="error-message">This field is required</div>
            </div>

            <div class="form-group">
                <label>Trade Name / Brand</label>
                <input type="text" id="tradeName" placeholder="Staffify">
                <small>Your brand or DBA name (optional)</small>
            </div>

            <div class="form-group">
                <label class="required">Business Registration Type</label>
                <select id="registrationType">
                    <option value="">Select registration type...</option>
                    <option value="DTI - Sole Prop">DTI - Sole Proprietorship</option>
                    <option value="SEC - Partnership">SEC - Partnership</option>
                    <option value="SEC - Corporation">SEC - Corporation</option>
                    <option value="SEC - OPC">SEC - One Person Corporation</option>
                    <option value="CDA - Co-op">CDA - Cooperative</option>
                </select>
                <small>Determines compliance requirements</small>
                <div class="error-message">Please select a registration type</div>
            </div>

            <div class="form-group">
                <label>Registration Number</label>
                <input type="text" id="registrationNo" maxlength="20" placeholder="2025-12345678">
                <small>BN / SEC / CDA number</small>
            </div>

            <div class="form-group">
                <label>Date of Registration</label>
                <input type="date" id="dateOfRegistration">
                <small>Official registration date</small>
            </div>

            <div class="form-group">
                <label>Industry Code <span class="info-icon" title="NAICS/PSIC classification code">i</span></label>
                <input type="text" id="industryCode" placeholder="62090 – Other IT Service Activities">
                <small>NAICS / PSIC classification</small>
            </div>
        </div>

        <div class="form-group">
            <label>Business Description</label>
            <textarea id="businessDescription" rows="4" maxlength="500" placeholder="Describe your business activities and services..."></textarea>
            <div class="char-counter"><span id="descCounter">0</span> / 500</div>
        </div>
    </div>

    <div class="tab-content" data-tab="tax">
        <div class="section-header">
            <h3>Tax Configuration</h3>
            <p>Configure tax types and accounting methods</p>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="required">Tax Type</label>
                <select id="taxType">
                    <option value="Non-VAT (3%)">Non-VAT (3%)</option>
                    <option value="VAT (12%)">VAT (12%)</option>
                    <option value="Zero-Rated">Zero-Rated</option>
                    <option value="Tax-Exempt">Tax-Exempt</option>
                </select>
                <small>Primary tax classification</small>
            </div>

            <div class="form-group">
                <label class="required">Books of Accounts</label>
                <div class="multiselect">
                    <div class="multiselect-display" id="booksDisplay">Select books of accounts...</div>
                    <div class="multiselect-dropdown" id="booksDropdown">
                        <div class="multiselect-option">
                            <input type="checkbox" value="Non-computerized" id="book1">
                            <label for="book1">Non-computerized</label>
                        </div>
                        <div class="multiselect-option">
                            <input type="checkbox" value="Loose-leaf" id="book2">
                            <label for="book2">Loose-leaf</label>
                        </div>
                        <div class="multiselect-option">
                            <input type="checkbox" value="CAS" id="book3">
                            <label for="book3">Computerized Accounting System (CAS)</label>
                        </div>
                    </div>
                </div>
                <small>Select all that apply</small>
            </div>

            <div class="form-group">
                <label>Accounting Method</label>
                <select id="accountingMethod">
                    <option value="">Select method...</option>
                    <option value="Accrual">Accrual Basis</option>
                    <option value="Cash">Cash Basis</option>
                </select>
                <small>Revenue recognition method</small>
            </div>

            <div class="form-group">
                <label>Fiscal Year Start</label>
                <select id="fiscalStart">
                    <option value="">Select month...</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
                <small>Beginning of fiscal year</small>
            </div>

            <div class="form-group">
                <label>Quarter Period Cut-off</label>
                <select id="quarterCutoff">
                    <option value="">Select...</option>
                    <option value="Calendar Quarter">Calendar Quarter</option>
                    <option value="13-Week">13-Week</option>
                    <option value="Custom">Custom</option>
                </select>
                <small>For quarterly reporting</small>
            </div>

            <div class="form-group">
                <label>Withholding Agent?</label>
                <div class="toggle-switch">
                    <label class="switch">
                        <input type="checkbox" id="withholdingToggle">
                        <span class="slider"></span>
                    </label>
                    <span>Enable withholding tax features</span>
                </div>
                <small>Required for 0619E/F & 1601 forms</small>
            </div>
        </div>
    </div>

    <div class="tab-content" data-tab="address">
        <div class="section-header">
            <h3>Address & Tax IDs</h3>
            <p>Business location and tax identification numbers</p>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Business TIN</label>
                <input type="text" id="businessTin" placeholder="123-456-789-000" maxlength="15">
                <small>Tax Identification Number</small>
            </div>

            <div class="form-group">
                <label>RDO Code</label>
                <input type="text" id="rdoCode" placeholder="055" maxlength="3">
                <small>Revenue District Office</small>
            </div>

            <div class="form-group">
                <label>ZIP Code</label>
                <input type="text" id="zipCode" placeholder="2200" maxlength="4">
                <small>4-digit postal code</small>
            </div>

            <div class="form-group">
                <label>Contact Phone</label>
                <input type="tel" id="contactPhone" placeholder="+63 917 123 4567">
                <small>International format preferred</small>
            </div>

            <div class="form-group">
                <label>Official Email</label>
                <input type="email" id="officialEmail" placeholder="finance@staffify.com">
                <small>Primary business email</small>
            </div>
        </div>

        <div class="form-group">
            <label>Official Address</label>
            <textarea id="officialAddress" rows="3" maxlength="300" placeholder="Blk 1 Lot 10, SBMA, Olongapo City, 2200"></textarea>
            <div class="char-counter"><span id="addressCounter">0</span> / 300</div>
        </div>
    </div>

    <div class="tab-content" data-tab="locale">
        <div class="section-header">
            <h3>Locale & Preferences</h3>
            <p>Regional and formatting preferences</p>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Base Currency</label>
                <select id="currency">
                    <option value="">Select currency...</option>
                    <option value="PHP">PHP - Philippine Peso</option>
                    <option value="USD">USD - US Dollar</option>
                    <option value="EUR">EUR - Euro</option>
                </select>
                <small>Primary operating currency</small>
            </div>

            <div class="form-group">
                <label>Time Zone</label>
                <select id="timezone">
                    <option value="">Select timezone...</option>
                    <option value="Asia/Manila">Asia/Manila (UTC+8)</option>
                    <option value="America/New_York">America/New_York (UTC-5)</option>
                    <option value="Europe/London">Europe/London (UTC+0)</option>
                </select>
                <small>For date/time formatting</small>
            </div>

            <div class="form-group">
                <label>Week Start Day</label>
                <select id="weekStart">
                    <option value="Sunday">Sunday</option>
                    <option value="Monday">Monday</option>
                </select>
                <small>First day of the week</small>
            </div>

            <div class="form-group">
                <label>Date Format</label>
                <select id="dateFormat">
                    <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                    <option value="MM-DD-YYYY">MM-DD-YYYY</option>
                    <option value="DD-MM-YYYY">DD-MM-YYYY</option>
                </select>
                <small>Display format for dates</small>
            </div>

            <div class="form-group">
                <label>Number Format</label>
                <select id="numberFormat">
                    <option value="1,234.56">1,234.56 (US)</option>
                    <option value="1 234,56">1 234,56 (EU)</option>
                </select>
                <small>Decimal separator style</small>
            </div>

            <div class="form-group">
                <label>Brand Color</label>
                <input type="color" id="brandColor" value="#2c3e50">
                <small>Used in reports and PDFs</small>
            </div>
        </div>
    </div>

    <div class="tab-content" data-tab="regulatory">
        <div class="section-header">
            <h3>Regulatory & Compliance</h3>
            <p>Government agency registration numbers</p>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>SSS Number</label>
                <input type="text" id="sss" placeholder="34-5678901-2">
                <small>Social Security System</small>
            </div>

            <div class="form-group">
                <label>PhilHealth Number</label>
                <input type="text" id="phic" placeholder="12-345678901-2">
                <small>Philippine Health Insurance</small>
            </div>

            <div class="form-group">
                <label>Pag-IBIG Number</label>
                <input type="text" id="hdmf" placeholder="1234-5678-9012">
                <small>Home Development Mutual Fund</small>
            </div>

            <div class="form-group">
                <label>PEZA / BOI Certificate</label>
                <input type="text" id="peza" placeholder="Enter if applicable">
                <small>Special economic zone registration</small>
            </div>
        </div>

        <div class="form-group">
            <label>Other Permits & Licenses</label>
            <textarea id="permits" rows="3" placeholder="FDA, DOLE, TESDA permits..."></textarea>
            <small>List all relevant permit numbers</small>
        </div>
    </div>

    <div class="tab-content" data-tab="documents">
        <div class="section-header">
            <h3>Document Defaults</h3>
            <p>Configure document numbering and templates</p>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Official Receipt Prefix</label>
                <input type="text" id="orPrefix" placeholder="OR-" maxlength="10">
                <small>Up to 10 characters</small>
            </div>

            <div class="form-group">
                <label>Sales Invoice Prefix</label>
                <input type="text" id="siPrefix" placeholder="SI-" maxlength="10">
                <small>Up to 10 characters</small>
            </div>

            <div class="form-group">
                <label>Next OR Number</label>
                <input type="number" id="nextOr" placeholder="1001">
                <small>Auto-increment starting number</small>
            </div>

            <div class="form-group">
                <label>Next SI Number</label>
                <input type="number" id="nextSi" placeholder="5001">
                <small>Auto-increment starting number</small>
            </div>

            <div class="form-group">
                <label>PDF Template</label>
                <select id="pdfTemplate">
                    <option value="">Select template...</option>
                    <option value="Standard">Standard</option>
                    <option value="Minimal">Minimal</option>
                    <option value="Custom">Custom</option>
                </select>
                <small>Document output format</small>
            </div>
        </div>
    </div>

    <div class="tab-content" data-tab="misc">
        <div class="section-header">
            <h3>Advanced Settings</h3>
            <p>System configuration and features</p>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Multi-Branch Operations</label>
                <div class="toggle-switch">
                    <label class="switch">
                        <input type="checkbox" id="enableMultiBranch">
                        <span class="slider"></span>
                    </label>
                    <span>Enable branch tracking</span>
                </div>
                <small>Unlocks branch dimension in reports</small>
            </div>

            <div class="form-group">
                <label>Inventory Tracking Mode</label>
                <select id="inventoryTracking">
                    <option value="">Select mode...</option>
                    <option value="Perpetual">Perpetual</option>
                    <option value="Periodic">Periodic</option>
                </select>
                <small>Impacts COGS calculation</small>
            </div>

            <div class="form-group">
                <label>Weighted Average Cost</label>
                <div class="toggle-switch">
                    <label class="switch">
                        <input type="checkbox" id="useWeightedCost">
                        <span class="slider"></span>
                    </label>
                    <span>Use weighted avg for inventory</span>
                </div>
                <small>Inventory valuation method</small>
            </div>

            <div class="form-group">
                <label>Audit Trail</label>
                <div class="toggle-switch">
                    <label class="switch">
                        <input type="checkbox" id="enableAuditTrail">
                        <span class="slider"></span>
                    </label>
                    <span>Log all system actions</span>
                </div>
                <small>Recommended for compliance</small>
            </div>

            <div class="form-group">
                <label>Last Updated</label>
                <input type="text" id="lastUpdated" value="Never" readonly>
                <small>Automatically updated on save</small>
            </div>
        </div>
    </div>

    <button class="save-button" id="saveSettingsBtn">Save All Settings</button>
</div>

<div class="notification" id="notification"></div>
@endsection


{{-- 
============================================
 SCRIPTS
 - Moved from '@section('content')' to '@push('scripts')'
 - This ensures they are loaded at the bottom of the <body>.
============================================
--}}
@push('scripts')
<script>
    // Tab switching
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabName = button.dataset.tab;
            
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            button.classList.add('active');
            document.querySelector(`[data-tab="${tabName}"].tab-content`).classList.add('active');
        });
    });

    // Character counters
    const descTextarea = document.getElementById('businessDescription');
    const descCounter = document.getElementById('descCounter');
    const addressTextarea = document.getElementById('officialAddress');
    const addressCounter = document.getElementById('addressCounter');

    if (descTextarea && descCounter) {
        descTextarea.addEventListener('input', () => {
            descCounter.textContent = descTextarea.value.length;
        });
    }

    if (addressTextarea && addressCounter) {
        addressTextarea.addEventListener('input', () => {
            addressCounter.textContent = addressTextarea.value.length;
        });
    }

    // Multiselect functionality
    const booksDisplay = document.getElementById('booksDisplay');
    const booksDropdown = document.getElementById('booksDropdown');
    const booksCheckboxes = booksDropdown.querySelectorAll('input[type="checkbox"]');

    booksDisplay.addEventListener('click', (e) => {
        e.stopPropagation();
        booksDropdown.classList.toggle('show');
        booksDisplay.classList.toggle('active');
    });

    document.addEventListener('click', () => {
        booksDropdown.classList.remove('show');
        booksDisplay.classList.remove('active');
    });

    booksDropdown.addEventListener('click', (e) => {
        e.stopPropagation();
    });

    function updateBooksDisplay() {
        const selected = Array.from(booksCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        booksDisplay.textContent = selected.length > 0 
            ? selected.join(', ') 
            : 'Select books of accounts...';
    }

    booksCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBooksDisplay);
    });

    // Notification system
    function showNotification(message, type = 'info', duration = 4000) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.className = `notification ${type} show`;
        
        if (duration > 0) {
            setTimeout(() => {
                notification.classList.remove('show');
            }, duration);
        }
    }

    // Form validation
    function validateForm() {
        const errors = [];
        
        // Required fields
        const businessName = document.getElementById('businessLegalName');
        if (!businessName.value.trim()) {
            errors.push('Business Legal Name is required');
            businessName.classList.add('error');
            businessName.nextElementSibling.nextElementSibling.classList.add('show');
        } else {
            businessName.classList.remove('error');
            businessName.nextElementSibling.nextElementSibling.classList.remove('show');
        }

        const registrationType = document.getElementById('registrationType');
        if (!registrationType.value) {
            errors.push('Business Registration Type is required');
            registrationType.classList.add('error');
            registrationType.nextElementSibling.nextElementSibling.classList.add('show');
        } else {
            registrationType.classList.remove('error');
            registrationType.nextElementSibling.nextElementSibling.classList.remove('show');
        }

        // Email validation
        const email = document.getElementById('officialEmail');
        if (email.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value.trim())) {
                errors.push('Please enter a valid email address');
                email.classList.add('error');
            }
        }

        return errors;
    }

    // Gather form data
    function gatherFormData() {
        const getVal = (id) => {
            const el = document.getElementById(id);
            return el ? el.value.trim() : '';
        };

        const getChecked = (id) => {
            const el = document.getElementById(id);
            return el ? el.checked : false;
        };

        const getBooksSelected = () => {
            return Array.from(booksCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value)
                .join(', ');
        };

        return {
            // Basic Profile
            businessLegalName: getVal('businessLegalName'),
            tradeName: getVal('tradeName'),
            registrationType: getVal('registrationType'),
            registrationNo: getVal('registrationNo'),
            dateOfRegistration: getVal('dateOfRegistration'),
            industryCode: getVal('industryCode'),
            businessDescription: getVal('businessDescription'),

            // Tax Settings
            taxType: getVal('taxType'),
            booksOfAccounts: getBooksSelected(),
            accountingMethod: getVal('accountingMethod'),
            fiscalStart: getVal('fiscalStart'),
            quarterCutoff: getVal('quarterCutoff'),
            withholdingAgent: getChecked('withholdingToggle'),

            // Address & IDs
            businessTin: getVal('businessTin'),
            rdoCode: getVal('rdoCode'),
            officialAddress: getVal('officialAddress'),
            zipCode: getVal('zipCode'),
            contactPhone: getVal('contactPhone'),
            officialEmail: getVal('officialEmail'),

            // Locale/Preferences
            currency: getVal('currency'),
            timezone: getVal('timezone'),
            weekStart: getVal('weekStart'),
            dateFormat: getVal('dateFormat'),
            numberFormat: getVal('numberFormat'),
            brandColor: getVal('brandColor'),

            // Regulatory
            sss: getVal('sss'),
            phic: getVal('phic'),
            hdmf: getVal('hdmf'),
            peza: getVal('peza'),
            permits: getVal('permits'),

            // Documents
            orPrefix: getVal('orPrefix'),
            siPrefix: getVal('siPrefix'),
            nextOr: getVal('nextOr'),
            nextSi: getVal('nextSi'),
            pdfTemplate: getVal('pdfTemplate'),

            // Miscellaneous
            enableMultiBranch: getChecked('enableMultiBranch'),
            inventoryTracking: getVal('inventoryTracking'),
            useWeightedCost: getChecked('useWeightedCost'),
            enableAuditTrail: getChecked('enableAuditTrail')
        };
    }

    // Save settings
    const saveBtn = document.getElementById('saveSettingsBtn');
    saveBtn.addEventListener('click', async () => {
        // Validate
        const errors = validateForm();
        if (errors.length > 0) {
            showNotification(errors.join('\n'), 'error', 6000);
            return;
        }

        // Disable button
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';

        try {
            // Gather data
            const formData = gatherFormData();
            console.log('Form data:', formData);

            // Simulate API call (replace with actual endpoint)
            await new Promise(resolve => setTimeout(resolve, 1500));

            // Update timestamp
            const now = new Date();
            document.getElementById('lastUpdated').value = now.toLocaleString();

            // Success
            showNotification('Settings saved successfully!', 'success');
            
        } catch (error) {
            console.error('Save error:', error);
            showNotification('Failed to save settings. Please try again.', 'error');
        } finally {
            // Re-enable button
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save All Settings';
        }
    });

    // Auto-format TIN
    const tinInput = document.getElementById('businessTin');
    if (tinInput) {
        tinInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 12) value = value.slice(0, 12);
            
            if (value.length >= 3) {
                value = value.slice(0, 3) + '-' + value.slice(3);
            }
            if (value.length >= 7) {
                value = value.slice(0, 7) + '-' + value.slice(7);
            }
            if (value.length >= 11) {
                value = value.slice(0, 11) + '-' + value.slice(11);
            }
            
            e.target.value = value;
        });
    }

    // Auto-format RDO
    const rdoInput = document.getElementById('rdoCode');
    if (rdoInput) {
        rdoInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 3);
        });
    }

    // Auto-format ZIP
    const zipInput = document.getElementById('zipCode');
    if (zipInput) {
        zipInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
        });
    }

    // Clear error on input
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', () => {
            field.classList.remove('error');
            const errorMsg = field.parentElement.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.classList.remove('show');
            }
        });
    });

    console.log('Business Settings UI initialized');
</script>
@endpush