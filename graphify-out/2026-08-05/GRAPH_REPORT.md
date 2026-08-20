# Graph Report - manajemen-gmf  (2026-08-05)

## Corpus Check
- 273 files · ~319,540 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 2063 nodes · 2748 edges · 269 communities (234 shown, 35 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 72 edges (avg confidence: 0.73)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `daa51925`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- ApprovalCenterController.php
- gray
- search
- AttendanceVerification
- color
- button
- slide_search_core.py
- Illuminate\Database\Eloquent\Factories\HasFactory
- spacing
- Coach
- TestTailwindConfigGenerator
- html-token-validator.py
- BM25
- package.json
- ClassSchedule
- Illuminate\Http\Request
- User
- Product
- generate-slide.py
- TailwindConfigGenerator
- design_system.py
- main
- DesignSystemGenerator
- fetch-background.py
- TestShadcnInstaller
- icon/generate.py
- fontSize
- .add_components
- extract-colors.cjs
- validate-asset.cjs
- ShadcnInstaller
- StaffInvitation
- composer.json
- scripts
- MembershipPackage
- validate-tokens.cjs
- test_tailwind_config_gen.py
- Member
- banner-design/references/banner-sizes-and-styles.md
- banner-design/SKILL.md
- approval-checklist.md
- asset-organization.md
- brand-guideline-template.md
- consistency-checklist.md
- update.md
- visual-identity.md
- voice-framework.md
- brand/SKILL.md
- brand-guidelines-starter.md
- design/references/banner-sizes-and-styles.md
- cip-design.md
- cip-prompt-engineering.md
- cip-style-guide.md
- design-routing.md
- icon-design.md
- logo-design.md
- logo-prompt-engineering.md
- slides-copywriting-formulas.md
- slides-layout-patterns.md
- slides-strategies.md
- social-photos-design.md
- design/SKILL.md
- design-system/SKILL.md
- shadcn-accessibility.md
- shadcn-components.md
- shadcn-theming.md
- tailwind-customization.md
- tailwind-responsive.md
- tailwind-utilities.md
- scripts/requirements.txt
- ui-styling/SKILL.md
- ui-ux-pro-max/SKILL.md
- inject-brand-context.cjs
- embed-tokens.cjs
- primitive
- patch
- search
- spesifikasi-sim-fitness-center.md
- color-palette-management.md
- logo-usage-rules.md
- messaging-framework.md
- typography-specifications.md
- cip-deliverable-guide.md
- logo-color-psychology.md
- logo-style-guide.md
- primitive-tokens.md
- semantic-tokens.md
- states-and-variants.md
- tailwind-integration.md
- token-architecture.md
- copywriting-formulas.md
- layout-patterns.md
- slide-strategies.md
- canvas-design-system.md
- logo/generate.py
- generate-tokens.cjs
- ._base_config
- ClassBooking
- component-tokens.md
- sync-brand-to-tokens.cjs
- _run
- BM25
- component-specs.md
- radius
- .generate_config_string
- format_ascii_box
- CheckMemberActive.php
- require
- require-dev
- setup
- README.md
- slides-html-template.md
- slides.md
- html-template.md
- ScanQrController
- config
- slides/SKILL.md
- shadow
- AppServiceProvider
- TestCase
- lg
- psr-4
- MembershipRenewal
- md
- xl
- RegistrationController
- Illuminate\Database\Seeder
- ExampleTest
- test_sync_brand_to_tokens.py
- main
- .__init__
- Controller
- slides-create.md
- create.md
- workflows/graphify.md
- .test_add_components_no_config
- .test_add_components_dry_run
- .test_list_installed_no_config
- .test_init_dry_run
- .test_add_components_no_components
- .test_recommend_plugins
- .test_recommend_plugins_nextjs
- .test_init_default_typescript
- .test_generate_javascript_config
- .test_generate_config_with_colors
- .test_validate_config_valid
- .test_write_config_invalid_path
- .test_full_configuration_typescript
- .test_base_config_structure
- .test_default_content_paths_react
- App\Http\Controllers\Coach
- step1.blade.php
- step2.blade.php
- step3.blade.php
- step4.blade.php
- extra
- CheckMembershipExpiry.php
- MidtransService
- .syncFromBookings
- none
- test

## God Nodes (most connected - your core abstractions)
1. `Controller` - 61 edges
2. `TailwindConfigGenerator` - 58 edges
3. `Member` - 53 edges
4. `TestTailwindConfigGenerator` - 35 edges
5. `ShadcnInstaller` - 34 edges
6. `Coach` - 33 edges
7. `User` - 32 edges
8. `AttendanceVerification` - 29 edges
9. `ClassSchedule` - 28 edges
10. `TestShadcnInstaller` - 26 edges

## Surprising Connections (you probably didn't know these)
- `TestShadcnInstaller` --uses--> `ShadcnInstaller`  [INFERRED]
  .agents/skills/ui-styling/scripts/tests/test_shadcn_add.py → .agents/skills/ui-styling/scripts/shadcn_add.py
- `TestGeneratedConfigIsValidJs` --uses--> `TailwindConfigGenerator`  [INFERRED]
  .agents/skills/ui-styling/scripts/tests/test_tailwind_config_gen.py → .agents/skills/ui-styling/scripts/tailwind_config_gen.py
- `TestTailwindConfigGenerator` --uses--> `TailwindConfigGenerator`  [INFERRED]
  .agents/skills/ui-styling/scripts/tests/test_tailwind_config_gen.py → .agents/skills/ui-styling/scripts/tailwind_config_gen.py
- `_generate_intelligent_overrides()` --calls--> `search()`  [EXTRACTED]
  .agents/skills/ui-ux-pro-max/scripts/design_system.py → .agents/skills/ui-ux-pro-max/scripts/core.py
- `ApprovalCenterController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/ApprovalCenterController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (269 total, 35 thin omitted)

### Community 0 - "ApprovalCenterController.php"
Cohesion: 0.14
Nodes (11): MemberApprovedMail, MemberRejectedMail, MembershipReminderMail, OtpMail, StaffInvitationMail, Illuminate\Bus\Queueable, Illuminate\Contracts\Queue\ShouldQueue, Illuminate\Mail\Mailable (+3 more)

### Community 1 - "gray"
Cohesion: 0.05
Nodes (53): $type, $value, $type, $value, $type, $value, $type, $value (+45 more)

### Community 2 - "search"
Cohesion: 0.07
Nodes (42): BM25, detect_domain(), get_cip_brief(), _load_csv(), Load CSV and return list of dicts, Core search function using BM25, Auto-detect the most relevant domain from query, Main search function with auto-domain detection (+34 more)

### Community 3 - "AttendanceVerification"
Cohesion: 0.14
Nodes (3): AttendanceVerificationController, DashboardController, AttendanceVerification

### Community 4 - "color"
Cohesion: 0.04
Nodes (48): $type, $value, background, destructive, destructive-foreground, foreground, muted, muted-foreground (+40 more)

### Community 5 - "button"
Cohesion: 0.06
Nodes (45): $type, $value, $type, $value, bg, fg, font-size, hover-bg (+37 more)

### Community 6 - "slide_search_core.py"
Cohesion: 0.09
Nodes (36): format_context(), format_result(), main(), Format a single search result for display, Format contextual recommendations for display., BM25, calculate_pattern_break(), detect_domain() (+28 more)

### Community 7 - "Illuminate\Database\Eloquent\Factories\HasFactory"
Cohesion: 0.10
Nodes (9): DashboardController, DashboardController, MembershipDocument, OtpCode, ProductSale, Visit, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model (+1 more)

### Community 8 - "spacing"
Cohesion: 0.06
Nodes (34): $type, $value, $type, $value, $type, $value, $type, $value (+26 more)

### Community 9 - "Coach"
Cohesion: 0.10
Nodes (6): CoachManagementController, PayrollController, CommissionController, TeachingHistoryController, Coach, Payroll

### Community 10 - "TestTailwindConfigGenerator"
Cohesion: 0.07
Nodes (15): Test adding colors multiple times., Test adding full color palette., Test adding custom breakpoints., Test TailwindConfigGenerator class., Test generating TypeScript configuration., Test generating config with plugins., Test validating config with no content paths., Test validating config with empty theme extensions. (+7 more)

### Community 11 - "html-token-validator.py"
Cohesion: 0.14
Nodes (24): get_context(), is_allowed_exception(), is_allowed_rgba(), is_inside_block(), load_css_variables(), main(), print_result(), print_summary() (+16 more)

### Community 12 - "BM25"
Cohesion: 0.12
Nodes (19): BM25, detect_domain(), _load_csv(), Load CSV and return list of dicts, Core search function using BM25, Auto-detect the most relevant domain from query, Main search function with auto-domain detection, Search across all domains and combine results (+11 more)

### Community 13 - "package.json"
Cohesion: 0.08
Nodes (24): alpinejs, concurrently, laravel-echo, laravel-vite-plugin, dependencies, alpinejs, laravel-echo, pusher-js (+16 more)

### Community 14 - "ClassSchedule"
Cohesion: 0.14
Nodes (3): ClassScheduleController, ClassSchedule, ClassType

### Community 15 - "Illuminate\Http\Request"
Cohesion: 0.16
Nodes (5): LandingPageContentController, ForgotPasswordController, ReportController, LandingPageContent, Illuminate\Http\Request

### Community 16 - "User"
Cohesion: 0.15
Nodes (4): StaffAccountController, User, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable

### Community 18 - "generate-slide.py"
Cohesion: 0.15
Nodes (19): _e(), generate_chart_slide(), generate_cta_slide(), generate_deck(), generate_metrics_slide(), generate_problem_slide(), generate_solution_slide(), generate_testimonial_slide() (+11 more)

### Community 19 - "TailwindConfigGenerator"
Cohesion: 0.10
Nodes (11): Generate Tailwind CSS configuration files., Add full color palette (50-950 shades) for a base color. Args: name: Color name…, TailwindConfigGenerator, Test adding custom fonts., Test adding custom spacing., Test that adding same plugin twice doesn't duplicate., Test initialization for JavaScript config., Test initialization with different frameworks. (+3 more)

### Community 20 - "design_system.py"
Cohesion: 0.15
Nodes (18): _detect_page_type(), format_markdown(), format_master_md(), format_page_override_md(), generate_design_system(), _generate_intelligent_overrides(), persist_design_system(), Format a page-specific override file with intelligent AI-generated content. (+10 more)

### Community 21 - "main"
Cohesion: 0.13
Nodes (8): main(), Add custom font families. Args: fonts: Dict of font_type: [font_names] e.g.,…, Add custom spacing values. Args: spacing: Dict of name: value e.g., {'18':…, Add custom breakpoints. Args: breakpoints: Dict of name: width e.g., {'3xl':…, Add plugin requirements. Args: plugins: List of plugin names e.g.,…, Get plugin recommendations based on configuration. Returns: List of recommended…, Validate configuration. Returns: Tuple of (valid, message), Add custom colors to theme. Args: colors: Dict of color_name: color_value Value…

### Community 22 - "DesignSystemGenerator"
Cohesion: 0.14
Nodes (11): DesignSystemGenerator, Find matching reasoning rule for a category., Apply reasoning rules to search results., Select best matching result based on priority keywords., Extract results list from search result dict., Generate complete design system recommendation. variance/motion/density are…, Bucket a 1-10 dial value into its tier config. Returns None if value is None., Generates design system recommendations from aggregated searches. (+3 more)

### Community 23 - "fetch-background.py"
Cohesion: 0.17
Nodes (17): generate_css_for_background(), get_background_image(), get_curated_images(), get_overlay_css(), get_pexels_search_url(), load_backgrounds_config(), load_brand_colors(), main() (+9 more)

### Community 24 - "TestShadcnInstaller"
Cohesion: 0.12
Nodes (10): Test ShadcnInstaller class., Test adding all components without config., Test adding all components in dry run mode., Create temporary project structure., Test listing installed components when none exist., Test listing installed components when they exist., Test checking for existing shadcn config., Test getting installed components without config. (+2 more)

### Community 25 - "icon/generate.py"
Cohesion: 0.20
Nodes (15): apply_color(), apply_viewbox_size(), extract_svgs(), generate_batch(), generate_icon(), generate_sizes(), load_env(), main() (+7 more)

### Community 26 - "fontSize"
Cohesion: 0.12
Nodes (16): $type, $value, $type, $value, $type, $value, $type, $value (+8 more)

### Community 27 - ".add_components"
Cohesion: 0.17
Nodes (8): main(), Add all available shadcn/ui components. Args: overwrite: If True, overwrite…, List installed components. Returns: Tuple of (success, message with component…, Check if shadcn is initialized in project. Returns: True if components.json…, Get list of already installed components. Returns: List of installed component…, Read shadcn version from project package.json; fall back to a pinned default., Add shadcn/ui components. Args: components: List of component names to add…, Tests for shadcn_add.py

### Community 28 - "extract-colors.cjs"
Cohesion: 0.22
Nodes (11): calculateCompliance(), colorDistance(), displayPalette(), extractHexColors(), findNearestBrandColor(), fs, generateImageMagickCommand(), hexToRgb() (+3 more)

### Community 29 - "validate-asset.cjs"
Cohesion: 0.25
Nodes (13): checkManifest(), formatBytes(), formatOutput(), fs, main(), parseFilename(), path, RULES (+5 more)

### Community 30 - "ShadcnInstaller"
Cohesion: 0.14
Nodes (8): Handle shadcn/ui component installation., ShadcnInstaller, Test adding components that are already installed., Test initialization with default project root., Test initialization with custom project root., Test checking for non-existent shadcn config., Test getting installed components when none exist., Test getting installed components when files exist.

### Community 31 - "StaffInvitation"
Cohesion: 0.11
Nodes (9): CheckExpiry, GenerateAttendanceVerifications, GenerateVerifications, StaffRegistrationController, MidtransWebhookController, StaffInvitation, Carbon, Carbon\Carbon (+1 more)

### Community 32 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 33 - "scripts"
Cohesion: 0.13
Nodes (15): scripts, dev, post-autoload-dump, post-create-project-cmd, post-update-cmd, pre-package-uninstall, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+7 more)

### Community 35 - "validate-tokens.cjs"
Cohesion: 0.24
Nodes (11): extensions, formatReport(), fs, getFiles(), main(), parseArgs(), path, patterns (+3 more)

### Community 36 - "test_tailwind_config_gen.py"
Cohesion: 0.20
Nodes (8): Tests for tailwind_config_gen.py, Reduce a generated TS/JS config to a bare assignable object so it can be handed…, Regression guard for the missing-comma bug between the ``theme`` block and…, The property preceding ``plugins`` must end with a comma (pure-Python check, so…, The emitted config parses as valid JS via ``node --check``., _strip_to_object(), TestGeneratedConfigIsValidJs, parametrize

### Community 38 - "banner-design/references/banner-sizes-and-styles.md"
Cohesion: 0.18
Nodes (10): 22 Art Direction Styles, Banner Sizes & Art Direction Styles Reference, Complete Banner Sizes, Design Principles, Print, Safe Zones, Social Media, Visual Hierarchy (3-Zone Rule) (+2 more)

### Community 39 - "banner-design/SKILL.md"
Cohesion: 0.18
Nodes (10): Banner Design - Multi-Format Creative Banner System, Banner Size Quick Reference, Prerequisites, Step 1: Gather Requirements (AskUserQuestion), Step 2: Research & Art Direction, Step 3: Design & Generate Options, Step 4: Export Banners to Images, Step 5: Present Options & Iterate (+2 more)

### Community 40 - "approval-checklist.md"
Cohesion: 0.18
Nodes (10): Accessibility, Asset Approval Checklist, Color Compliance, Content Accessibility, Imagery, Logo Usage, Quick Review, Typography (+2 more)

### Community 41 - "asset-organization.md"
Cohesion: 0.18
Nodes (10): Asset Entry (manifest.json), Asset Organization Guide, Components, Directory Structure, Examples, Format, Metadata Schema, Naming Convention (+2 more)

### Community 42 - "brand-guideline-template.md"
Cohesion: 0.18
Nodes (10): 1. Color Palette, 2. Typography, Accessibility, Brand Guidelines Template, Brand Guidelines v{X.Y}, Document Structure, Neutral Palette, Primary Colors (+2 more)

### Community 43 - "consistency-checklist.md"
Cohesion: 0.18
Nodes (10): Brand Consistency Checklist, Colors, Imagery, Language, Logo, Messaging, Tone, Typography (+2 more)

### Community 44 - "update.md"
Cohesion: 0.18
Nodes (10): Check brand context extraction, Check CSS variables, Files Modified, Overview, Step 1: Gather Brand Input, Step 2: Update Brand Guidelines, Step 3: Sync to Design Tokens, Step 4: Verify Sync (+2 more)

### Community 45 - "visual-identity.md"
Cohesion: 0.18
Nodes (10): Color Palette, Color Specifications, Core Visual Elements, Correct Usage, Incorrect Usage, Logo, Logo Usage, Typography (+2 more)

### Community 46 - "voice-framework.md"
Cohesion: 0.18
Nodes (10): Brand Voice Framework, Character Spectrum, Emotion Spectrum, Language Spectrum, Step 1: Define Personality Traits, Step 2: Create Voice Chart, Tone Spectrum, Voice Development Process (+2 more)

### Community 47 - "brand/SKILL.md"
Cohesion: 0.18
Nodes (10): 1. Edit docs/brand-guidelines.md (or use /brand update), 2. Sync to design tokens, 3. Verify, Brand, Brand Sync Workflow, Quick Start, References, Scripts (+2 more)

### Community 48 - "brand-guidelines-starter.md"
Cohesion: 0.18
Nodes (10): 1. Color Palette, 2. Typography, Accessibility, Brand Guidelines v1.0, Font Stack, Neutral Palette, Primary Colors, Quick Reference (+2 more)

### Community 49 - "design/references/banner-sizes-and-styles.md"
Cohesion: 0.18
Nodes (10): 22 Art Direction Styles, Banner Sizes & Art Direction Styles Reference, Complete Banner Sizes, Design Principles, Print, Safe Zones, Social Media, Visual Hierarchy (3-Zone Rule) (+2 more)

### Community 50 - "cip-design.md"
Cohesion: 0.18
Nodes (10): CIP Brief (Start Here), CIP Design Reference, Commands, Deliverables, Design styles, Generate Mockups, Industry guidelines, Mockup contexts (+2 more)

### Community 51 - "cip-prompt-engineering.md"
Cohesion: 0.18
Nodes (10): Apparel (Polo/T-Shirt), Base Prompt Structure, Business Card, CIP Mockup Prompt Engineering, Corporate Minimal, Deliverable-Specific Modifiers, Letterhead, Office Signage (+2 more)

### Community 52 - "cip-style-guide.md"
Cohesion: 0.18
Nodes (10): Bold Dynamic, CIP Design Style Guide, Classic Traditional, Color Psychology, Corporate Minimal, Fresh Modern, Luxury Premium, Modern Tech (+2 more)

### Community 53 - "design-routing.md"
Cohesion: 0.18
Nodes (10): Banner Design Tasks, Brand Identity Tasks, Corporate Identity Program Tasks, Design Routing Guide, Implementation Tasks, Logo Design Tasks, Presentation Tasks, Routing by Task Type (+2 more)

### Community 54 - "icon-design.md"
Cohesion: 0.18
Nodes (10): Available Styles, CLI Options, Commands, Generate Batch Variations, Generate Multiple Sizes, Generate Single Icon, Icon Categories, Icon Design Reference (+2 more)

### Community 55 - "logo-design.md"
Cohesion: 0.18
Nodes (10): Available Styles, Color palettes, Commands, Design Brief (Start Here), Generate Logo, Industry guidelines, Logo Design Reference, Scripts (+2 more)

### Community 56 - "logo-prompt-engineering.md"
Cohesion: 0.18
Nodes (10): Core Prompt Structure, Effective Keywords by Style, Logo AI Prompt Engineering, Luxury/Premium, Minimalist, Modern/Tech, Negative Prompts (What to Avoid), Organic/Natural (+2 more)

### Community 57 - "slides-copywriting-formulas.md"
Cohesion: 0.18
Nodes (10): AIDA (Attention-Interest-Desire-Action), Before-After-Bridge, Copywriting Formulas, Core Formulas, Cost of Inaction, FAB (Features-Advantages-Benefits), Formula-to-Slide Mapping, Headline Patterns (+2 more)

### Community 58 - "slides-layout-patterns.md"
Cohesion: 0.18
Nodes (10): Card Styles, Component Variants, CSS Structures, Feature Grid (3 columns), Layout Patterns, Layout Selection by Use Case, Metric Styles, Metrics Dashboard (4 columns) (+2 more)

### Community 59 - "slides-strategies.md"
Cohesion: 0.18
Nodes (10): Common Structures, Duarte Sparkline Pattern, Find strategy by goal, Get emotion arc, Product Demo (6 slides), Sales Pitch (9 slides), Search Commands, Slide Strategies (+2 more)

### Community 60 - "social-photos-design.md"
Cohesion: 0.18
Nodes (10): Platform Sizes, Social Photos Design Guide, Step 1: Activate Project Management, Step 2: Analyze Requirements, Step 3: Generate Ideas, Step 4: Design HTML Files, Step 5: Screenshot Export, Step 6: Verify & Fix Designs (+2 more)

### Community 61 - "design/SKILL.md"
Cohesion: 0.18
Nodes (10): CIP: Generate Brief, CIP: Search Domains, CIP Design (Built-in), Design, Logo: Generate Design Brief, Logo: Generate with AI, Logo: Search Styles/Colors/Industries, Logo Design (Built-in) (+2 more)

### Community 62 - "design-system/SKILL.md"
Cohesion: 0.18
Nodes (10): Component Spec Pattern, Design System, Integration, Quick Start, References, Scripts, Templates, Three-Layer Structure (+2 more)

### Community 63 - "shadcn-accessibility.md"
Cohesion: 0.18
Nodes (10): ARIA Labels, Command Palette Navigation, Dialog/Modal Navigation, Dropdown/Menu Navigation, Focus Management, Foundation: Radix UI Primitives, Keyboard Navigation, Screen Reader Support (+2 more)

### Community 64 - "shadcn-components.md"
Cohesion: 0.18
Nodes (10): Button, Checkbox, Form & Input Components, Form (with React Hook Form + Zod), Input, Installation, Radio Group, Select (+2 more)

### Community 65 - "shadcn-theming.md"
Cohesion: 0.18
Nodes (10): Color Customization, Color Format, CSS Variable System, Dark Mode Setup, Method 1: Update CSS Variables, Method 2: Theme Generator, Next.js App Router, shadcn/ui Theming & Customization (+2 more)

### Community 66 - "tailwind-customization.md"
Cohesion: 0.18
Nodes (10): @theme Directive, Color Customization, Custom Color Palette, Custom Font Sizes, Custom Fonts, Custom Utilities, Semantic Colors, Spacing Customization (+2 more)

### Community 67 - "tailwind-responsive.md"
Cohesion: 0.18
Nodes (10): Breakpoint System, Common Responsive Layouts, Layout Changes, Mobile-First Approach, Responsive Patterns, Spacing, Tailwind CSS Responsive Design, Typography (+2 more)

### Community 68 - "tailwind-utilities.md"
Cohesion: 0.18
Nodes (10): Display, Flexbox, Grid, Layout Utilities, Margin, Padding, Positioning, Spacing Utilities (+2 more)

### Community 69 - "scripts/requirements.txt"
Cohesion: 0.18
Nodes (10): - Node.js 18+: https://nodejs.org/, - npm (comes with Node.js), # shadcn/ui CLI is installed per-project:, No Python package dependencies - uses only standard library, Note: This skill works with shadcn/ui and Tailwind CSS, npx shadcn-ui@latest init, Python 3.10+ required, Requires Node.js and package managers: (+2 more)

### Community 70 - "ui-styling/SKILL.md"
Cohesion: 0.18
Nodes (10): Alternative: Tailwind-Only Setup, Component + Styling Setup, Component Layer: shadcn/ui, Core Stack, Quick Start, Reference, Styling Layer: Tailwind CSS, UI Styling Skill (+2 more)

### Community 71 - "ui-ux-pro-max/SKILL.md"
Cohesion: 0.18
Nodes (10): How to Use This Skill, Prerequisites, Search Reference, Step 1: Analyze User Requirements, Step 2: Generate Design System (REQUIRED), Step 2b: Persist Design System (Master + Overrides Pattern), Step 2c: Design Dials (optional), Step 3: Supplement with Detailed Searches (as needed) (+2 more)

### Community 72 - "inject-brand-context.cjs"
Cohesion: 0.31
Nodes (10): extractColorsFromTable(), extractCoreAttributes(), extractHexColors(), extractImageStyle(), extractTypography(), extractVoice(), fs, generatePromptAddition() (+2 more)

### Community 73 - "embed-tokens.cjs"
Cohesion: 0.18
Nodes (8): args, fs, minimal, MINIMAL_TOKENS, path, projectRoot, tokensPath, wrapStyle

### Community 74 - "primitive"
Cohesion: 0.18
Nodes (11): fast, normal, slow, $type, $value, $type, $value, primitive (+3 more)

### Community 75 - "patch"
Cohesion: 0.18
Nodes (6): Test adding components with overwrite flag., Test successful component addition., Test component addition with subprocess error., Test component addition when npx is not found., Test successful addition of all components., patch

### Community 76 - "search"
Cohesion: 0.25
Nodes (10): detect_domain(), _load_csv(), Load CSV and return list of dicts, Core search function using BM25, Auto-detect the most relevant domain from query, Main search function with auto-domain detection, Search stack-specific guidelines, search() (+2 more)

### Community 77 - "spesifikasi-sim-fitness-center.md"
Cohesion: 0.18
Nodes (10): 1. Overview, 2. Autentikasi (berlaku untuk semua aktor), 3.1 QR Code, 3.2 Presensi Kelas vs Presensi Gym, 3.3 Pembayaran Kelas (Model B — Bayar di Tempat), 3.4 Verifikasi Kehadiran Coach, 3.5 Registrasi Member (Self-Service), 3.6 Approval Center (Gabungan) (+2 more)

### Community 78 - "color-palette-management.md"
Cohesion: 0.22
Nodes (9): Accessibility Requirements, Checking Contrast, Color Documentation Format, Color System Structure, Contrast Ratios (WCAG 2.1), CSS Variables, Hierarchy, Markdown Table (+1 more)

### Community 79 - "logo-usage-rules.md"
Cohesion: 0.22
Nodes (9): Clear Space, Color Usage, Color Variants, Digital, Logo Variants, Minimum Clear Space, Minimum Size, Primary Variants (+1 more)

### Community 80 - "messaging-framework.md"
Cohesion: 0.22
Nodes (9): Core Statements, Framework Structure, Message Architecture, Mission Statement, Positioning Statement, Primary Message, Supporting Messages (3-5), Value Proposition (+1 more)

### Community 81 - "typography-specifications.md"
Cohesion: 0.22
Nodes (9): Base System, Font Loading, Font Stack Structure, Font Weights, Primary Fonts, Responsive Adjustments, Scale Definition, Type Scale (+1 more)

### Community 82 - "cip-deliverable-guide.md"
Cohesion: 0.22
Nodes (9): Business Card, Core Identity, Envelope, Letterhead, Logo Variations, Office Environment, Primary Logo, Reception Signage (+1 more)

### Community 83 - "logo-color-psychology.md"
Cohesion: 0.22
Nodes (9): Black, Blue, Green, Orange, Primary Color Meanings, Purple, Red, White (+1 more)

### Community 84 - "logo-style-guide.md"
Cohesion: 0.22
Nodes (9): 1. Wordmark (Logotype), 2. Lettermark (Monogram), 3. Pictorial Mark (Brand Mark), 4. Abstract Mark, 5. Mascot, 6. Emblem, 7. Combination Mark, Aesthetic Styles (+1 more)

### Community 85 - "primitive-tokens.md"
Cohesion: 0.22
Nodes (9): Border Radius, Color Scales, Gray Scale, Motion / Duration, Primary Colors (Blue), Shadows, Spacing Scale, Status Colors (+1 more)

### Community 86 - "semantic-tokens.md"
Cohesion: 0.22
Nodes (9): Accent, Background & Foreground, Border & Ring, Color Semantics, Destructive, Muted, Primary, Secondary (+1 more)

### Community 87 - "states-and-variants.md"
Cohesion: 0.22
Nodes (9): Disabled States, Focus Ring Spec, Focus States, Focus Within, Interactive States, State Definitions, State Priority, State Transitions (+1 more)

### Community 88 - "tailwind-integration.md"
Cohesion: 0.22
Nodes (9): Animation Tokens, Base Layer, Button Example, Component Classes, CSS Variables Setup, HSL Format Benefits, Spacing Integration, Tailwind Config (+1 more)

### Community 89 - "token-architecture.md"
Cohesion: 0.22
Nodes (9): Categories, Dark Mode, File Organization, Layer 1: Primitive Tokens, Layer 2: Semantic Tokens, Layer 3: Component Tokens, Layer Overview, Naming Convention (+1 more)

### Community 90 - "copywriting-formulas.md"
Cohesion: 0.22
Nodes (9): AIDA (Attention-Interest-Desire-Action), Before-After-Bridge, Core Formulas, Cost of Inaction, FAB (Features-Advantages-Benefits), Formula-to-Slide Mapping, Headline Patterns, PAS (Problem-Agitate-Solution) (+1 more)

### Community 91 - "layout-patterns.md"
Cohesion: 0.22
Nodes (9): Card Styles, Component Variants, CSS Structures, Feature Grid (3 columns), Layout Selection by Use Case, Metric Styles, Metrics Dashboard (4 columns), Title Slide (+1 more)

### Community 92 - "slide-strategies.md"
Cohesion: 0.22
Nodes (9): Common Structures, Duarte Sparkline Pattern, Find strategy by goal, Get emotion arc, Product Demo (6 slides), Sales Pitch (9 slides), Search Commands, Strategy Selection (+1 more)

### Community 93 - "canvas-design-system.md"
Cohesion: 0.22
Nodes (9): 1. Visual Communication First, 2. Minimal Text Integration, 3. Expert Craftsmanship, 4. Systematic Patterns, Core Principles, Design Movement Examples, Design Philosophy Approach, Phase 1: Design Philosophy Creation (+1 more)

### Community 94 - "logo/generate.py"
Cohesion: 0.29
Nodes (9): enhance_prompt(), generate_batch(), generate_logo(), load_env(), main(), Enhance the logo prompt with style and industry modifiers, Generate a logo using Gemini models with image generation Args: aspect_ratio:…, Generate multiple logo variants with different styles (+1 more)

### Community 95 - "generate-tokens.cjs"
Cohesion: 0.36
Nodes (9): flattenTokens(), fs, generateCSS(), generateTailwind(), main(), parseArgs(), path, resolveReference() (+1 more)

### Community 96 - "._base_config"
Cohesion: 0.22
Nodes (6): Path, Initialize generator. Args: typescript: If True, generate .ts config, else .js…, Determine default output path., Create base configuration structure., Get default content paths for framework., Any

### Community 97 - "ClassBooking"
Cohesion: 0.19
Nodes (3): RosterController, ClassBookingController, ClassBooking

### Community 98 - "component-tokens.md"
Cohesion: 0.25
Nodes (8): Alert Tokens, Badge Tokens, Button Tokens, Card Tokens, Dialog/Modal Tokens, Input Tokens, Table Tokens, Usage Example

### Community 99 - "sync-brand-to-tokens.cjs"
Cohesion: 0.33
Nodes (8): adjustBrightness(), { execFileSync }, extractColorsFromMarkdown(), fs, generateColorScale(), main(), path, updateDesignTokens()

### Community 100 - "_run"
Cohesion: 0.28
Nodes (8): Path, Regression tests for validate-tokens.cjs. The validator used to skip any line…, A hardcoded hex on the same line as a var() token is still a violation., A line that references only tokens produces no false positives., _run(), test_flags_hardcoded_hex_sharing_line_with_token(), test_token_only_line_reports_no_violation(), CompletedProcess

### Community 101 - "BM25"
Cohesion: 0.28
Nodes (5): BM25, BM25 ranking algorithm for text search, Lowercase, split, remove punctuation, filter short words, Build BM25 index from documents, Score all documents against query

### Community 102 - "component-specs.md"
Cohesion: 0.25
Nodes (7): Anatomy, Button, Component Specifications, Input, Sizes, States, Variants

### Community 103 - "radius"
Cohesion: 0.29
Nodes (8): $type, $value, $type, $value, radius, default, full, default

### Community 104 - ".generate_config_string"
Cohesion: 0.20
Nodes (6): Generate configuration file content. Returns: Configuration file as string, Generate TypeScript configuration., Generate JavaScript configuration., Format plugins array for config. Validates each plugin name against a strict…, Add indentation to JSON string., Write configuration to file. Returns: Tuple of (success, message)

### Community 105 - "format_ascii_box"
Cohesion: 0.25
Nodes (8): ansi_ljust(), format_ascii_box(), hex_to_ansi(), Convert hex color to ANSI True Color swatch (██) with fallback., Like str.ljust but accounts for zero-width ANSI escape sequences., Create a Unicode section separator: ├─── NAME ───...┤, Format design system as Unicode box with ANSI color swatches., section_header()

### Community 106 - "CheckMemberActive.php"
Cohesion: 0.43
Nodes (4): CheckMemberActive, CheckRole, Closure, Symfony\Component\HttpFoundation\Response

### Community 107 - "require"
Cohesion: 0.22
Nodes (9): require, intervention/image, laravel/framework, laravel/reverb, laravel/tinker, midtrans/midtrans-php, php, simplesoftwareio/simple-qrcode (+1 more)

### Community 108 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 109 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 110 - "README.md"
Cohesion: 0.25
Nodes (7): About Laravel, Agentic Development, Code of Conduct, Contributing, Learning Laravel, License, Security Vulnerabilities

### Community 111 - "slides-html-template.md"
Cohesion: 0.29
Nodes (6): Animation Classes, Background Images, Base Structure, Chart.js Integration, CSS Variables Reference, HTML Slide Template

### Community 112 - "slides.md"
Cohesion: 0.29
Nodes (6): Key Features, Knowledge Base, Slides Reference, Usage, When to Use, Workflow

### Community 113 - "html-template.md"
Cohesion: 0.29
Nodes (6): Animation Classes, Background Images, Base Structure, Chart.js Integration, CSS Variables Reference, HTML Slide Template

### Community 115 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 116 - "slides/SKILL.md"
Cohesion: 0.33
Nodes (5): References (Knowledge Base), Routing, Slides, Subcommands, When to Use

### Community 117 - "shadow"
Cohesion: 0.47
Nodes (6): sm, shadow, sm, sm, $type, $value

### Community 119 - "TestCase"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

### Community 120 - "lg"
Cohesion: 0.60
Nodes (5): lg, $type, $value, lg, lg

### Community 121 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 122 - "MembershipRenewal"
Cohesion: 0.13
Nodes (3): ApprovalCenterController, RenewalController, MembershipRenewal

### Community 123 - "md"
Cohesion: 0.67
Nodes (4): $type, $value, md, md

### Community 124 - "xl"
Cohesion: 0.67
Nodes (4): xl, xl, $type, $value

### Community 126 - "Illuminate\Database\Seeder"
Cohesion: 0.27
Nodes (4): DatabaseSeeder, MembershipPackageSeeder, UserSeeder, Illuminate\Database\Seeder

### Community 131 - "Controller"
Cohesion: 0.12
Nodes (7): LoginController, LogoutController, Controller, ClassHistoryController, DashboardController, InvoiceController, ProfileController

### Community 256 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 259 - ".syncFromBookings"
Cohesion: 0.38
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

### Community 260 - "none"
Cohesion: 0.67
Nodes (4): $type, $value, none, none

### Community 261 - "test"
Cohesion: 0.67
Nodes (3): test, @php artisan config:clear --ansi @no_additional_args, @php artisan test

## Knowledge Gaps
- **729 isolated node(s):** `fs`, `path`, `fs`, `path`, `fs` (+724 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **35 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `ApprovalCenterController.php`, `ClassBooking`, `MembershipPackage`, `AttendanceVerification`, `Member`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `Coach`, `ClassSchedule`, `Illuminate\Http\Request`, `User`, `Product`, `ScanQrController`, `MembershipRenewal`, `RegistrationController`, `StaffInvitation`?**
  _High betweenness centrality (0.013) - this node is a cross-community bridge._
- **Why does `primitive` connect `primitive` to `gray`, `color`, `radius`, `spacing`, `shadow`, `fontSize`?**
  _High betweenness centrality (0.012) - this node is a cross-community bridge._
- **Why does `Member` connect `Member` to `ApprovalCenterController.php`, `CheckMembershipExpiry.php`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `Illuminate\Http\Request`, `ScanQrController`, `MembershipRenewal`, `RegistrationController`, `StaffInvitation`?**
  _High betweenness centrality (0.008) - this node is a cross-community bridge._
- **Are the 2 inferred relationships involving `TailwindConfigGenerator` (e.g. with `TestGeneratedConfigIsValidJs` and `TestTailwindConfigGenerator`) actually correct?**
  _`TailwindConfigGenerator` has 2 INFERRED edges - model-reasoned connections that need verification._
- **Are the 13 inferred relationships involving `Member` (e.g. with `.handle()` and `.markExpired()`) actually correct?**
  _`Member` has 13 INFERRED edges - model-reasoned connections that need verification._
- **What connects `fs`, `path`, `fs` to the rest of the system?**
  _729 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `ApprovalCenterController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.1443850267379679 - nodes in this community are weakly interconnected._