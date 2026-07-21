# Contributing

## Branch Naming

Use clear branch names tied to the sprint or workstream.

- `feature/s0-quality-ci`
- `feature/s1-administration`
- `fix/auth-last-login`

## Sprint Convention

- Sprint foundation work should reference the sprint number in branch names and pull requests.
- Keep Sprint 0 work focused on platform and engineering foundations rather than business modules.

## Commit Messages

Use concise imperative messages.

- `Add PostgreSQL compatibility CI job`
- `Tighten login status checks`
- `Expand tenancy test helpers`

## Pull Requests

- Target `develop` unless the change is specifically intended for `main`.
- Include a short summary of behavior changes.
- List validation performed.
- Call out migrations, environment changes, and follow-up work.

## Definition of Done

- Code is formatted with Pint.
- Static analysis passes.
- Relevant Pest coverage exists and passes.
- Documentation is updated where behavior or setup changed.
- No secrets, local builds, or generated dependency folders are committed.
