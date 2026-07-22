# Atlas ERP Design Analysis

Date: July 22, 2026

Source material reviewed:
- Published Figma Make application at `https://hut-mess-39404746.figma.site/`
- Attached screen recording thumbnail extracted from `Screen Recording 2026-07-22 at 2.16.58 AM.mov`

Scope of this document:
- Analyze the visual and structural patterns of the reference application
- Translate those patterns into an implementation-ready system for Atlas ERP
- Define phased implementation guidance before UI development continues

## 1. Navigation Structure

The reference uses a persistent application shell with three fixed navigation zones:

1. Brand block at top-left
- Product mark
- Product name
- Active company / tenant name

2. Grouped primary navigation in the left rail
- `Workspace`
- `Operations`
- `Finance`
- `System`

3. User identity block at bottom-left
- Initial-based avatar
- User full name
- Role title

Observed navigation items:
- Dashboard
- CRM
- Jobs
- Fleet
- Inventory
- Finance
- Reports
- Administration
- Settings
- System Health
- Design System

Structural pattern:
- Left rail is collapsible from full width to icon rail
- Labels are grouped with small uppercase section headers
- Active state is high-contrast and filled
- Inactive state is muted, with stronger color on hover
- Navigation is task-oriented, not database-oriented

Recommendation for Atlas ERP:
- Keep grouped navigation
- Treat the sidebar as the product backbone, not a Filament menu dump
- Separate business work from system configuration
- Keep tenant context visible in the shell at all times

## 2. Page Hierarchy

The reference follows a clear hierarchy:

1. App shell
- Sidebar
- Top command bar
- Main content canvas

2. Workspace page
- Page title / context
- Summary metrics
- Primary work surfaces
- Supporting side panels

3. Detail page
- Object header
- Status / metadata strip
- Summary cards
- Activity and related records
- Detailed tables or forms lower on the page

Observed page flow from the bundle:
- Dashboard
- CRM list
- Client view
- Client create
- Jobs list
- Job view
- Job create
- Settings
- System Health
- Design System
- Placeholder module pages for Fleet, Inventory, Finance, Reports, Administration

Hierarchy principle:
- Every screen answers in order:
  - Where am I?
  - What matters now?
  - What can I do next?

## 3. Design Tokens

The published stylesheet exposes a compact token set with a strong SaaS bias.

### Colors

Core neutrals:
- App background: `#F7F8FC`
- Primary text: `#0C0E1A`
- Secondary text: `#3B4163`
- Muted text: `#6B7389`
- Disabled / placeholder text: `#9CA3AF`
- Sidebar text muted: `#A8ADCC`
- Surface border: `#E2E5F0`
- Soft divider / alternate border: `#F1F3FA`
- Sidebar background: `#0C0E1A`
- Sidebar divider: `#1A1D2E`
- Surface: `#FFFFFF`

Brand / interactive:
- Primary action: `#2B4EFF`
- Primary hover: `#1E3FE8`
- Soft primary background: `#E8EDFF`

Status colors:
- Success: `#16A34A`
- Success tint: `#DCFCE7`
- Info: `#0284C7`
- Info tint: `#E0F2FE`
- Warning: `#D97706`
- Warning tint: `#FEF3C7`
- Danger: `#DC2626`
- Danger tint: `#FEE2E2`
- Accent / optional category tint: `#F3E8FF`

Interpretation:
- The palette is intentionally restrained
- Most contrast comes from layout, typography, and whitespace
- Color is reserved for action, state, and emphasis

### Spacing

Base spacing token:
- `4px`

Observed common steps:
- `4`
- `8`
- `12`
- `16`
- `20`
- `24`
- `32`
- `48`
- `80`

Spacing behavior:
- Dense micro-spacing inside chips and controls
- Moderate spacing within cards
- Large vertical separation between card groups
- Sidebar and header rely on tighter spacing than workspace content

Recommendation:
- Use a 4px scale
- Standardize page gutters and vertical rhythm before styling individual resources

### Typography

Fonts:
- Sans: `Plus Jakarta Sans`
- Mono: `Geist Mono`

Observed text sizes:
- `10px`
- `12px` (`text-xs`)
- `14px` (`text-sm`)
- `16px` (`text-base`)
- `18px` (`text-lg`)
- `20px` (`text-xl`)
- `24px` (`text-2xl`)
- `36px` (`text-4xl`)

Typography behavior:
- Small uppercase labels for system grouping
- Tight, bold titles
- Metric values are large but not oversized
- Secondary metadata is quiet and compact
- Mono is used for IDs, technical labels, and utility text

### Border Radius

Observed radii:
- `6px`
- `8px`
- `10px`
- `14px`
- `16px` equivalent (`2xl`)
- `18px`
- `9999px` for pills / avatars

Usage pattern:
- Small controls: `6px` to `8px`
- Buttons and inputs: `8px`
- Standard cards: `10px` to `14px`
- Feature cards / large surfaces: `16px` to `18px`
- Status pills and avatars: fully rounded

### Shadows

Observed shadows:
- Low elevation: `0 1px 4px rgba(12, 14, 26, 0.05)`
- Hover elevation: `0 4px 16px rgba(12, 14, 26, 0.08)`
- Overlay / modal elevation: `0 24px 64px rgba(12, 14, 26, 0.18)`

Interpretation:
- Shadows are subtle
- Separation comes more from border + radius + background contrast than from heavy blur

## 4. Reusable UI Components

The reference implies a consistent component library:

### Shell components
- Collapsible sidebar
- Sectioned navigation group
- Active nav item
- User identity footer
- Top command bar
- Search field with inline icon and shortcut hint
- Quick create button
- Notification button with badge
- Avatar button

### Summary components
- KPI metric card
- Status badge / pill
- Section header with supporting text
- Section header with trailing text action
- Secondary summary card

### Work surface components
- Content card
- Split layout card cluster
- Approval queue card
- Activity list
- Upcoming work list
- Workspace shortcut card
- Placeholder module card

### Data-display components
- Table card
- Inline metadata row
- Entity identity block
- Amount / monetary cell
- Empty state block
- Read-only audit item

### Input components
- Search input
- Primary button
- Secondary button
- Tertiary icon button
- Reject / approve paired action buttons
- Form field with compact label spacing

## 5. Workspace Layout Patterns

The strongest pattern in the reference is its shell-to-workspace composition.

### Global shell
- Fixed left sidebar
- Fixed top bar
- Scrollable main canvas
- Canvas background distinct from cards

### Workspace page pattern
- Compact workspace heading at top of content
- Immediate summary row beneath heading
- Primary grid beneath summary
- Right column used for action-oriented or exception-oriented content
- Left column used for deeper, operational content

### Detail workspace pattern
- Entity header first
- Small cluster of summary cards second
- Supporting modules after that
- Detailed table or relationship panels last

### Rhythm pattern
- Large card groups
- Clear gutters
- Minimal prose
- One main action per region
- Supporting actions visually subordinate

Recommendation for Atlas ERP:
- Build page scaffolds first
- Then fit Filament resources into those scaffolds
- Avoid table-first resources as the default visual entry point

## 6. Dashboard Structure

The observed dashboard pattern is:

1. Shell and top command bar
- Search field
- Quick create
- Notifications
- User avatar

2. KPI strip
- Total Clients
- Active Jobs
- Pending Approvals
- Jobs Today
- Completed MTD
- Revenue MTD

3. Primary content split
- Left large operational card: `Upcoming Work`
- Right action / exception card: `Approval Queue`

4. Secondary content
- `Recent Activity`

Design traits:
- The dashboard is operational, not explanatory
- Metrics are skim-first
- Lists are compact but breathable
- Approvals are visible as a first-class workflow, not buried in tables

Implications for Atlas ERP:
- Dashboard should prioritize live work, pending decisions, and exceptions
- KPI row should stay visually balanced
- Right column should hold fast action surfaces and risk surfaces
- Recent activity belongs below the first decision surfaces, not above them

## 7. Table Patterns

The screen recording frame shows a strong table-like list pattern inside cards.

Observed traits:
- Tables live inside large rounded surfaces
- Rows have generous height
- Primary label appears first
- Secondary metadata sits directly under the title
- Time, crew count, status, or amount align to the right
- Rows are divided by soft borders, not dense gridlines
- Status appears as tinted pill, not plain text
- Headings include a view-all action

Recommended Atlas ERP table pattern:
- Wrap every table in a workspace card
- Use padded header region above the actual grid
- Convert important columns into title + metadata stacks
- Use status chips for lifecycle state
- Use smaller mono text for job codes, invoice IDs, references
- Prefer right-aligned numeric columns
- Promote useful filters into the card header
- Design empty states as destination cards, not blank tables

## 8. Form Patterns

The reference is not form-heavy in the visible frame, but the bundle structure and shell conventions still suggest the intended form language.

Expected form behavior:
- Forms should appear as workspace tasks, not admin forms
- Inputs should sit in cards or grouped panels
- One primary action should dominate
- Secondary actions should be quieter
- Labels should be short and close to fields
- Supporting help text should be sparse

Recommended Atlas ERP form pattern:
- Start with a workspace header
- Follow with grouped form sections
- Use two-column layouts for desktop where appropriate
- Keep record identity and status visible during edit flows
- Put destructive actions in a separate danger region
- Use sticky or consistently placed save actions
- Prefer structured sections:
  - Overview
  - Contacts / ownership
  - Scheduling / operations
  - Financial placeholders
  - Audit / system metadata

## 9. Components That Should Be Shared Across The Application

The following components should become shared application primitives:

### Shell
- App sidebar
- Navigation section label
- Navigation item
- Top command bar
- Global search input
- Notification trigger
- User identity chip

### Workspace primitives
- Workspace header
- Workspace subheading
- Metric card
- Summary card
- Section card
- Section header with action slot
- Empty state card
- Split content grid

### Data primitives
- Status badge
- Currency metric block
- Activity timeline item
- Approval item
- Record identity stack
- Metadata pair
- Workspace table wrapper

### Action primitives
- Quick action card
- Inline row actions
- Primary CTA
- Secondary CTA
- Destructive CTA

### Forms
- Form section shell
- Fieldset card
- Inline validation text
- Sticky action footer

### Cross-cutting utilities
- Tokenized spacing classes / config
- Shared icon size rules
- Standard content widths
- Standard card padding rules

## 10. Implementation Plan

Implementation should happen in phases so the product architecture improves before visual polish expands.

### Phase 1: Foundation tokens and shell

Goal:
- Create the shared visual language once

Work:
- Define app tokens for color, spacing, typography, radius, and elevation
- Standardize page background, surface backgrounds, and divider colors
- Rebuild the Filament shell styling into a product shell
- Implement sidebar groups, active states, top command bar, and user footer
- Introduce shared workspace header component

Deliverable:
- One consistent shell across all authenticated pages

### Phase 2: Dashboard architecture

Goal:
- Replace widget clutter with a real operations cockpit

Work:
- Build a KPI metric row
- Build the main dashboard split layout
- Add operational list cards
- Add approval / alerts / quick action surfaces
- Normalize section spacing and card heights

Deliverable:
- A dashboard that is useful within three seconds of load

### Phase 3: Shared page scaffolds

Goal:
- Stop table-first layouts across all entity pages

Work:
- Create shared scaffold patterns for:
  - List pages
  - Detail pages
  - Create pages
  - Edit pages
- Add summary-card regions ahead of tables
- Move filters, search, and CTA placement into a standard header structure

Deliverable:
- Consistent information hierarchy across resources

### Phase 4: Table and list system

Goal:
- Make operational data readable and product-grade

Work:
- Wrap tables in workspace cards
- Increase row height and header padding
- Redesign empty states
- Standardize chips, inline metadata, and right-aligned numeric columns
- Improve quick filtering and contextual search placement

Deliverable:
- Reusable Atlas ERP table pattern across CRM, Jobs, Companies, and Settings

### Phase 5: Form system

Goal:
- Turn create/edit flows into structured workspaces

Work:
- Create form section cards
- Introduce layout conventions for overview, ownership, operational dates, and financial placeholders
- Standardize save, cancel, destructive, and secondary actions
- Improve inline validation and supporting text

Deliverable:
- Cleaner create/edit flows with predictable structure

### Phase 6: Module rollout

Goal:
- Apply the system module by module

Priority order:
1. Dashboard
2. Clients
3. Jobs
4. Companies
5. Settings
6. System Health
7. Roles / administration views

Deliverable:
- Incremental visual convergence without destabilizing the platform

### Phase 7: Refinement and QA

Goal:
- Ensure the system feels coherent, not just styled

Work:
- Visual QA across desktop and mobile
- Check spacing rhythm and component consistency
- Verify contrast and accessibility
- Verify Filament states still behave correctly
- Tune loading, empty, error, and success states

Deliverable:
- Production-ready UI system with consistent behavior

## Key Design Conclusions

The reference is successful because it does not behave like an admin generator. It behaves like an operations product.

The main qualities to preserve in Atlas ERP are:
- clear shell hierarchy
- grouped navigation
- compact but high-signal metrics
- large breathable cards
- right-rail decision surfaces
- table-in-card presentation
- restrained use of color
- minimal prose
- strong tenant and user context

The main mistake to avoid is styling isolated widgets without first standardizing page architecture. The shell, workspace header, summary cards, and table wrappers should become the new foundation before module-by-module implementation begins.
