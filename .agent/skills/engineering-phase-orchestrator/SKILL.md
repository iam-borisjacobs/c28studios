---
name: engineering-phase-orchestrator
description: Project-agnostic workflow for auditing, designing, approval-gated implementation, testing, documentation, acceptance, and optional Git delivery of software engineering phases.
---

# Engineering Phase Orchestrator

Use this skill for features, bug fixes, refactors, integrations, security improvements, UI changes, and infrastructure work across different software projects.

## Portability rule
Never assume a company, product, framework, language, database, architecture, directory layout, roles, colors, deployment platform, or Git convention. Discover them from the current repository and its authoritative instructions. Keep project-specific policy in project-level configuration, not in this skill.

## Core rules
1. Inspect before changing.
2. Treat repository instructions, architecture docs, requirements, and existing code as the source of truth.
3. Separate design from implementation.
4. Do not implement until the user explicitly approves Stage A.
5. Implement only the approved scope; stop if material design changes become necessary.
6. Preserve existing user changes and behavior.
7. Add tests for changed behavior and relevant failure/security cases.
8. Report only checks actually run and results actually observed.
9. Document implementation and acceptance.
10. Commit or push only with explicit authorization. Never force-push without explicit request and risk explanation.

## Stage 0 — Project and task discovery
- Find repository root and read project instructions (for example AGENTS.md), README, contribution guide, architecture docs, PRD/specifications, and relevant module docs.
- Discover stack, versions, package manager, runtime, database, storage, auth/RBAC, tests, build process, deployment model, and conventions from evidence.
- Inspect `git status`, branch, relevant history, and uncommitted changes. Never overwrite or discard them.
- Trace the relevant routes, handlers/controllers, services, models, validators, views/components, styles, scripts, migrations, tests, and docs.
- Define requested outcome, scope, exclusions, dependencies, assumptions, and blocking questions.
- Never expose secrets discovered in repository files or logs.

## Stage A — Design review (NO IMPLEMENTATION)
Produce a design review containing:
1. Objective and user-visible outcome.
2. Existing-state findings with exact file paths/symbols.
3. Current architecture and conventions.
4. Proposed design, component boundaries, and data flow.
5. Data/storage impact, migrations, backfills, and rollback.
6. Authorization, validation, security, privacy, and input/output handling.
7. UX, accessibility, responsive behavior, where applicable.
8. Compatibility and regression risks.
9. Alternatives and recommendation.
10. Expected files to create/modify/remove.
11. Test and verification plan.
12. Risks, assumptions, open questions.
13. Rollout and rollback plan where relevant.

Use existing documentation conventions. If none exist, propose `docs/<area>/<phase>/01-design-review.md`.

**Hard stop:** State that no implementation was performed and wait for clear user approval. Silence or ambiguous feedback is not approval.

## Stage B — Approved implementation
Begin only after explicit approval.
- Recheck Git status and preserve pre-existing changes.
- Confirm scope and resolve blockers.
- Follow project patterns and framework idioms; reuse existing services/components where appropriate.
- Enforce server-side authorization and validation; safely encode output.
- Handle uploads, storage, transactions, concurrency, idempotency, and failure recovery where relevant.
- For UI, use accessible semantics, keyboard support, visible focus, and responsive layouts.
- Keep secrets and environment-specific values out of source control.
- Add/update positive, negative, authorization, validation, edge, and regression tests as relevant.
- Do not weaken/delete tests merely to make them pass.
- Avoid unrelated refactors and broad formatting churn.
- If the approved design must materially change, stop and seek approval.

## Stage C — Verification
Run targeted tests first, then broader checks as practical:
- Unit/feature/integration tests.
- Full suite where feasible.
- Lint, static analysis, type checks, and security checks where configured.
- Production build/asset compilation where relevant.
- Framework route/config/view/schema checks where relevant.
- Browser/manual checks for UI when available.

Record exact commands/procedures, completion status, results/counts, warnings, skipped checks, environment limits, and known gaps. Do not claim a running test has passed. If browser verification was unavailable, say so.

## Stage D — Documentation and acceptance
Follow project documentation conventions. Suggested records:
- `01-design-review.md`
- `02-implementation.md`
- `03-acceptance.md`

The acceptance report must distinguish verified work, implemented but unverified work, deferred work, and limitations. Update README/changelog/API/operator docs/walkthrough only when warranted.

## Stage E — Git delivery (only when authorized)
- Review diff and status.
- Check secrets, generated artifacts, debug output, accidental files, and unrelated changes.
- Stage only intended files.
- Commit only if authorized, following project conventions.
- Push only if authorized and branch/remote are confirmed.
- Never rewrite history or force-push without explicit authorization.
- Report branch, commit hash, and push outcome only after verification.

## Approval gate
End Stage A with wording equivalent to:
> Stage A complete — design review only. No implementation files were changed. Awaiting explicit approval to begin implementation.

Clear instructions such as “proceed with implementation”, “approved”, or “start Stage B” authorize the reviewed scope. If scope changes, revise the design and obtain approval for the changed scope.

## Project-specific configuration
Look for project-level instructions defining product terminology, architecture constraints, roles, design system, quality gates, docs paths, Git policy, and deployment policy. Do not invent missing values. Label assumptions and ask when they materially affect correctness or security.

## Final reporting
Report phase/status, completed scope, important files, security/architecture notes, actual verification results, docs updated, Git result if applicable, and remaining issues/approval gate. Never fabricate paths, test counts, assertion counts, timings, hashes, or tool outcomes.
