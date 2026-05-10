# Design Guidelines

## 🎨 UI/UX Standards
- **Framework**: Bootstrap 5.
- **Design Aesthetic**: Modern, clean, "Glassmorphism" elements.
- **Dark Mode**: Mandatory support using `[data-bs-theme=dark]`. Ensure all components (cards, tables, modals) are styled correctly for both light and dark themes.
- **Master Layout**: `resources/views/structure/master.blade.php`.
- **Icons**: Use FontAwesome or Bootstrap Icons consistently.

## 🍱 Component Design
- **Dashboard Cards**: Use interactive hover effects and standard CSS transitions.
- **Tables**: Use FlexSearch patterns for all searchable tables. Ensure tables are responsive.
- **Forms**: Use Bootstrap's grid system for multi-column form layouts.
- **Modals**: Use Bootstrap 5 modals for quick actions and small forms.

## 📱 Responsiveness
- All views MUST be mobile-friendly.
- Use Bootstrap's container-row-column grid system.
- Ensure large tables are wrapped in `.table-responsive`.

## 🎨 CSS Variables
- Use existing CSS variables for consistency:
    - `--primary-color`
    - `--bs-dashboard-accent`
    - (Refer to `master.blade.php` for the full list of variables)
