# AGENTS.md — mistralys/text-diff

> **Operating instructions for AI agents entering this codebase.**
> Read this file first. Follow its directives exactly.

---

## 1. Project Manifest — Start Here!

**Location:** `docs/agents/project-manifest/`

The Project Manifest is the **Source of Truth** for this repository. Consult it before reading any source file.

| Document | File | Description |
|---|---|---|
| Overview | [README.md](docs/agents/project-manifest/README.md) | Manifest index and project summary. |
| Tech Stack & Patterns | [tech-stack.md](docs/agents/project-manifest/tech-stack.md) | Runtime, dependencies, architecture, build & test tooling. |
| File Tree | [file-tree.md](docs/agents/project-manifest/file-tree.md) | Annotated directory structure. |
| Public API Surface | [api-surface.md](docs/agents/project-manifest/api-surface.md) | Every public class, method, and constant. |
| Key Data Flows | [data-flows.md](docs/agents/project-manifest/data-flows.md) | Main interaction paths through the library. |
| Constraints & Conventions | [constraints.md](docs/agents/project-manifest/constraints.md) | Rules, conventions, and gotchas. |

### Quick Start Workflow

1. **Read** `README.md` — understand what the library does and its manifest layout.
2. **Read** `tech-stack.md` — know the runtime, dependencies, and architectural patterns.
3. **Read** `constraints.md` — internalize every convention and gotcha before touching code.
4. **Reference** `file-tree.md` — locate files without scanning the filesystem.
5. **Reference** `api-surface.md` — understand method signatures without reading source.
6. **Reference** `data-flows.md` — trace how data moves through the library.

---

## 2. Manifest Maintenance Rules

When code changes are made, the corresponding manifest documents **must** be updated to prevent drift.

| Change Made | Documents to Update |
|---|---|
| New class or file added | `file-tree.md`, `api-surface.md` |
| Class or file renamed / moved | `file-tree.md`, `api-surface.md` |
| Public method added / changed / removed | `api-surface.md` |
| Dependency added or removed | `tech-stack.md` |
| Directory restructured | `file-tree.md` |
| New renderer created | `file-tree.md`, `api-surface.md`, `data-flows.md`, `tech-stack.md` |
| Comparison or rendering flow changed | `data-flows.md` |
| New constraint, convention, or gotcha discovered | `constraints.md` |
| CSS classes or stylesheet changed | `constraints.md` (CSS Naming section) |
| PHPUnit config or test suite changed | `tech-stack.md`, `constraints.md` (Test Structure section) |
| New constant or error code added | `api-surface.md` |

---

## 3. Efficiency Rules — Search Smart

Do **not** scan source files when the answer is already in the manifest.

- **Finding a file?** Check `file-tree.md` FIRST.
- **Understanding a method signature?** Check `api-surface.md` FIRST.
- **Looking for implementation patterns?** Check `tech-stack.md` FIRST.
- **Tracing data flow?** Check `data-flows.md` FIRST.
- **Checking conventions?** Check `constraints.md` FIRST.
- **Only then** fall through to reading source files in `src/`.

---

## 4. Failure Protocol & Decision Matrix

| Scenario | Action | Priority |
|---|---|---|
| Ambiguous requirement | Use the most restrictive interpretation. | MUST |
| Manifest/code conflict | Trust the manifest. Flag the code for review. | MUST |
| Missing documentation | Flag the gap explicitly. Do not invent facts. | MUST |
| Untested code path | Proceed with caution. Add a test recommendation. | SHOULD |
| Namespace discrepancy (`Mistrals` vs `Mistralys`) | Do **not** "correct" the renderer namespace `Mistrals\Diff\Renderer`. It is intentional. See `constraints.md`. | MUST |
| Calling `toArray()` / render method twice on same `Diff` instance | This will fail. `toArray()` is destructive (single-use). Create a new `Diff` instance instead. | MUST |
| Calling render after `dispose()` | Throws `DiffException`. Never call `dispose()` before a render method. | MUST |
| Adding a new source file | Declare `declare(strict_types=1);` at the top. | MUST |
| File I/O needed | Use `AppUtils\FileHelper::readContents()`, not native PHP functions. | MUST |
| Styler test suite not running | The `Styler/` test directory exists but is **not** registered in `phpunit.xml`. If styler tests must run, register the suite. | SHOULD |

---

## 5. Project Stats

| Property | Value |
|---|---|
| **Language / Runtime** | PHP 7.4+ (strict typing) |
| **Architecture** | Static factory + Strategy/Renderer pattern |
| **Package manager** | Composer (`classmap` autoloading) |
| **Package name** | `mistralys/text-diff` |
| **License** | MIT |
| **Test framework** | PHPUnit ≥ 9.6 |
| **Static analysis** | PHPStan ≥ 1.9 (`docs/config/phpstan.neon`) |
| **Build/test shortcut** | `run-tests.bat` (project root) |
| **Key dependency** | `mistralys/application-utils ≥ 1.2.5` |
| **Current version** | 2.0.2 |
