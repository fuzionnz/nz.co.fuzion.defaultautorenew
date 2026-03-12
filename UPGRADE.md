# Upgrade Notes — CiviCRM 6.4.x / PHP 8.1+ Compatibility

## Version 2.0 (2025)

This release updates the extension (originally written for CiviCRM 4.7 / PHP 5.4) to be
compatible with **CiviCRM 5.x / 6.4.x** and **PHP 8.1+**.

---

### Changes Made

#### 1. `defaultautorenew.civix.php` — PHP 8.0+ fatal error fix

**File:** `defaultautorenew.civix.php`, line ~246

**Problem:** Used deprecated curly-brace string offset syntax `$entry{0}`.
- Deprecated in PHP 7.4
- Triggers a fatal parse error in PHP 8.0+

**Fix:**
```php
// Before
if ($entry{0} == '.') {

// After
if ($entry[0] == '.') {
```

---

#### 2. `defaultautorenew.civix.php` — Removed `CRM_Core_Error::fatal()`

**File:** `defaultautorenew.civix.php`, inside `_defaultautorenew_civix_civicrm_caseTypes()`

**Problem:** `CRM_Core_Error::fatal()` was deprecated in CiviCRM 5.x and removed in 6.x.
Calling it would cause a fatal PHP error on newer CiviCRM installs.

**Fix:**
```php
// Before
CRM_Core_Error::fatal($errorMessage);
// throw new CRM_Core_Exception($errorMessage);

// After
throw new CRM_Core_Exception($errorMessage);
```

---

#### 3. `defaultautorenew.php` — Replaced `$form->get_template_vars()`

**File:** `defaultautorenew.php`, `defaultautorenew_civicrm_buildForm()`

**Problem:** `$form->get_template_vars()` relied on a pass-through method that no longer
exists on `CRM_Core_Form` in CiviCRM 5.x/6.x. CiviCRM's `CRM_Core_Form::assign()` stores
variables on the global `CRM_Core_Smarty` singleton, not on the form object itself.

**Fix:** Access template variables directly via the Smarty singleton:
```php
// Before
$auto = json_decode($form->get_template_vars('autoRenewMembershipTypeOptions'));

// After
$autoRenewOptions = CRM_Core_Smarty::singleton()->get_template_vars('autoRenewMembershipTypeOptions');
if (!empty($autoRenewOptions)) {
  $auto = is_string($autoRenewOptions) ? json_decode($autoRenewOptions) : (object) $autoRenewOptions;
  ...
}
```

The added `!empty()` guard also prevents PHP warnings when the contribution page has no
membership types with auto-renew enabled.

---

#### 4. `defaultautorenew.php` — Modernized PHP array syntax

**File:** `defaultautorenew.php`

**Problem:** Used older `array()` literal syntax and `list()` construct where modern
short syntax is clearer and consistent with current CiviCRM coding standards.

**Fix:**
```php
// Before
$form->setDefaults($defaults);           // $defaults was a separate variable
$manager->addSetting(array('autoRenewIds' => $ids));
list(, $id) = explode('_', $key);

// After
$form->setDefaults(['is_recur' => TRUE]);
$manager->addSetting(['autoRenewIds' => $ids]);
[, $id] = explode('_', $key);
```

---

#### 5. `defaultautorenew.js` — Replaced `eval()` with `JSON.parse()`

**File:** `defaultautorenew.js`

**Problem:** Used `eval()` to parse the `data-price-field-values` attribute:
```javascript
eval('data = ' + ct.attr('data-price-field-values'));
```
- `eval()` is a security risk (arbitrary code execution if attribute value is tampered with).
- Modern browser Content Security Policies (CSP) — which CiviCRM 5.x/6.x may enforce — block `eval()`.
- The attribute value is JSON produced by PHP's `json_encode()`, so `JSON.parse()` is the correct tool.

**Fix:**
```javascript
// Before
eval('data = ' + ct.attr('data-price-field-values'));

// After
dataStr = ct.attr('data-price-field-values');
if (!dataStr) { return; }
try {
  data = JSON.parse(dataStr);
} catch (e) {
  return;
}
```

A `try/catch` is added to gracefully handle any malformed attribute value instead of
throwing an uncaught exception.

---

---

#### 8. `info.xml` — Updated compatibility metadata

**File:** `info.xml`

- `<ver>4.7</ver>` → `<ver>5.0</ver>` + `<ver>6.0</ver>`
- Version bumped from `1.0` to `2.0`
- `develStage` changed from `alpha` to `stable`
- Updated description, maintainer contact, and removed `FIXME` placeholder URLs
- Updated release date and comments

---

### Minimum Requirements After This Update

| Requirement  | Before     | After      |
|--------------|------------|------------|
| PHP          | 5.4+       | 8.1+       |
| CiviCRM      | 4.7        | 5.0 / 6.4  |
