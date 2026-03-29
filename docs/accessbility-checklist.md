# Accessibility & Web Standards Checklist

Use this checklist before submitting or merging any page. All pages must pass
**axe DevTools** (no Critical or Serious violations) and the
**W3C Markup Validator** (no errors).

---

## Semantic HTML

- [ ] Use semantic elements: `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<footer>`
- [ ] Avoid `<div>` or `<span>` where a semantic element would be appropriate
- [ ] Page has exactly one `<main>` landmark per page
- [ ] Lists of items use `<ul>` or `<ol>` (e.g., product grids, nav menus)

---

## Heading Hierarchy

- [ ] Page has exactly **one `<h1>`** - the primary page topic
- [ ] Headings follow logical order: `h1 → h2 → h3` - no levels skipped
- [ ] Heading text clearly describes the section it introduces
- [ ] Headings are not used purely for visual sizing - use CSS classes instead

---

## Images & Alt Text

- [ ] Every `<img>` has an `alt` attribute (even if empty)
- [ ] Decorative images: `alt=""` and optionally `role="presentation"`
- [ ] Meaningful images have descriptive alt text (not "image", "photo", or the filename)
- [ ] Product images: `alt="{Product Name} - {Manufacturer} {Type} switch"`
- [ ] Logo: `alt="KeyForge - home"` (or similar descriptive text)
- [ ] Icon-only elements use `aria-hidden="true"` and are accompanied by visible or visually-hidden text

---

## Forms & Labels

- [ ] Every `<input>`, `<select>`, and `<textarea>` has an associated `<label>` via matching `for`/`id`
- [ ] Visually hidden labels use `.visually-hidden` (Bootstrap) - **never remove labels entirely**
- [ ] Required fields: include the HTML `required` attribute AND `aria-required="true"`
- [ ] Required fields are visually indicated (e.g., asterisk `*`) with a legend explaining the symbol
- [ ] Error messages are linked to their input via `aria-describedby`
- [ ] Error messages use `role="alert"` or `role="status"` for dynamic announcements
- [ ] Submit buttons have descriptive text ("Log In", "Create Account" - not just "Submit")
- [ ] `autocomplete` attributes used where appropriate (`email`, `current-password`, `username`, etc.)
- [ ] `<fieldset>` and `<legend>` used to group related radio buttons and checkboxes

---

## Keyboard Navigation

- [ ] All interactive elements are reachable with the **Tab** key
- [ ] Tab order follows the visual reading order (top-left to bottom-right)
- [ ] **Focus indicators are never hidden** - `outline: none` must not be used without a custom replacement
- [ ] Custom focus styles meet WCAG 2.1 contrast requirements (3:1 minimum against adjacent colours)
- [ ] **"Skip to main content"** is the first focusable element on every page
- [ ] Modal dialogs trap focus when open and return focus to the trigger on close
- [ ] Dropdown menus are keyboard navigable (Bootstrap handles this for its own components)

---

## Colour & Contrast

- [ ] Normal text: contrast ratio ≥ **4.5:1** (WCAG AA)
- [ ] Large text (18pt / 14pt bold): contrast ratio ≥ **3:1** (WCAG AA)
- [ ] Information is not conveyed by colour alone (e.g., error states also show an icon or text)
- [ ] Test contrast with the [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)

---

## ARIA

- [ ] ARIA roles only used when no native HTML element conveys the same meaning
- [ ] Landmark regions have unique `aria-label` when multiple of the same type exist (e.g., two `<nav>` elements)
- [ ] Dynamic content updates use `aria-live="polite"` regions (e.g., filter result count)
- [ ] Icon-only buttons have `aria-label` describing the action (e.g., `aria-label="Remove Cherry MX Red from cart"`)
- [ ] Expanded/collapsed components use `aria-expanded="true/false"` (Bootstrap handles this for accordions, dropdowns)
- [ ] Do not use `aria-label` to override meaningful visible text - make the visible text correct instead

---

## Bootstrap-Specific

- [ ] **Modal**: `aria-labelledby` points to the modal's `<h*>` title element; includes `role="dialog"`
- [ ] **Navbar toggle**: button has `aria-controls` (pointing to nav collapse ID) and `aria-expanded`
- [ ] **Carousel**: container has `aria-label`; prev/next controls have `aria-label`; auto-play can be paused
- [ ] **Alert (dynamic)**: use `role="alert"` for error messages; `role="status"` for success/info
- [ ] **Dropdown**: parent button has `aria-expanded` (Bootstrap handles this automatically)
- [ ] **Tabs**: use Bootstrap's tab component correctly - `role="tablist"`, `role="tab"`, `aria-selected`
- [ ] **Pagination**: `<nav aria-label="Pagination navigation">` wraps the `<ul class="pagination">`; active page has `aria-current="page"`
- [ ] **Tables**: use `<th scope="col">` for column headers and `<th scope="row">` for row headers

---

## W3C Markup Validation

Run every page through https://validator.w3.org/ before submitting.

- [ ] `<!DOCTYPE html>` on every page
- [ ] `<html lang="en">` set (update language code if content is in another language)
- [ ] `<meta charset="UTF-8">` in `<head>`
- [ ] `<meta name="viewport" content="width=device-width, initial-scale=1.0">` in `<head>`
- [ ] `<title>` is present, unique, and descriptive per page
- [ ] No duplicate `id` attributes on the same page
- [ ] All elements are properly opened and closed
- [ ] All attribute values are quoted
- [ ] No deprecated HTML attributes (`align`, `bgcolor`, `border` on tables, etc.)
- [ ] No inline `style` attributes where a CSS class would be more appropriate
- [ ] No stray or unclosed tags
- [ ] `<button>` elements inside forms have explicit `type="submit"` or `type="button"`

---

## axe DevTools

1. Install the [axe DevTools browser extension](https://www.deque.com/axe/browser-extensions/)
2. Open the page in Chrome or Firefox
3. Open DevTools → axe DevTools tab → Run analysis
4. **Resolve all Critical and Serious violations before submitting**
5. Document any Best Practice or Minor issues that were intentionally accepted (with justification)

---

## Quick Self-Review Questions

Before opening a PR, ask yourself:

1. Can I navigate the entire page using only the keyboard?
2. Does every image have meaningful alt text?
3. Is there a visible focus indicator on every interactive element?
4. Does the page have one `<h1>` and do headings follow logical order?
5. Does every form input have a label?
6. Have I run the W3C validator and fixed all errors?
7. Have I run axe DevTools and fixed all Critical/Serious issues?