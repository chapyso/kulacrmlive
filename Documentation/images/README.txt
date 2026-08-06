================================================================================
LIVESTOCK MANAGEMENT SYSTEM - DOCUMENTATION SCREENSHOTS
================================================================================

This folder holds every screenshot referenced by documentation/index.html.

How it works:
  - Every <img> in index.html points to a file in THIS folder (flat, no
    sub-folders).
  - If a file is missing, a styled "screenshot needed" placeholder is shown
    automatically in the browser (handled by js/main.js). The placeholder
    prints the EXACT filename the screenshot should be saved as.
  - To finish the docs, take each screenshot below and save it into this
    folder using the exact filename shown (PNG, JPG and WEBP are all fine -
    match the name).

Recommended capture:
  - Browser at 1280px wide (or larger).
  - Use the demo data so screenshots have real rows, not empty tables.

Total images needed: 41
(Edit modals, button-only crops, "after assignment" duplicates and filter
variants were intentionally dropped to keep this list lean. The "Add" modal
covers the layout; the "Edit" modal looks identical with values pre-filled.)

--------------------------------------------------------------------------------
LOGIN (1)
--------------------------------------------------------------------------------
  login-page.png         Login page at /auth/login.

--------------------------------------------------------------------------------
FARM SETUP (1)
--------------------------------------------------------------------------------
  settings.png           Settings page (incl. timezone & threshold).

--------------------------------------------------------------------------------
LIVESTOCK (2)
--------------------------------------------------------------------------------
  livestock_add.png            Add Livestock modal (with Notes field).
  livestock_variant_add.png    Add Variant modal under a breed.

--------------------------------------------------------------------------------
SUPPLIER (1)
--------------------------------------------------------------------------------
  supplier_add.png             Add Supplier modal.

--------------------------------------------------------------------------------
LIVESTOCK PURCHASE (2)
--------------------------------------------------------------------------------
  livestock_purchase1.png      Add New Purchase form (line items + payment tick).
  supplier_payment2.png        Add Supplier Payment modal.

--------------------------------------------------------------------------------
SHED (5)
--------------------------------------------------------------------------------
  shed.png                     Shed list page.
  shed_details.png             Shed details / view page.
  assign_to_shed.png           Assign livestock to shed modal.
  shed_death.png               Add New Death modal.
  transfer_list.png            Transfer list page.

--------------------------------------------------------------------------------
PRODUCT UNIT (1)
--------------------------------------------------------------------------------
  product_unit.png             Product unit list.

--------------------------------------------------------------------------------
VACCINE (8)
--------------------------------------------------------------------------------
  add_vaccine_route.png        Add Vaccine Route modal.
  create_new_vaccine.png       Add Vaccine modal.
  vaccine_dose_assign.png      Vaccine Dose Assign modal.
  vaccine_assign_list.png      Vaccine assign list page.
  vaccine_doses.png            Vaccine doses detail page.
  vaccine_purchase.png         Add Vaccine Purchase form.
  shed_wise_vaccine_date.png   Shed-wise vaccination date page.

--------------------------------------------------------------------------------
FOOD (5)
--------------------------------------------------------------------------------
  create_food.png              Create Food modal.
  assign_food_to_batch.png     Assign Food to Batch modal.
  purchase_food.png            Add Food Purchase form.
  distribute_food.png          Distribute Food modal.

--------------------------------------------------------------------------------
PRODUCTION (6)
--------------------------------------------------------------------------------
  product_category.png         Product Category list.
  product_create.png           Create Product modal.
  assign_product.png           Assign Product to Batch modal.
  product_stock_add_popup.png  Add Product Stock modal.
  reproduction_add.png         Add Livestock Reproduction modal.

--------------------------------------------------------------------------------
CLIENT (1)
--------------------------------------------------------------------------------
  add_client.png               Add Client modal/form.

--------------------------------------------------------------------------------
SALE (3)
--------------------------------------------------------------------------------
  livestock_sale.png           Add Livestock Sale form.
  product_sale.png             Add Product Sale form.
  add_client_payment.png       Add Client Payment modal.

--------------------------------------------------------------------------------
OTHER EXPENSES (2)
--------------------------------------------------------------------------------
  expesne_category.png         Expense Category list (note: original
                               misspelling kept).
  expense.png                  Expense list (with date filter & CSV).

--------------------------------------------------------------------------------
STAFF (2)
--------------------------------------------------------------------------------
  staff_add.png                Add Staff modal/form.
  staff_payments.png           Staff Payments list page.

--------------------------------------------------------------------------------
TRASH & BACKUP (0)
--------------------------------------------------------------------------------
  (no images needed for this section)

--------------------------------------------------------------------------------
REPORTS (14)
--------------------------------------------------------------------------------
  dashboard.png                       Main dashboard.
  batch_analysis_report.png           Batch Analysis result page.
  shed_analysis_report.png            Shed Analysis result page.
  finance_report.png                  Finance Report page.
  (no images needed for other reports)

================================================================================
Drop each file directly into THIS folder using the exact name shown above
and the placeholders in the rendered documentation will be replaced
automatically.
================================================================================
