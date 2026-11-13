# Phase checklist

## Discovery
- [ ] Read project instructions and source-of-truth docs.
- [ ] Inspect relevant code/tests and Git status/branch.
- [ ] Preserve existing user changes.
- [ ] Define scope, exclusions, dependencies, risks.

## Stage A — Design
- [ ] Existing-state audit with file references.
- [ ] Proposed architecture and data flow.
- [ ] Data/storage/migration implications.
- [ ] Authorization, validation, security, privacy.
- [ ] UX/accessibility/responsive behavior.
- [ ] Compatibility and regression assessment.
- [ ] File impact list.
- [ ] Test/verification plan.
- [ ] Risks, open questions, rollback.
- [ ] Stop for explicit approval.

## Stage B — Implementation
- [ ] Reconfirm approved scope and Git state.
- [ ] Implement narrowly using project conventions.
- [ ] Add/update tests.
- [ ] Seek approval if design/scope materially changes.

## Stage C — Verification
- [ ] Targeted tests and broader suite where practical.
- [ ] Lint/static/type/security checks where configured.
- [ ] Build/compile checks where relevant.
- [ ] Browser/manual checks where available.
- [ ] Record actual results and limitations.

## Stage D — Acceptance
- [ ] Implementation report.
- [ ] Acceptance report mapped to criteria.
- [ ] Docs/walkthrough updated where needed.
- [ ] Separate verified, unverified, deferred, and limited items.

## Stage E — Git (if authorized)
- [ ] Review diff/status and check for secrets/unrelated files.
- [ ] Stage intended files only.
- [ ] Commit/push only when explicitly authorized.
- [ ] Verify and report actual result.
