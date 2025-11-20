-- --------------------------------------------------------
-- FIXED SQL Data Seeder for Stafify Chart of Accounts
-- Table: chart_of_accounts
-- Column Fix: Changed 'name' to 'account_type'
-- --------------------------------------------------------

INSERT INTO `chart_of_accounts` 
(`account_number`, `account_type`, `account_group`, `is_parent`, `parent_account_number`, `description`, `created_at`, `updated_at`) 
VALUES

-- 100000: ASSETS
('100000', 'Assets', 'Assets', 1, NULL, 'Resources owned by the business that provide future economic benefit.', NOW(), NOW()),

-- 110000: CURRENT ASSETS
('110000', 'Current Assets', 'Assets', 1, '100000', 'Assets expected to be used, sold, or converted to cash within one year.', NOW(), NOW()),

-- 111000: Cash & Equivalents
('111000', 'Cash and Cash Equivalents', 'Assets', 1, '110000', 'General cash account', NOW(), NOW()),
('111100', 'Cash', 'Assets', 0, '111000', 'Any cash', NOW(), NOW()),
('111110', 'Petty Cash', 'Assets', 0, '111000', 'For small daily expenses', NOW(), NOW()),
('111200', 'Cash in Bank - Current', 'Assets', 0, '111000', 'Checking accounts', NOW(), NOW()),
('111300', 'Cash in Bank - Savings', 'Assets', 0, '111000', 'Savings accounts', NOW(), NOW()),
('111400', 'Checks on Hand', 'Assets', 0, '111000', 'Valid checks received but not yet deposited.', NOW(), NOW()),
('111500', 'Post Dated Checks', 'Assets', 0, '111000', 'Checks received with a future date.', NOW(), NOW()),

-- 112000: Accounts Receivable
('112000', 'Accounts Receivable', 'Assets', 1, '110000', 'General AR', NOW(), NOW()),
('112100', 'Accounts Receivables - Trade', 'Assets', 0, '112000', 'Sales-related AR', NOW(), NOW()),
('112200', 'Accounts Receivables - Non-Trade', 'Assets', 0, '112000', 'Non-sales AR (e.g., advances)', NOW(), NOW()),
('112300', 'Allowance for Doubtful Accounts', 'Assets', 0, '112000', 'Contra-asset account', NOW(), NOW()),

-- 113000: Notes Receivable
('113000', 'Notes Receivable', 'Assets', 1, '110000', 'Written promises from customers or borrowers.', NOW(), NOW()),
('113100', 'Notes Receivable - Short Term', 'Assets', 0, '113000', 'Collectible within one year.', NOW(), NOW()),
('113200', 'Notes Receivable - Long Term', 'Assets', 0, '113000', 'Due beyond one year.', NOW(), NOW()),

-- 114000: Loans Receivable
('114000', 'Loans Receivable', 'Assets', 1, '110000', 'Funds lent out to employees or affiliates.', NOW(), NOW()),
('114100', 'Loans Receivable - Short Term', 'Assets', 0, '114000', 'Expected to be collected within one year.', NOW(), NOW()),
('114200', 'Loans Receivable - Long Term', 'Assets', 0, '114000', 'Loans due beyond one year.', NOW(), NOW()),

-- 115000: Inventories
('115000', 'Inventories', 'Assets', 1, '110000', 'General inventory', NOW(), NOW()),
('115100', 'Merchandise Inventory', 'Assets', 0, '115000', 'For trading businesses', NOW(), NOW()),
('115200', 'Raw Materials Inventory', 'Assets', 0, '115000', 'For manufacturing businesses', NOW(), NOW()),
('115300', 'Finished Goods/Supplies Inventory', 'Assets', 0, '115000', 'Completed items or office supplies', NOW(), NOW()),

-- 116000: Taxes Receivable
('116000', 'Taxes Receivable', 'Assets', 1, '110000', 'Control account for taxes receivable', NOW(), NOW()),
('116100', 'Input VAT Receivable', 'Assets', 0, '116000', 'VAT paid from purchases', NOW(), NOW()),
('116200', 'VAT Receivable - Net', 'Assets', 0, '116000', 'Input VAT minus Output VAT', NOW(), NOW()),
('116300', 'Percentage Tax Receivable - Overpayment', 'Assets', 0, '116000', 'For Non-VAT registered entities refund', NOW(), NOW()),
('116400', 'Withholding Tax Receivable', 'Assets', 0, '116000', 'General WTC', NOW(), NOW()),
('116500', 'Withholding Tax Receivable – Expanded', 'Assets', 0, '116000', 'WTE (e.g., for freelancers)', NOW(), NOW()),
('116600', 'Withholding Tax Receivable – Compensation', 'Assets', 0, '116000', 'WTC on Compensation', NOW(), NOW()),
('116700', 'Final Tax Receivable', 'Assets', 0, '116000', 'For final tax types', NOW(), NOW()),
('116800', 'Income Tax Receivable', 'Assets', 0, '116000', 'After quarterly/annual tax filing', NOW(), NOW()),

-- 117000: Prepaid Expenses
('117000', 'Prepaid Expenses', 'Assets', 1, '110000', 'General prepaid category', NOW(), NOW()),
('117100', 'Prepaid Rent', 'Assets', 0, '117000', 'Rent paid in advance', NOW(), NOW()),
('117200', 'Prepaid Insurance', 'Assets', 0, '117000', 'Insurance paid in advance', NOW(), NOW()),

-- 118000: Advances (Set as Parent in original, but acts as header)
('118000', 'Advances to Employees', 'Assets', 1, '110000', 'Salary or expense advances', NOW(), NOW()),

-- 119000: Other Current Assets
('119000', 'Other Current Assets', 'Assets', 1, '110000', 'Short-term assets not falling under typical categories', NOW(), NOW()),

-- 120000: NON-CURRENT ASSETS
('120000', 'Non-Current Assets', 'Assets', 1, '100000', 'Long-term assets', NOW(), NOW()),

-- 121000: PPE
('121000', 'Property, Plant & Equipment', 'Assets', 1, '120000', 'Header for all fixed physical assets', NOW(), NOW()),
('121100', 'Land', 'Assets', 0, '121000', 'Non-depreciable owned land', NOW(), NOW()),
('121200', 'Building', 'Assets', 0, '121000', 'Structures used in business', NOW(), NOW()),
('121300', 'Leasehold Improvements', 'Assets', 0, '121000', 'Improvements on leased spaces', NOW(), NOW()),
('121400', 'Office Equipment', 'Assets', 0, '121000', 'Printers, phones, etc.', NOW(), NOW()),
('121500', 'IT Equipment', 'Assets', 0, '121000', 'Computers, servers, networking', NOW(), NOW()),
('121600', 'Furniture and Fixtures', 'Assets', 0, '121000', 'Desks, chairs, cabinets', NOW(), NOW()),
('121700', 'Tools and Equipment', 'Assets', 0, '121000', 'Small tools used in daily operations', NOW(), NOW()),
('121800', 'Transportation Equipment', 'Assets', 0, '121000', 'Vehicles owned by the company', NOW(), NOW()),
('121900', 'Machinery and Equipment', 'Assets', 0, '121000', 'Heavy equipment', NOW(), NOW()),
-- Accumulated Depreciation
('121910', 'Accumulated Depreciations - Buildings', 'Assets', 0, '121000', 'Contra to Building', NOW(), NOW()),
('121920', 'Accumulated Depreciations - Office Equipment', 'Assets', 0, '121000', 'Contra to Office Equipment', NOW(), NOW()),
('121930', 'Accumulated Depreciations - IT Equipment', 'Assets', 0, '121000', 'Contra to IT Equipment', NOW(), NOW()),
('121940', 'Accumulated Depreciations - Furniture', 'Assets', 0, '121000', 'Contra to Furniture', NOW(), NOW()),
('121950', 'Accumulated Depreciations - Tools', 'Assets', 0, '121000', 'Contra to Tools', NOW(), NOW()),
('121960', 'Accumulated Depreciations - Transportation', 'Assets', 0, '121000', 'Contra to Vehicles', NOW(), NOW()),
('121970', 'Accumulated Depreciation - Machinery', 'Assets', 0, '121000', 'Contra to Machinery', NOW(), NOW()),

-- 122000: Intangible Assets
('122000', 'Intangible Assets', 'Assets', 1, '120000', 'Non-physical assets', NOW(), NOW()),
('122100', 'Software and Application Licenses', 'Assets', 0, '122000', 'Purchased or developed software', NOW(), NOW()),
('122200', 'Trademark/Brand Name', 'Assets', 0, '122000', 'Registered intellectual property', NOW(), NOW()),
('122300', 'Goodwill', 'Assets', 0, '122000', 'From acquisition', NOW(), NOW()),
('122400', 'Website Development Costs', 'Assets', 0, '122000', 'Capitalized app/website costs', NOW(), NOW()),
('122900', 'Accumulated Amortization - Intangibles', 'Assets', 0, '122000', 'Contra-asset for intangibles', NOW(), NOW()),

-- 123000: Deferred Tax Asset
('123000', 'Deferred Tax Asset', 'Assets', 1, '120000', 'Future tax benefit', NOW(), NOW()),
('123100', 'Deferred Tax Asset Sub', 'Assets', 0, '123000', 'Future tax benefit due to temporary differences', NOW(), NOW()),

-- 124000: Other Non-Current
('124000', 'Other Non-Current Assets', 'Assets', 1, '120000', 'Major subgroup under Asset', NOW(), NOW()),
('124100', 'Security Deposits', 'Assets', 0, '124000', 'Long-term rental/utilities', NOW(), NOW()),
('124200', 'Rental Deposits', 'Assets', 0, '124000', 'Leases', NOW(), NOW()),
('124300', 'Long-Term Advances', 'Assets', 0, '124000', 'Expected to settle beyond 1 year', NOW(), NOW()),
('124400', 'Construction in Progress', 'Assets', 0, '124000', 'Ongoing construction for PPE', NOW(), NOW()),
('124900', 'Other Deferred Charges', 'Assets', 0, '124000', 'Long-term prepaid costs', NOW(), NOW()),

-- 200000: LIABILITIES
('200000', 'Liabilities', 'Liabilities', 1, NULL, 'Present obligations of the business', NOW(), NOW()),

-- 210000: Current Liabilities
('210000', 'Current Liabilities', 'Liabilities', 1, '200000', 'Obligations due within one year', NOW(), NOW()),

('211000', 'Accounts Payable', 'Liabilities', 1, '210000', 'General unpaid obligations', NOW(), NOW()),
('211100', 'Accounts Payable - Trade', 'Liabilities', 0, '211000', 'Suppliers for goods', NOW(), NOW()),
('211200', 'Accounts Payable - Non-Trade', 'Liabilities', 0, '211000', 'Other payables (rent, freelancers)', NOW(), NOW()),

('212000', 'Notes Payable', 'Liabilities', 1, '210000', 'Written promises to pay', NOW(), NOW()),
('212100', 'Notes Payable - Short Term', 'Liabilities', 0, '212000', 'Due within one year', NOW(), NOW()),
('212200', 'Notes Payable - Long Term', 'Liabilities', 0, '212000', 'Due beyond one year', NOW(), NOW()),

('213000', 'Loans Payable', 'Liabilities', 1, '210000', 'Amounts borrowed from banks', NOW(), NOW()),
('213100', 'Loans Payable - Short Term', 'Liabilities', 0, '213000', 'Due within one year', NOW(), NOW()),
('213200', 'Loans Payable - Long Term', 'Liabilities', 0, '213000', 'Due beyond one year', NOW(), NOW()),

('214000', 'Accrued Expenses', 'Liabilities', 1, '210000', 'Expenses incurred but unpaid', NOW(), NOW()),
('214100', 'Accrued Salaries and Wages', 'Liabilities', 0, '214000', 'Employee salaries not yet paid', NOW(), NOW()),
('214200', 'Accrued Utilities', 'Liabilities', 0, '214000', 'Electric, water, internet bills accrued', NOW(), NOW()),

('215000', 'Taxes Payable', 'Liabilities', 1, '210000', 'Control account for taxes payable', NOW(), NOW()),
('215100', 'Output VAT Payable', 'Liabilities', 0, '215000', 'VAT collected from sales', NOW(), NOW()),
('215200', 'VAT Payable - Net', 'Liabilities', 0, '215000', 'Output VAT minus Input VAT', NOW(), NOW()),
('215300', 'Percentage Tax Payable', 'Liabilities', 0, '215000', 'For Non-VAT registered entities', NOW(), NOW()),
('215400', 'Withholding Tax Payable', 'Liabilities', 0, '215000', 'General WTC', NOW(), NOW()),
('215500', 'Withholding Tax Payable – Expanded', 'Liabilities', 0, '215000', 'WTE', NOW(), NOW()),
('215600', 'Withholding Tax Payable – Compensation', 'Liabilities', 0, '215000', 'WTC on Comp', NOW(), NOW()),
('215700', 'Final Tax Payable', 'Liabilities', 0, '215000', 'For final tax types', NOW(), NOW()),
('215800', 'Income Tax Payable', 'Liabilities', 0, '215000', 'After quarterly/annual tax filing', NOW(), NOW()),

('216000', 'Government Contributions Payable', 'Liabilities', 1, '210000', 'SSS, PhilHealth, Pag-IBIG', NOW(), NOW()),
('216100', 'SSS Contributions Payable', 'Liabilities', 0, '216000', 'Mandatory benefit', NOW(), NOW()),
('216200', 'PhilHealth Contributions Payable', 'Liabilities', 0, '216000', 'Health insurance', NOW(), NOW()),
('216300', 'Pag-Ibig Contributions Payable', 'Liabilities', 0, '216000', 'Housing fund', NOW(), NOW()),

('217000', 'Unearned Revenue', 'Liabilities', 1, '210000', 'Advance collections', NOW(), NOW()),
('218000', 'Advances from Customers', 'Liabilities', 1, '210000', 'Customer deposits', NOW(), NOW()),
('219000', 'Other Current Liabilities', 'Liabilities', 1, '210000', 'Misc short-term payables', NOW(), NOW()),

-- 220000: Non Current Liabilities
('220000', 'Non Current Liabilities', 'Liabilities', 1, '200000', 'Long-term obligations', NOW(), NOW()),
('221000', 'Lease Liabilities – Long-Term', 'Liabilities', 0, '220000', 'For capital/finance lease', NOW(), NOW()),
('222000', 'Deferred Tax Liability', 'Liabilities', 0, '220000', 'Tax payable in future periods', NOW(), NOW()),
('223000', 'Retirement Benefit Obligations', 'Liabilities', 0, '220000', 'Estimated benefits due', NOW(), NOW()),
('224000', 'Other Non-Current Liabilities', 'Liabilities', 0, '220000', 'Misc long-term obligations', NOW(), NOW()),

-- 300000: EQUITY
('300000', 'Equity', 'Equity', 1, NULL, 'Total Equity control account', NOW(), NOW()),
('310000', 'Owner’s Capital', 'Equity', 1, '300000', 'Sole Proprietorship Capital', NOW(), NOW()),
('310100', 'Owner’s Additional Investment', 'Equity', 0, '310000', 'Investments during the year', NOW(), NOW()),
('310200', 'Owner’s Drawing / Dividends', 'Equity', 0, '310000', 'Withdrawals during the year', NOW(), NOW()),

-- Corporate Equity
('312000', 'Share Capital – Common', 'Equity', 1, '300000', 'Common stock', NOW(), NOW()),
('312100', 'Share Capital – Preferred', 'Equity', 0, '312000', 'Preferred stock', NOW(), NOW()),
('313000', 'Subscription Receivable', 'Equity', 0, '312000', 'Subscribed shares not yet paid', NOW(), NOW()),
('314000', 'Additional Paid-In Capital', 'Equity', 0, '312000', 'Excess paid over par', NOW(), NOW()),
('315000', 'Treasury Shares', 'Equity', 0, '312000', 'Contra-equity account', NOW(), NOW()),

-- Retained Earnings
('316000', 'Retained Earnings – Beginning Balance', 'Equity', 1, '300000', 'Prior period retained income', NOW(), NOW()),
('316100', 'Retained Earnings – Appropriated', 'Equity', 0, '316000', 'Restricted retained earnings', NOW(), NOW()),
('316200', 'Retained Earnings – Unappropriated', 'Equity', 0, '316000', 'Available for dividends', NOW(), NOW()),
('317000', 'Net Income for the Period', 'Equity', 0, '316000', 'Temporary account before closing', NOW(), NOW()),
('318000', 'Dividends Declared / Payable', 'Equity', 0, '316000', 'For Corporations', NOW(), NOW()),
('319000', 'Other Equity Adjustments', 'Equity', 0, '316000', 'Revaluations, translation, reserves', NOW(), NOW()),

-- 400000: REVENUE
('400000', 'Revenue', 'Revenue', 1, NULL, 'Income earned from operations', NOW(), NOW()),

('410000', 'Revenue from Sale of Goods', 'Revenue', 1, '400000', 'Income from selling products', NOW(), NOW()),
('411000', 'Sales – VATable', 'Revenue', 0, '410000', 'Sales subject to 12% VAT', NOW(), NOW()),
('412000', 'Sales – Non-VAT', 'Revenue', 0, '410000', 'Sales under 3% or 1%', NOW(), NOW()),
('413000', 'Sales – Zero Rated', 'Revenue', 0, '410000', 'Export sales', NOW(), NOW()),
('414000', 'Sales – Tax Exempt', 'Revenue', 0, '410000', 'Exempt under Sec 109', NOW(), NOW()),
('415000', 'Sales Returns and Allowances', 'Revenue', 0, '410000', 'Contra-revenue', NOW(), NOW()),
('416000', 'Sales Discounts', 'Revenue', 0, '410000', 'Contra-revenue', NOW(), NOW()),

('420000', 'Revenue from Services', 'Revenue', 1, '400000', 'Income from providing services', NOW(), NOW()),
('421000', 'Service Income – VATable', 'Revenue', 0, '420000', 'Services subject to 12% VAT', NOW(), NOW()),
('422000', 'Service Income – Non-VAT', 'Revenue', 0, '420000', 'Non-VAT registered service income', NOW(), NOW()),
('423000', 'Service Income – Zero Rated', 'Revenue', 0, '420000', 'BPO/ITO export services', NOW(), NOW()),
('424000', 'Service Income – Tax Exempt', 'Revenue', 0, '420000', 'Medical/Educational', NOW(), NOW()),
('425000', 'Service Income Returns', 'Revenue', 0, '420000', 'Refunds or adjustments', NOW(), NOW()),
('426000', 'Service Discounts', 'Revenue', 0, '420000', 'Discounts given', NOW(), NOW()),

('430000', 'Other Operating Income', 'Revenue', 1, '400000', 'Revenue from non-core ops', NOW(), NOW()),
('431000', 'Commission Income', 'Revenue', 0, '430000', 'Reseller or agent fees', NOW(), NOW()),
('432000', 'Rental Income', 'Revenue', 0, '430000', 'From leasing property', NOW(), NOW()),
('433000', 'Consulting Fees', 'Revenue', 0, '430000', 'Non-regular consulting', NOW(), NOW()),
('434000', 'Franchise Fees', 'Revenue', 0, '430000', 'If applicable', NOW(), NOW()),
('435000', 'Royalty Income', 'Revenue', 0, '430000', 'IP revenue', NOW(), NOW()),
('436000', 'Miscellaneous Income', 'Revenue', 0, '430000', 'Catch-all', NOW(), NOW()),
('439000', 'Other Income – Tax Exempt', 'Revenue', 0, '430000', 'Grants, donations', NOW(), NOW()),

-- 500000: COST OF SALES
('500000', 'Cost of Sales', 'Cost of Sales', 1, NULL, 'Direct costs of production/delivery', NOW(), NOW()),

('510000', 'Cost of Sales – Goods', 'Cost of Sales', 1, '500000', 'Merchandise costs', NOW(), NOW()),
('511000', 'Merchandise Inventory – Beginning', 'Cost of Sales', 0, '510000', 'Starting inventory', NOW(), NOW()),
('511100', 'Purchases', 'Cost of Sales', 0, '510000', 'Inventory bought', NOW(), NOW()),
('511200', 'Freight In', 'Cost of Sales', 0, '510000', 'Delivery cost', NOW(), NOW()),
('511300', 'Direct Materials Consumed', 'Cost of Sales', 0, '510000', 'For manufacturers', NOW(), NOW()),
('511400', 'Direct Labor – Production', 'Cost of Sales', 0, '510000', 'For manufacturers', NOW(), NOW()),
('511500', 'Manufacturing Overhead', 'Cost of Sales', 0, '510000', 'Factory utilities', NOW(), NOW()),
('511600', 'Inventory Adjustments', 'Cost of Sales', 0, '510000', 'Write-downs', NOW(), NOW()),
('511700', 'Merchandise Inventory – Ending', 'Cost of Sales', 0, '510000', 'Ending inventory', NOW(), NOW()),
('511900', 'Cost of Goods Sold (Computed)', 'Cost of Sales', 0, '510000', 'Total COGS', NOW(), NOW()),
('512000', 'Purchase Returns and Allowances', 'Cost of Sales', 0, '510000', 'Contra-expense', NOW(), NOW()),
('512100', 'Purchase Discounts', 'Cost of Sales', 0, '510000', 'Contra-expense', NOW(), NOW()),

('520000', 'Cost of Sales – Services', 'Cost of Sales', 1, '500000', 'Direct costs for services', NOW(), NOW()),
('521000', 'Direct Labor – Service Staff', 'Cost of Sales', 0, '520000', 'Therapists, agents, developers', NOW(), NOW()),
('521100', 'Service Subcontractors', 'Cost of Sales', 0, '520000', 'Outsourced services', NOW(), NOW()),
('521200', 'Service Materials & Consumables', 'Cost of Sales', 0, '520000', 'Supplies directly used', NOW(), NOW()),
('521300', 'Direct Travel & Transportation', 'Cost of Sales', 0, '520000', 'Related to service delivery', NOW(), NOW()),
('521400', 'Utilities – Direct', 'Cost of Sales', 0, '520000', 'Internet, Power directly used', NOW(), NOW()),
('521500', 'Depreciation – Service Equipment', 'Cost of Sales', 0, '520000', 'Equipment depreciation', NOW(), NOW()),
('521900', 'Other Direct Costs – Services', 'Cost of Sales', 0, '520000', 'Any other attributable cost', NOW(), NOW()),
('522000', 'Service Purchase Returns', 'Cost of Sales', 0, '520000', 'Contra-expense', NOW(), NOW()),
('522100', 'Service Purchase Discounts', 'Cost of Sales', 0, '520000', 'Contra-expense', NOW(), NOW()),

-- 600000: OPERATING EXPENSES
('600000', 'Operating Expenses', 'Operating Expenses', 1, NULL, 'Indirect costs', NOW(), NOW()),

-- Ops Salaries
('610000', 'Salaries and Wages (Ops)', 'Operating Expenses', 1, '600000', 'Total gross pay ops', NOW(), NOW()),
('611000', 'Basic Pay (Ops)', 'Operating Expenses', 0, '610000', 'Regular base salary', NOW(), NOW()),
('611100', 'Overtime Pay (Ops)', 'Operating Expenses', 0, '610000', 'Overtime rendered', NOW(), NOW()),
('611200', 'Allowances – Regular (Ops)', 'Operating Expenses', 0, '610000', 'Recurring allowances', NOW(), NOW()),
('611300', 'Commissions – Regular (Ops)', 'Operating Expenses', 0, '610000', 'Recurring commissions', NOW(), NOW()),
('611400', 'Allowances – On-Call (Ops)', 'Operating Expenses', 0, '610000', 'Standby pay', NOW(), NOW()),
('611500', 'Commissions – On-Call (Ops)', 'Operating Expenses', 0, '610000', 'Emergency commissions', NOW(), NOW()),

-- Ops Benefits
('620000', 'Employee Benefits (Ops)', 'Operating Expenses', 1, '600000', 'Non-cash/indirect benefits', NOW(), NOW()),
('621000', 'SSS Premium Contribution (Ops)', 'Operating Expenses', 0, '620000', 'Employer share SSS', NOW(), NOW()),
('621100', 'PHIC Premium Contribution (Ops)', 'Operating Expenses', 0, '620000', 'Employer share PhilHealth', NOW(), NOW()),
('621200', 'HDMF Premium Contribution (Ops)', 'Operating Expenses', 0, '620000', 'Employer share Pag-IBIG', NOW(), NOW()),
('621300', 'COLA (Ops)', 'Operating Expenses', 0, '620000', 'Cost of Living Allowance', NOW(), NOW()),
('621400', 'Rice Subsidy (Ops)', 'Operating Expenses', 0, '620000', 'Rice subsidy', NOW(), NOW()),
('621500', 'Meal Allowance (Ops)', 'Operating Expenses', 0, '620000', 'Meals', NOW(), NOW()),
('621600', 'Uniform Allowance (Ops)', 'Operating Expenses', 0, '620000', 'Uniforms', NOW(), NOW()),
('621700', 'Laundry Allowance (Ops)', 'Operating Expenses', 0, '620000', 'Laundry', NOW(), NOW()),
('621800', 'Medical Allowance (Ops)', 'Operating Expenses', 0, '620000', 'Medical', NOW(), NOW()),
('622000', '13th Month Pay (Ops)', 'Operating Expenses', 0, '620000', 'Year-end bonus', NOW(), NOW()),
('622100', 'Service Incentive Leaves (Ops)', 'Operating Expenses', 0, '620000', 'SIL', NOW(), NOW()),
('622400', 'HMO (Ops)', 'Operating Expenses', 0, '620000', 'Health insurance', NOW(), NOW()),

-- Admin Salaries
('630000', 'Salaries and Wages (Admin)', 'Operating Expenses', 1, '600000', 'Office/admin staff salaries', NOW(), NOW()),
('631000', 'Basic Pay (Admin)', 'Operating Expenses', 0, '630000', 'Base salary', NOW(), NOW()),
('631100', 'Overtime Pay (Admin)', 'Operating Expenses', 0, '630000', 'Overtime', NOW(), NOW()),

-- Admin Benefits
('640000', 'Employee Benefits (Admin)', 'Operating Expenses', 1, '600000', 'Benefits for admin', NOW(), NOW()),
('641000', 'SSS Premium (Admin)', 'Operating Expenses', 0, '640000', 'Employer share SSS', NOW(), NOW()),
('641100', 'PHIC Premium (Admin)', 'Operating Expenses', 0, '640000', 'Employer share PhilHealth', NOW(), NOW()),
('641200', 'HDMF Premium (Admin)', 'Operating Expenses', 0, '640000', 'Employer share Pag-IBIG', NOW(), NOW()),
('642600', '13th Month Pay (Admin)', 'Operating Expenses', 0, '640000', 'Year-end bonus', NOW(), NOW()),
('643800', 'HMO (Admin)', 'Operating Expenses', 0, '640000', 'Health insurance', NOW(), NOW()),

-- Sales & Marketing
('650000', 'Sales & Marketing Expenses', 'Operating Expenses', 1, '600000', 'Customer acquisition', NOW(), NOW()),
('651000', 'Sales Commission', 'Operating Expenses', 0, '650000', 'Incentives', NOW(), NOW()),
('651100', 'Marketing and Advertising', 'Operating Expenses', 0, '650000', 'Ads, SEO', NOW(), NOW()),
('651200', 'Travel – Marketing/Sales', 'Operating Expenses', 0, '650000', 'Campaign travel', NOW(), NOW()),
('651300', 'Gasoline, Oil, Lubricant', 'Operating Expenses', 0, '650000', 'Vehicle fuel', NOW(), NOW()),
('651400', 'Representation Expense', 'Operating Expenses', 0, '650000', 'Client meetings', NOW(), NOW()),

-- Utilities & Rent
('660000', 'Utilities & Rent', 'Operating Expenses', 1, '600000', 'Essential services', NOW(), NOW()),
('661000', 'Rent Expense', 'Operating Expenses', 0, '660000', 'Office rent', NOW(), NOW()),
('661100', 'Electricity', 'Operating Expenses', 0, '660000', 'Power bills', NOW(), NOW()),
('661200', 'Water', 'Operating Expenses', 0, '660000', 'Water consumption', NOW(), NOW()),
('661300', 'Internet', 'Operating Expenses', 0, '660000', 'Broadband/Data', NOW(), NOW()),
('661400', 'Communications', 'Operating Expenses', 0, '660000', 'Phone/Mobile', NOW(), NOW()),
('661600', 'Garbage Hauling', 'Operating Expenses', 0, '660000', 'Disposal', NOW(), NOW()),

-- Depreciation (Opex)
('670000', 'Depreciation & Amortization', 'Operating Expenses', 1, '600000', 'Allocation of asset costs', NOW(), NOW()),
('671000', 'Depreciation Expense', 'Operating Expenses', 0, '670000', 'Physical assets', NOW(), NOW()),
('671100', 'Amortization Expense', 'Operating Expenses', 0, '670000', 'Intangibles', NOW(), NOW()),

-- Admin Services
('680000', 'Admin Services', 'Operating Expenses', 1, '600000', 'General admin expenses', NOW(), NOW()),
('681000', 'Office Supplies', 'Operating Expenses', 0, '680000', 'Pens, paper', NOW(), NOW()),
('681100', 'Property Insurance', 'Operating Expenses', 0, '680000', 'Insurance', NOW(), NOW()),
('681200', 'Outside Services', 'Operating Expenses', 0, '680000', 'Contracted services', NOW(), NOW()),
('681500', 'Legal Services', 'Operating Expenses', 0, '680000', 'Lawyers', NOW(), NOW()),
('681600', 'Accounting & Bookkeeping', 'Operating Expenses', 0, '680000', 'Finance work', NOW(), NOW()),
('681700', 'IT Services', 'Operating Expenses', 0, '680000', 'Hosting, tech support', NOW(), NOW()),
('681800', 'Security Services', 'Operating Expenses', 0, '680000', 'Guards', NOW(), NOW()),
('681900', 'Trainings and Seminars', 'Operating Expenses', 0, '680000', 'Education', NOW(), NOW()),

-- Other Opex
('612000', 'Other OPEX', 'Operating Expenses', 1, '600000', 'Misc expenses', NOW(), NOW()),
('612100', 'Taxes and Licenses', 'Operating Expenses', 0, '612000', 'Permits', NOW(), NOW()),
('612900', 'Miscellaneous Expense', 'Operating Expenses', 0, '612000', 'Catch-all', NOW(), NOW()),

-- 700000: OTHER EXPENSES
('700000', 'Other Expenses', 'Other Expenses', 1, NULL, 'Non-operating costs', NOW(), NOW()),
('710000', 'Other Operating Losses', 'Other Expenses', 1, '700000', 'Non-recurring losses', NOW(), NOW()),
('712000', 'Interest Expense', 'Other Expenses', 0, '710000', 'Loans/Credit', NOW(), NOW()),
('713000', 'Bank Charges', 'Other Expenses', 0, '710000', 'Fees', NOW(), NOW()),
('714000', 'Penalties & Surcharges', 'Other Expenses', 0, '710000', 'Fines', NOW(), NOW()),
('715000', 'Bad Debts/Write-Offs', 'Other Expenses', 0, '710000', 'Uncollectible', NOW(), NOW()),
('716000', 'FX Losses', 'Other Expenses', 0, '710000', 'Currency loss', NOW(), NOW()),

-- 800000: OTHER INCOME
('800000', 'Other Income', 'Other Income', 1, NULL, 'Non-core revenue', NOW(), NOW()),
('810000', 'Miscellaneous Income', 'Other Income', 1, '800000', 'Misc income', NOW(), NOW()),
('811000', 'Gain on Sale of Assets', 'Other Income', 0, '810000', 'Net profit from assets', NOW(), NOW()),
('812000', 'Foreign Exchange Gain', 'Other Income', 0, '810000', 'Currency gain', NOW(), NOW()),
('820000', 'Rebates and Discounts', 'Other Income', 1, '800000', 'Earned discounts', NOW(), NOW()),
('821000', 'Purchase Discounts Earned', 'Other Income', 0, '820000', 'Early payment', NOW(), NOW()),
('822000', 'Sales Rebates Earned', 'Other Income', 0, '820000', 'Incentives', NOW(), NOW()),

-- 900000: ADJUSTMENTS
('900000', 'Year-End Adjustments', 'Year-End Adjustments & Closing Entries', 1, NULL, 'Closing entries', NOW(), NOW()),
('910000', 'Adjusting Entries', 'Year-End Adjustments & Closing Entries', 1, '900000', 'Manual entries', NOW(), NOW()),
('911000', 'Accrued Expenses Adj', 'Year-End Adjustments & Closing Entries', 0, '910000', 'Expenses incurred not recorded', NOW(), NOW()),
('912000', 'Prepaid Expenses Adj', 'Year-End Adjustments & Closing Entries', 0, '910000', 'Expenses paid in advance', NOW(), NOW()),
('920000', 'Closing Entries', 'Year-End Adjustments & Closing Entries', 1, '900000', 'Clears temporary accounts', NOW(), NOW()),
('921000', 'Income Summary', 'Year-End Adjustments & Closing Entries', 0, '920000', 'Holding account', NOW(), NOW());